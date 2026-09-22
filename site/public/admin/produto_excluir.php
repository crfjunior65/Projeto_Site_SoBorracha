<?php
require_once __DIR__ . '/../includes/AdminAuth.php';
require_once __DIR__ . '/../includes/ProductStore.php';

$auth = new AdminAuth();
$auth->requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$auth->checkCsrf($_POST['csrf'] ?? '')) {
    header('Location: produtos.php?msg=erro');
    exit;
}

$id = $_POST['id'] ?? '';
if ($id === '') {
    header('Location: produtos.php?msg=erro');
    exit;
}

$store = new ProductStore();
$imagemRemovida = $store->delete($id);

// Remove a imagem do produto se estiver em images/produtos/ (não apaga imagens gerais do site)
if ($imagemRemovida && strpos($imagemRemovida, 'images/produtos/') === 0) {
    $abs = __DIR__ . '/../' . $imagemRemovida;
    if (is_file($abs)) {
        @unlink($abs);
    }
}

header('Location: produtos.php?msg=excluido');
exit;
