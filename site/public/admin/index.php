<?php
require_once __DIR__ . '/../includes/AdminAuth.php';
$auth = new AdminAuth();
header('Location: ' . ($auth->isLoggedIn() ? 'produtos.php' : 'login.php'));
exit;
