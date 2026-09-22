<?php
require_once __DIR__ . '/config_paths.php';
require_once CONFIG_PATH;

/**
 * Classe para gerenciar fornecedores
 */
class Supplier {
    private $db;
    
    public function __construct() {
        $this->db = getDB();
    }
    
    /**
     * Listar todos os fornecedores
     */
    public function getAll($filters = []) {
        $sql = "SELECT * FROM suppliers WHERE 1=1";
        $params = [];
        
        // Filtros
        if (!empty($filters['status'])) {
            $sql .= " AND status = :status";
            $params['status'] = $filters['status'];
        }
        
        if (!empty($filters['featured'])) {
            $sql .= " AND featured = :featured";
            $params['featured'] = $filters['featured'];
        }
        
        if (!empty($filters['search'])) {
            $sql .= " AND (name LIKE :search OR company_name LIKE :search OR city LIKE :search)";
            $params['search'] = '%' . $filters['search'] . '%';
        }
        
        $sql .= " ORDER BY featured DESC, name ASC";
        
        return $this->db->fetchAll($sql, $params);
    }
    
    /**
     * Obter fornecedor por ID
     */
    public function getById($id) {
        return $this->db->fetchOne(
            "SELECT * FROM suppliers WHERE id = :id",
            ['id' => $id]
        );
    }
    
    /**
     * Obter fornecedores em destaque
     */
    public function getFeatured($limit = 6) {
        return $this->db->fetchAll(
            "SELECT * FROM suppliers WHERE status = 'active' AND featured = 1 ORDER BY name ASC LIMIT :limit",
            ['limit' => $limit]
        );
    }
    
    /**
     * Criar novo fornecedor
     */
    public function create($data) {
        try {
            // Processar especialidades (converter array para JSON)
            if (isset($data['specialties']) && is_array($data['specialties'])) {
                $data['specialties'] = json_encode($data['specialties']);
            }
            
            // Limpar dados
            $data = $this->sanitizeData($data);
            
            $id = $this->db->insert('suppliers', $data);
            
            return $id;
        } catch (Exception $e) {
            throw new Exception("Erro ao criar fornecedor: " . $e->getMessage());
        }
    }
    
    /**
     * Atualizar fornecedor
     */
    public function update($id, $data) {
        try {
            // Processar especialidades
            if (isset($data['specialties']) && is_array($data['specialties'])) {
                $data['specialties'] = json_encode($data['specialties']);
            }
            
            // Limpar dados
            $data = $this->sanitizeData($data);
            
            $this->db->update('suppliers', $data, 'id = :id', ['id' => $id]);
            
            return true;
        } catch (Exception $e) {
            throw new Exception("Erro ao atualizar fornecedor: " . $e->getMessage());
        }
    }
    
    /**
     * Deletar fornecedor
     */
    public function delete($id) {
        try {
            // Verificar se há produtos vinculados
            $products = $this->db->fetchOne(
                "SELECT COUNT(*) as count FROM products WHERE supplier_id = :id",
                ['id' => $id]
            );
            
            if ($products['count'] > 0) {
                throw new Exception("Não é possível deletar fornecedor com produtos vinculados");
            }
            
            $this->db->delete('suppliers', 'id = :id', ['id' => $id]);
            
            return true;
        } catch (Exception $e) {
            throw new Exception("Erro ao deletar fornecedor: " . $e->getMessage());
        }
    }
    
    /**
     * Alternar status do fornecedor
     */
    public function toggleStatus($id) {
        try {
            $supplier = $this->getById($id);
            if (!$supplier) {
                throw new Exception("Fornecedor não encontrado");
            }
            
            $newStatus = $supplier['status'] === 'active' ? 'inactive' : 'active';
            
            $this->db->update(
                'suppliers',
                ['status' => $newStatus],
                'id = :id',
                ['id' => $id]
            );
            
            return $newStatus;
        } catch (Exception $e) {
            throw new Exception("Erro ao alterar status: " . $e->getMessage());
        }
    }
    
    /**
     * Alternar destaque do fornecedor
     */
    public function toggleFeatured($id) {
        try {
            $supplier = $this->getById($id);
            if (!$supplier) {
                throw new Exception("Fornecedor não encontrado");
            }
            
            $newFeatured = $supplier['featured'] ? 0 : 1;
            
            $this->db->update(
                'suppliers',
                ['featured' => $newFeatured],
                'id = :id',
                ['id' => $id]
            );
            
            return $newFeatured;
        } catch (Exception $e) {
            throw new Exception("Erro ao alterar destaque: " . $e->getMessage());
        }
    }
    
    /**
     * Upload de logo do fornecedor
     */
    public function uploadLogo($id, $file) {
        try {
            $uploadDir = '../uploads/suppliers/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            // Validar arquivo
            $allowedTypes = ['jpg', 'jpeg', 'png', 'webp'];
            $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            
            if (!in_array($fileExtension, $allowedTypes)) {
                throw new Exception("Tipo de arquivo não permitido");
            }
            
            if ($file['size'] > UPLOAD_MAX_SIZE) {
                throw new Exception("Arquivo muito grande");
            }
            
            // Gerar nome único
            $fileName = 'supplier_' . $id . '_' . time() . '.' . $fileExtension;
            $filePath = $uploadDir . $fileName;
            
            if (move_uploaded_file($file['tmp_name'], $filePath)) {
                // Atualizar banco de dados
                $this->db->update(
                    'suppliers',
                    ['logo_image' => $fileName],
                    'id = :id',
                    ['id' => $id]
                );
                
                return $fileName;
            } else {
                throw new Exception("Erro ao fazer upload do arquivo");
            }
        } catch (Exception $e) {
            throw new Exception("Erro no upload: " . $e->getMessage());
        }
    }
    
    /**
     * Obter estatísticas dos fornecedores
     */
    public function getStats() {
        $stats = [];
        
        // Total de fornecedores
        $stats['total'] = $this->db->fetchOne("SELECT COUNT(*) as count FROM suppliers")['count'];
        
        // Fornecedores ativos
        $stats['active'] = $this->db->fetchOne("SELECT COUNT(*) as count FROM suppliers WHERE status = 'active'")['count'];
        
        // Fornecedores em destaque
        $stats['featured'] = $this->db->fetchOne("SELECT COUNT(*) as count FROM suppliers WHERE featured = 1")['count'];
        
        // Fornecedores por estado
        $stats['by_state'] = $this->db->fetchAll("SELECT state, COUNT(*) as count FROM suppliers WHERE state IS NOT NULL GROUP BY state ORDER BY count DESC");
        
        return $stats;
    }
    
    /**
     * Limpar e validar dados
     */
    private function sanitizeData($data) {
        $cleaned = [];
        
        $fields = [
            'name', 'company_name', 'cnpj', 'contact_person', 'email', 'phone', 
            'whatsapp', 'website', 'address', 'city', 'state', 'zip_code', 
            'description', 'specialties', 'status', 'featured'
        ];
        
        foreach ($fields as $field) {
            if (isset($data[$field])) {
                $cleaned[$field] = trim($data[$field]);
                
                // Validações específicas
                if ($field === 'email' && !empty($cleaned[$field])) {
                    if (!filter_var($cleaned[$field], FILTER_VALIDATE_EMAIL)) {
                        throw new Exception("Email inválido");
                    }
                }
                
                if ($field === 'website' && !empty($cleaned[$field])) {
                    if (!filter_var($cleaned[$field], FILTER_VALIDATE_URL)) {
                        throw new Exception("Website inválido");
                    }
                }
                
                if ($field === 'cnpj' && !empty($cleaned[$field])) {
                    $cleaned[$field] = preg_replace('/[^0-9]/', '', $cleaned[$field]);
                }
                
                if ($field === 'phone' || $field === 'whatsapp') {
                    $cleaned[$field] = preg_replace('/[^0-9+() -]/', '', $cleaned[$field]);
                }
            }
        }
        
        return $cleaned;
    }
    
    /**
     * Buscar fornecedores por especialidade
     */
    public function getBySpecialty($specialty) {
        return $this->db->fetchAll(
            "SELECT * FROM suppliers WHERE status = 'active' AND JSON_CONTAINS(specialties, :specialty) ORDER BY featured DESC, name ASC",
            ['specialty' => json_encode($specialty)]
        );
    }

    /**
     * Buscar fornecedores
     */
    public function search($query) {
        try {
            $searchTerm = '%' . $query . '%';
            return $this->db->fetchAll(
                "SELECT * FROM suppliers 
                 WHERE name LIKE :query1 OR company_name LIKE :query2 OR description LIKE :query3
                 ORDER BY name ASC
                 LIMIT 20",
                [
                    'query1' => $searchTerm,
                    'query2' => $searchTerm,
                    'query3' => $searchTerm
                ]
            );
        } catch (Exception $e) {
            error_log("Erro ao buscar fornecedores: " . $e->getMessage());
            return [];
        }
    }
}
?>
