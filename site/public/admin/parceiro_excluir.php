<?php
require_once __DIR__ . '/../includes/AdminAuth.php';
require_once __DIR__ . '/../includes/PartnerStore.php';

$auth = new AdminAuth();
$auth->requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$auth->checkCsrf($_POST['csrf'] ?? '')) {
    header('Location: parceiros.php?msg=erro');
    exit;
}

$id = $_POST['id'] ?? '';
if ($id === '') {
    header('Location: parceiros.php?msg=erro');
    exit;
}

$store = new PartnerStore();
$logoRemovido = $store->delete($id);

if ($logoRemovido) {
    $abs = __DIR__ . '/../uploads/parceiros/' . basename($logoRemovido);
    if (is_file($abs)) {
        @unlink($abs);
    }
}

header('Location: parceiros.php?msg=excluido');
exit;
