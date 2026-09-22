<?php
require_once 'includes/Auth.php';

$auth = new Auth();
$auth->logout();

// Redirecionar para login com mensagem de sucesso
header('Location: login.php?message=logout_success');
exit;
?>
