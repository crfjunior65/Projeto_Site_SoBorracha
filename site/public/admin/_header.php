<?php
// Espera que $auth (AdminAuth) já exista e a sessão esteja validada.
// Também espera $pageTitle definido pela página.
if (!isset($pageTitle)) { $pageTitle = 'Admin'; }
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?> - Admin Só Borracha</title>
    <meta name="robots" content="noindex, nofollow">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/admin.css">
</head>
<body>
    <header class="admin-topbar">
        <div class="brand">
            <img src="../images/LogoSB-Photoroom.png" alt="Só Borracha">
            <span>Admin</span>
        </div>
        <div class="topbar-actions">
            <a href="produtos.php"><i class="fas fa-box"></i> Produtos</a>
            <a href="parceiros.php"><i class="fas fa-handshake"></i> Parceiros</a>
            <a href="imagens.php"><i class="fas fa-image"></i> Imagens do site</a>
            <a href="../index.php" target="_blank"><i class="fas fa-external-link-alt"></i> Ver site</a>
            <span class="user"><i class="fas fa-user"></i> <?php echo htmlspecialchars($auth->currentUser() ?? ''); ?></span>
            <a href="logout.php" class="btn btn-secondary btn-sm"><i class="fas fa-sign-out-alt"></i> Sair</a>
        </div>
    </header>
    <div class="admin-wrap">
