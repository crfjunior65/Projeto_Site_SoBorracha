<?php
require_once __DIR__ . '/config_paths.php';
require_once CONFIG_PATH;

/**
 * Classe para gerenciar autenticação de administradores
 */
class Auth {
    private $db;
    
    public function __construct() {
        $this->db = getDB();
        
        // Configurar sessão
        if (session_status() === PHP_SESSION_NONE) {
            session_name(ADMIN_SESSION_NAME);
            session_start();
        }
    }
    
    /**
     * Fazer login do usuário
     */
    public function login($username, $password) {
        try {
            $user = $this->db->fetchOne(
                "SELECT * FROM admin_users WHERE (username = :username OR email = :email) AND status = 'active'",
                ['username' => $username, 'email' => $username]
            );
            
            if ($user && password_verify($password, $user['password'])) {
                // Atualizar último login
                $this->db->update(
                    'admin_users',
                    ['last_login' => date('Y-m-d H:i:s')],
                    'id = :id',
                    ['id' => $user['id']]
                );
                
                // Criar sessão
                $_SESSION['admin_user'] = [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'email' => $user['email'],
                    'full_name' => $user['full_name'],
                    'role' => $user['role'],
                    'login_time' => time()
                ];
                
                // Log da atividade
                $this->logActivity($user['id'], 'login', null, null, 'Login realizado com sucesso');
                
                return true;
            }
            
            return false;
        } catch (Exception $e) {
            error_log("Erro no login: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Fazer logout do usuário
     */
    public function logout() {
        if ($this->isLoggedIn()) {
            $this->logActivity($_SESSION['admin_user']['id'], 'logout', null, null, 'Logout realizado');
        }
        
        session_destroy();
        return true;
    }
    
    /**
     * Verificar se o usuário está logado
     */
    public function isLoggedIn() {
        if (!isset($_SESSION['admin_user'])) {
            return false;
        }
        
        // Verificar se a sessão não expirou
        if (time() - $_SESSION['admin_user']['login_time'] > ADMIN_SESSION_LIFETIME) {
            $this->logout();
            return false;
        }
        
        return true;
    }
    
    /**
     * Obter dados do usuário logado
     */
    public function getUser() {
        return $this->isLoggedIn() ? $_SESSION['admin_user'] : null;
    }
    
    /**
     * Verificar se o usuário tem permissão
     */
    public function hasPermission($permission) {
        $user = $this->getUser();
        if (!$user) return false;
        
        // Admin tem todas as permissões
        if ($user['role'] === 'admin') return true;
        
        // Definir permissões por role
        $permissions = [
            'editor' => ['view_products', 'edit_products', 'view_suppliers', 'edit_suppliers', 'view_settings']
        ];
        
        return in_array($permission, $permissions[$user['role']] ?? []);
    }
    
    /**
     * Registrar atividade no log
     */
    public function logActivity($userId, $action, $tableName = null, $recordId = null, $description = null, $oldValues = null, $newValues = null) {
        try {
            $this->db->insert('admin_logs', [
                'user_id' => $userId,
                'action' => $action,
                'table_name' => $tableName,
                'record_id' => $recordId,
                'old_values' => $oldValues ? json_encode($oldValues) : null,
                'new_values' => $newValues ? json_encode($newValues) : null,
                'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
                'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null
            ]);
        } catch (Exception $e) {
            error_log("Erro ao registrar log: " . $e->getMessage());
        }
    }
    
    /**
     * Criar novo usuário administrador
     */
    public function createUser($data) {
        try {
            // Verificar se username ou email já existem
            $existing = $this->db->fetchOne(
                "SELECT id FROM admin_users WHERE username = :username OR email = :email",
                ['username' => $data['username'], 'email' => $data['email']]
            );
            
            if ($existing) {
                throw new Exception("Username ou email já existem");
            }
            
            // Hash da senha
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
            
            $userId = $this->db->insert('admin_users', $data);
            
            // Log da atividade
            if ($this->isLoggedIn()) {
                $this->logActivity(
                    $this->getUser()['id'],
                    'create_user',
                    'admin_users',
                    $userId,
                    "Usuário {$data['username']} criado"
                );
            }
            
            return $userId;
        } catch (Exception $e) {
            throw $e;
        }
    }
    
    /**
     * Atualizar senha do usuário
     */
    public function updatePassword($userId, $newPassword) {
        try {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            
            $this->db->update(
                'admin_users',
                ['password' => $hashedPassword],
                'id = :id',
                ['id' => $userId]
            );
            
            // Log da atividade
            $this->logActivity(
                $this->getUser()['id'],
                'update_password',
                'admin_users',
                $userId,
                "Senha atualizada"
            );
            
            return true;
        } catch (Exception $e) {
            throw $e;
        }
    }
    
    /**
     * Middleware para proteger páginas admin
     */
    public function requireLogin() {
        if (!$this->isLoggedIn()) {
            header('Location: login.php');
            exit;
        }
    }
    
    /**
     * Middleware para verificar permissões
     */
    public function requirePermission($permission) {
        $this->requireLogin();
        
        if (!$this->hasPermission($permission)) {
            header('Location: dashboard.php?error=permission_denied');
            exit;
        }
    }
}
?>
