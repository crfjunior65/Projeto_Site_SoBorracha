<?php
/**
 * Configurações do Banco de Dados para Docker
 * Site: Só Borracha Ltda
 */

// Configurações do banco de dados para Docker
define('DB_HOST', $_ENV['DB_HOST'] ?? 'db');
define('DB_NAME', $_ENV['DB_NAME'] ?? 'soborracha_db');
define('DB_USER', $_ENV['DB_USER'] ?? 'soborracha_user');
define('DB_PASS', $_ENV['DB_PASS'] ?? 'soborracha_pass');
define('DB_CHARSET', 'utf8mb4');

// Configurações de segurança
define('ADMIN_SESSION_NAME', 'soborracha_admin');
define('ADMIN_SESSION_LIFETIME', 3600); // 1 hora
define('UPLOAD_MAX_SIZE', 10 * 1024 * 1024); // 10MB para Docker
define('ALLOWED_IMAGE_TYPES', ['jpg', 'jpeg', 'png', 'webp', 'gif']);

// Caminhos do sistema para Docker
define('ADMIN_PATH', '/admin');
define('UPLOADS_PATH', '/admin/uploads');
define('SITE_URL', 'http://localhost:8080');
define('ADMIN_URL', SITE_URL . ADMIN_PATH);

// Configurações de desenvolvimento
define('DEBUG_MODE', true);
define('SHOW_ERRORS', true);

// Configurar exibição de erros para desenvolvimento
if (DEBUG_MODE) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
}

// Classe de conexão com o banco de dados
class Database {
    private static $instance = null;
    private $connection;
    
    private function __construct() {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
            ];
            
            $this->connection = new PDO($dsn, DB_USER, DB_PASS, $options);
            
            // Log de conexão bem-sucedida em modo debug
            if (DEBUG_MODE) {
                error_log("Database connection successful to " . DB_HOST . "/" . DB_NAME);
            }
            
        } catch (PDOException $e) {
            $error_msg = "Erro na conexão com o banco de dados: " . $e->getMessage();
            error_log($error_msg);
            
            if (DEBUG_MODE) {
                die($error_msg);
            } else {
                die("Erro interno do servidor. Tente novamente mais tarde.");
            }
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->connection;
    }
    
    // Método para executar queries
    public function query($sql, $params = []) {
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);
            
            // Log de queries em modo debug
            if (DEBUG_MODE) {
                error_log("Query executed: " . $sql . " | Params: " . json_encode($params));
            }
            
            return $stmt;
        } catch (PDOException $e) {
            error_log("Database Error: " . $e->getMessage() . " | Query: " . $sql);
            throw $e;
        }
    }
    
    // Método para buscar um registro
    public function fetchOne($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt->fetch();
    }
    
    // Método para buscar múltiplos registros
    public function fetchAll($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt->fetchAll();
    }
    
    // Método para inserir dados
    public function insert($table, $data) {
        $columns = implode(',', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        
        $sql = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";
        $this->query($sql, $data);
        
        return $this->connection->lastInsertId();
    }
    
    // Método para atualizar dados
    public function update($table, $data, $where, $whereParams = []) {
        $setClause = [];
        foreach (array_keys($data) as $key) {
            $setClause[] = "{$key} = :{$key}";
        }
        $setClause = implode(', ', $setClause);
        
        $sql = "UPDATE {$table} SET {$setClause} WHERE {$where}";
        $params = array_merge($data, $whereParams);
        
        return $this->query($sql, $params);
    }
    
    // Método para deletar dados
    public function delete($table, $where, $params = []) {
        $sql = "DELETE FROM {$table} WHERE {$where}";
        return $this->query($sql, $params);
    }
    
    // Método para testar conexão
    public function testConnection() {
        try {
            $result = $this->fetchOne("SELECT 1 as test");
            return $result['test'] === 1;
        } catch (Exception $e) {
            return false;
        }
    }
}

// Função helper para obter a instância do banco
function getDB() {
    return Database::getInstance();
}

// Função para verificar se estamos em ambiente Docker
function isDockerEnvironment() {
    return file_exists('/.dockerenv') || (isset($_ENV['DOCKER_ENV']) && $_ENV['DOCKER_ENV'] === 'true');
}

// Função para obter URL base
function getBaseUrl() {
    if (isDockerEnvironment()) {
        return SITE_URL;
    }
    
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    return $protocol . '://' . $host;
}
?>
