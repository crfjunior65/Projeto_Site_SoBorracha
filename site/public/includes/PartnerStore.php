<?php
/**
 * PartnerStore - Persistência de PARCEIROS em arquivo JSON (sem banco).
 * Só Borracha
 *
 * Parceiros = rede de distribuidores, revendedores, lojas e oficinas
 * afiliados/credenciados à Só Borracha. Lê/grava data/parceiros.json.
 */

class PartnerStore {

    private $file;

    /** Tipos de parceiro exibidos no site e no filtro do admin. */
    public static $tipos = [
        'distribuidor' => 'Distribuidor',
        'revendedor'   => 'Revendedor',
        'loja'         => 'Loja Parceira',
        'oficina'      => 'Oficina Credenciada',
    ];

    public function __construct($file = null) {
        $this->file = $file ?: (__DIR__ . '/../data/parceiros.json');
    }

    public function readAll() {
        if (!is_readable($this->file)) {
            return [];
        }
        $data = json_decode(file_get_contents($this->file), true);
        return is_array($data) ? $data : [];
    }

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
     * Lista parceiros.
     * @param bool $onlyActive Retorna apenas ativos (site público).
     */
    public function all($onlyActive = false) {
        $list = $this->readAll();

        if ($onlyActive) {
            $list = array_filter($list, function ($s) {
                return ($s['status'] ?? 'active') === 'active';
            });
        }

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

    public function find($id) {
        foreach ($this->readAll() as $s) {
            if (($s['id'] ?? null) === $id) {
                return $s;
            }
        }
        return null;
    }

    public function save($input) {
        $list = $this->readAll();
        $parceiro = $this->normalize($input);

        $index = -1;
        foreach ($list as $i => $s) {
            if (($s['id'] ?? null) === $parceiro['id']) {
                $index = $i;
                break;
            }
        }

        if ($index >= 0) {
            if (empty($parceiro['logo_image'])) {
                $parceiro['logo_image'] = $list[$index]['logo_image'] ?? '';
            }
            $list[$index] = $parceiro;
        } else {
            $list[] = $parceiro;
        }

        return $this->writeAll($list) ? $parceiro : false;
    }

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

    private function normalize($input) {
        $name = trim($input['name'] ?? '');

        $id = trim($input['id'] ?? '');
        if ($id === '') {
            $id = $this->slugify($name);
        }

        $tipo = $input['tipo'] ?? 'revendedor';
        if (!isset(self::$tipos[$tipo])) {
            $tipo = 'revendedor';
        }

        $status = ($input['status'] ?? 'active') === 'active' ? 'active' : 'inactive';

        return [
            'id'           => $id,
            'name'         => $name,
            'company_name' => trim($input['company_name'] ?? ''),
            'tipo'         => $tipo,
            'email'        => trim($input['email'] ?? ''),
            'phone'        => trim($input['phone'] ?? ''),
            'whatsapp'     => trim($input['whatsapp'] ?? ''),
            'website'      => trim($input['website'] ?? ''),
            'city'         => trim($input['city'] ?? ''),
            'state'        => strtoupper(trim($input['state'] ?? '')),
            'description'  => trim($input['description'] ?? ''),
            'logo_image'   => trim($input['logo_image'] ?? ''),
            'status'       => $status,
            'featured'     => !empty($input['featured']),
        ];
    }

    public function slugify($text) {
        $text = strtolower(trim($text));
        $map = ['á'=>'a','à'=>'a','â'=>'a','ã'=>'a','ä'=>'a','é'=>'e','è'=>'e','ê'=>'e','ë'=>'e',
                'í'=>'i','ì'=>'i','î'=>'i','ï'=>'i','ó'=>'o','ò'=>'o','ô'=>'o','õ'=>'o','ö'=>'o',
                'ú'=>'u','ù'=>'u','û'=>'u','ü'=>'u','ç'=>'c'];
        $text = strtr($text, $map);
        $text = preg_replace('/[^a-z0-9]+/', '-', $text);
        $text = trim($text, '-');
        if ($text === '') {
            $text = 'parceiro-' . substr(md5(uniqid('', true)), 0, 6);
        }
        return $text;
    }
}
