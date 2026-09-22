<?php
/**
 * ProductStore - Persistência de produtos em arquivo JSON (sem banco de dados).
 * Só Borracha
 *
 * Responsável por ler/gravar o arquivo data/produtos.json com trava de arquivo
 * (evita corrupção em gravações concorrentes) e por validar/normalizar os dados.
 */

class ProductStore {

    private $file;

    /** Categorias válidas exibidas no site e no filtro do admin. */
    public static $categorias = [
        'porta'     => 'Borrachas de Porta',
        'parabrisa' => 'Borrachas de Parabrisa',
        'vidro'     => 'Borrachas de Vidro',
        'perfis'    => 'Perfis Especiais',
        'outros'    => 'Outros',
    ];

    public function __construct($file = null) {
        // Padrão: data/produtos.json na raiz do site público
        $this->file = $file ?: (__DIR__ . '/../data/produtos.json');
    }

    /** Lê todo o conteúdo (settings + produtos). Retorna estrutura padrão se faltar. */
    public function readAll() {
        if (!is_readable($this->file)) {
            return ['settings' => $this->defaultSettings(), 'produtos' => []];
        }
        $raw = file_get_contents($this->file);
        $data = json_decode($raw, true);
        if (!is_array($data)) {
            return ['settings' => $this->defaultSettings(), 'produtos' => []];
        }
        if (!isset($data['settings']) || !is_array($data['settings'])) {
            $data['settings'] = $this->defaultSettings();
        }
        if (!isset($data['produtos']) || !is_array($data['produtos'])) {
            $data['produtos'] = [];
        }
        return $data;
    }

    private function defaultSettings() {
        return [
            'vitrine_titulo'    => 'Nossos Produtos',
            'vitrine_subtitulo' => 'Alguns exemplos do nosso catálogo. Trabalhamos com milhares de opções.',
            'aviso_multimarcas' => 'Não encontrou? Fale conosco no WhatsApp e consulte a peça do seu veículo.',
        ];
    }

    /** Grava a estrutura completa com trava exclusiva. */
    private function writeAll($data) {
        $dir = dirname($this->file);
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }
        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        if ($json === false) {
            return false;
        }
        return file_put_contents($this->file, $json, LOCK_EX) !== false;
    }

    /** Retorna as configurações da vitrine. */
    public function getSettings() {
        $data = $this->readAll();
        return $data['settings'];
    }

    /** Atualiza as configurações da vitrine. */
    public function saveSettings($settings) {
        $data = $this->readAll();
        $data['settings'] = [
            'vitrine_titulo'    => trim($settings['vitrine_titulo'] ?? ''),
            'vitrine_subtitulo' => trim($settings['vitrine_subtitulo'] ?? ''),
            'aviso_multimarcas' => trim($settings['aviso_multimarcas'] ?? ''),
        ];
        return $this->writeAll($data);
    }

    /**
     * Lista produtos.
     * @param bool $onlyActive Se true, retorna apenas ativos (para o site público).
     */
    public function all($onlyActive = false) {
        $data = $this->readAll();
        $produtos = $data['produtos'];

        if ($onlyActive) {
            $produtos = array_filter($produtos, function ($p) {
                return !empty($p['ativo']);
            });
        }

        // Ordena por campo "ordem" (asc) e depois por nome
        usort($produtos, function ($a, $b) {
            $oa = $a['ordem'] ?? 999;
            $ob = $b['ordem'] ?? 999;
            if ($oa === $ob) {
                return strcmp($a['nome'] ?? '', $b['nome'] ?? '');
            }
            return $oa <=> $ob;
        });

        return array_values($produtos);
    }

    /** Busca um produto pelo id. Retorna null se não existir. */
    public function find($id) {
        foreach ($this->readAll()['produtos'] as $p) {
            if (($p['id'] ?? null) === $id) {
                return $p;
            }
        }
        return null;
    }

    /**
     * Cria ou atualiza um produto.
     * Se $input['id'] existir e já houver produto com esse id, atualiza; senão cria.
     * @return array|false O produto salvo, ou false em erro.
     */
    public function save($input) {
        $data = $this->readAll();

        $produto = $this->normalize($input);

        // Procura índice existente
        $index = -1;
        foreach ($data['produtos'] as $i => $p) {
            if (($p['id'] ?? null) === $produto['id']) {
                $index = $i;
                break;
            }
        }

        if ($index >= 0) {
            // Preserva imagem antiga se não veio nova
            if (empty($produto['imagem'])) {
                $produto['imagem'] = $data['produtos'][$index]['imagem'] ?? '';
            }
            $data['produtos'][$index] = $produto;
        } else {
            $data['produtos'][] = $produto;
        }

        return $this->writeAll($data) ? $produto : false;
    }

    /** Remove um produto pelo id. Retorna o caminho da imagem removida (para limpeza) ou null. */
    public function delete($id) {
        $data = $this->readAll();
        $imagemRemovida = null;
        $novos = [];
        foreach ($data['produtos'] as $p) {
            if (($p['id'] ?? null) === $id) {
                $imagemRemovida = $p['imagem'] ?? null;
                continue;
            }
            $novos[] = $p;
        }
        $data['produtos'] = $novos;
        $this->writeAll($data);
        return $imagemRemovida;
    }

    /** Normaliza e valida os campos de um produto vindos do formulário. */
    private function normalize($input) {
        $nome = trim($input['nome'] ?? '');

        // ID: usa o informado ou gera a partir do nome (slug)
        $id = trim($input['id'] ?? '');
        if ($id === '') {
            $id = $this->slugify($nome);
        }

        // Features: aceita array ou string separada por vírgula/linha
        $features = $input['features'] ?? [];
        if (is_string($features)) {
            $features = preg_split('/[\r\n,]+/', $features);
        }
        $features = array_values(array_filter(array_map('trim', (array) $features)));

        $categoria = $input['categoria'] ?? 'outros';
        if (!isset(self::$categorias[$categoria])) {
            $categoria = 'outros';
        }

        return [
            'id'              => $id,
            'nome'            => $nome,
            'categoria'       => $categoria,
            'descricao'       => trim($input['descricao'] ?? ''),
            'imagem'          => trim($input['imagem'] ?? ''),
            'features'        => $features,
            'compatibilidade' => trim($input['compatibilidade'] ?? ''),
            'destaque'        => !empty($input['destaque']),
            'badge'           => trim($input['badge'] ?? ''),
            'ordem'           => (int) ($input['ordem'] ?? 999),
            'ativo'           => !empty($input['ativo']),
        ];
    }

    /** Gera um slug seguro a partir de um texto (para IDs). */
    public function slugify($text) {
        $text = strtolower(trim($text));
        $text = preg_replace('/[áàâãä]/u', 'a', $text);
        $text = preg_replace('/[éèêë]/u', 'e', $text);
        $text = preg_replace('/[íìîï]/u', 'i', $text);
        $text = preg_replace('/[óòôõö]/u', 'o', $text);
        $text = preg_replace('/[úùûü]/u', 'u', $text);
        $text = preg_replace('/[ç]/u', 'c', $text);
        $text = preg_replace('/[^a-z0-9]+/', '-', $text);
        $text = trim($text, '-');
        if ($text === '') {
            $text = 'produto-' . substr(md5(uniqid('', true)), 0, 6);
        }
        return $text;
    }
}
