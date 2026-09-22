<?php
require_once 'includes/Auth.php';
require_once 'includes/Supplier.php';

$auth = new Auth();
$auth->requireLogin();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método não permitido']);
    exit;
}

try {
    $query = $_GET['q'] ?? '';
    
    if (strlen($query) < 2) {
        echo json_encode(['success' => true, 'results' => []]);
        exit;
    }
    
    $results = [];
    
    // Buscar fornecedores
    $supplier = new Supplier();
    $suppliers = $supplier->search($query);
    
    foreach ($suppliers as $sup) {
        $results[] = [
            'type' => 'supplier',
            'title' => $sup['name'],
            'subtitle' => $sup['company_name'],
            'url' => 'pages/suppliers/view.php?id=' . $sup['id'],
            'icon' => 'fas fa-truck'
        ];
    }
    
    // Buscar outras entidades (produtos, etc.) - implementar conforme necessário
    
    // Limitar resultados
    $results = array_slice($results, 0, 10);
    
    echo json_encode([
        'success' => true, 
        'results' => $results,
        'total' => count($results)
    ]);
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false, 
        'message' => $e->getMessage()
    ]);
}
?>
