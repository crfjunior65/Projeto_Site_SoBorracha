<?php
require_once '../../includes/Auth.php';
require_once '../../includes/Supplier.php';

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
    
    if (!isset($input['id']) || !is_numeric($input['id'])) {
        throw new Exception('ID do fornecedor inválido');
    }
    
    $supplier = new Supplier();
    $supplierId = (int)$input['id'];
    
    // Verificar se o fornecedor existe
    $existingSupplier = $supplier->getById($supplierId);
    if (!$existingSupplier) {
        throw new Exception('Fornecedor não encontrado');
    }
    
    // Excluir logo se existir
    if (!empty($existingSupplier['logo_image'])) {
        $logoPath = '../../uploads/suppliers/' . $existingSupplier['logo_image'];
        if (file_exists($logoPath)) {
            unlink($logoPath);
        }
    }
    
    // Excluir fornecedor
    $result = $supplier->delete($supplierId);
    
    if ($result) {
        echo json_encode([
            'success' => true, 
            'message' => 'Fornecedor excluído com sucesso'
        ]);
    } else {
        throw new Exception('Erro ao excluir fornecedor');
    }
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false, 
        'message' => $e->getMessage()
    ]);
}
?>
