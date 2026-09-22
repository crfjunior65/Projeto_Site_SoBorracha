<?php
require_once '../../includes/Auth.php';

$auth = new Auth();
$auth->requireLogin();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método não permitido']);
    exit;
}

try {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($input['filename']) || !isset($input['directory'])) {
        throw new Exception('Parâmetros inválidos');
    }
    
    $filename = $input['filename'];
    $directory = $input['directory'];
    
    // Validar diretório
    $allowedDirectories = [
        'site' => '../../uploads/site/',
        'products' => '../../uploads/products/',
        'suppliers' => '../../uploads/suppliers/',
        'public' => '../../../public/images/'
    ];
    
    if (!isset($allowedDirectories[$directory])) {
        throw new Exception('Diretório inválido');
    }
    
    $uploadDir = $allowedDirectories[$directory];
    $filePath = $uploadDir . $filename;
    
    // Validar se o arquivo existe e está no diretório correto
    if (!file_exists($filePath)) {
        throw new Exception('Arquivo não encontrado');
    }
    
    // Validar extensão do arquivo
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
    $fileExtension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    
    if (!in_array($fileExtension, $allowedExtensions)) {
        throw new Exception('Tipo de arquivo não permitido');
    }
    
    // Excluir arquivo
    if (unlink($filePath)) {
        echo json_encode([
            'success' => true, 
            'message' => 'Imagem excluída com sucesso'
        ]);
    } else {
        throw new Exception('Erro ao excluir arquivo');
    }
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false, 
        'message' => $e->getMessage()
    ]);
}
?>
