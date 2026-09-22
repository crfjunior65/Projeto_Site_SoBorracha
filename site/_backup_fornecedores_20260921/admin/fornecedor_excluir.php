<?php
require_once __DIR__ . '/../includes/AdminAuth.php';
require_once __DIR__ . '/../includes/SupplierStore.php';

$auth = new AdminAuth();
$auth->requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$auth->checkCsrf($_POST['csrf'] ?? '')) {
    header('Location: fornecedores.php?msg=erro');
    exit;
}

$id = $_POST['id'] ?? '';
if ($id === '') {
    header('Location: fornecedores.php?msg=erro');
    exit;
}

$store = new SupplierStore();
$logoRemovido = $store->delete($id);

// Remove o logo do disco, se existir
if ($logoRemovido) {
    $abs = __DIR__ . '/../uploads/fornecedores/' . basename($logoRemovido);
    if (is_file($abs)) {
        @unlink($abs);
    }
}

header('Location: fornecedores.php?msg=excluido');
exit;
