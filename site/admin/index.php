<?php
/**
 * Redirecionamento para página de login
 * Só Borracha Ltda - Administração
 */

// Redirecionar para login se não estiver logado
require_once 'includes/Auth.php';

$auth = new Auth();

if ($auth->isLoggedIn()) {
    // Se já estiver logado, redirecionar para dashboard
    header('Location: dashboard.php');
} else {
    // Se não estiver logado, redirecionar para login
    header('Location: login.php');
}

exit;
?>
