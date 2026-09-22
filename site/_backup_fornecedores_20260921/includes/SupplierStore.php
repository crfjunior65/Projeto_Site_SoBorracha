<?php
/**
 * SupplierStore - Persistência de fornecedores em arquivo JSON (sem banco).
 * Só Borracha Ltda
 *
 * Lê/grava data/fornecedores.json com trava de arquivo e valida os dados.
 */

class SupplierStore {

    private $file;

    public function __construct($file = null) {
        $this->file = $file ?: (__DIR__ . '/../data/fornecedores.json');
    }

    /** Lê todos os fornecedores (array). */
    public function readAll() {
        if (!is_readable($this->file)) {
            return [];
        }
        $data = json_decode(file_get_contents($this->file), true);
        return is_array($data) ? $data : [];
    }

    /** Grava a lista completa com trava exclusiva. */
    private function writeAll($list) {
        $dir = dirname($this->file);
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }
        $json = json_encode(array_values($list), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        if ($json === false) {
            return false;
        }
        return file_put_contents($this->file, $json, LOCK_EX) !== false;
    }

    /**
     * Lista fornecedores.
     * @param bool $onlyActive Retorna apenas ativos (site público).
     */
    public function all($onlyActive = false) {
        $list = $this->readAll();

        if ($onlyActive) {
            $list = array_filter($list, function ($s) {
                return ($s['status'] ?? 'active') === 'active';
            });
        }

        // Destaques primeiro, depois por nome
        usort($list, function ($a, $b) {
            $fa = !empty($a['featured']) ? 0 : 1;
            $fb = !empty($b['featured']) ? 0 : 1;
            if ($fa === $fb) {
                return strcmp($a['name'] ?? '', $b['name'] ?? '');
            }
            return $fa <=> $fb;
        });

        return array_values($list);
    }

    /** Busca por id. */
    public function find($id) {
        foreach ($this->readAll() as $s) {
            if (($s['id'] ?? null) === $id) {
                return $s;
            }
        }
        return null;
    }

    /** Cria ou atualiza um fornecedor. Retorna o registro salvo ou false. */
    public function save($input) {
        $list = $this->readAll();
        $fornecedor = $this->normalize($input);

        $index = -1;
        foreach ($list as $i => $s) {
            if (($s['id'] ?? null) === $fornecedor['id']) {
                $index = $i;
                break;
            }
        }

        if ($index >= 0) {
            // Preserva logo antigo se não veio novo
            if (empty($fornecedor['logo_image'])) {
                $fornecedor['logo_image'] = $list[$index]['logo_image'] ?? '';
            }
            $list[$index] = $fornecedor;
        } else {
            $list[] = $fornecedor;
        }

        return $this->writeAll($list) ? $fornecedor : false;
    }

    /** Remove por id. Retorna o logo removido (para limpeza) ou null. */
    public function delete($id) {
        $list = $this->readAll();
        $logoRemovido = null;
        $novos = [];
        foreach ($list as $s) {
            if (($s['id'] ?? null) === $id) {
                $logoRemovido = $s['logo_image'] ?? null;
                continue;
            }
            $novos[] = $s;
        }
        $this->writeAll($novos);
        return $logoRemovido;
    }

    /** Normaliza os campos vindos do formulário. */
    private function normalize($input) {
        $name = trim($input['name'] ?? '');

        $id = trim($input['id'] ?? '');
        if ($id === '') {
            $id = $this->slugify($name);
        }

        // Especialidades: array ou string separada por vírgula/linha
        $specialties = $input['specialties'] ?? [];
        if (is_string($specialties)) {
            $specialties = preg_split('/[\r\n,]+/', $specialties);
        }
        $specialties = array_values(array_filter(array_map('trim', (array) $specialties)));

        $status = ($input['status'] ?? 'active') === 'active' ? 'active' : 'inactive';

        return [
            'id'           => $id,
            'name'         => $name,
            'company_name' => trim($input['company_name'] ?? ''),
            'email'        => trim($input['email'] ?? ''),
            'phone'        => trim($input['phone'] ?? ''),
            'whatsapp'     => trim($input['whatsapp'] ?? ''),
            'website'      => trim($input['website'] ?? ''),
            'city'         => trim($input['city'] ?? ''),
            'state'        => strtoupper(trim($input['state'] ?? '')),
            'description'  => trim($input['description'] ?? ''),
            'logo_image'   => trim($input['logo_image'] ?? ''),
            'specialties'  => $specialties,
            'status'       => $status,
            'featured'     => !empty($input['featured']),
        ];
    }

    /** Gera slug seguro para IDs. */
    public function slugify($text) {
        $text = strtolower(trim($text));
        $map = ['á'=>'a','à'=>'a','â'=>'a','ã'=>'a','ä'=>'a','é'=>'e','è'=>'e','ê'=>'e','ë'=>'e',
                'í'=>'i','ì'=>'i','î'=>'i','ï'=>'i','ó'=>'o','ò'=>'o','ô'=>'o','õ'=>'o','ö'=>'o',
                'ú'=>'u','ù'=>'u','û'=>'u','ü'=>'u','ç'=>'c'];
        $text = strtr($text, $map);
        $text = preg_replace('/[^a-z0-9]+/', '-', $text);
        $text = trim($text, '-');
        if ($text === '') {
            $text = 'fornecedor-' . substr(md5(uniqid('', true)), 0, 6);
        }
        return $text;
    }
}
