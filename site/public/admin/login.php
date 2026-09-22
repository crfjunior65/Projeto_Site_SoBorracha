<?php
require_once __DIR__ . '/../includes/AdminAuth.php';

$auth = new AdminAuth();

// Já logado? vai para o painel
if ($auth->isLoggedIn()) {
    header('Location: produtos.php');
    exit;
}

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!$auth->checkCsrf($_POST['csrf'] ?? '')) {
        $erro = 'Sessão expirada. Recarregue a página e tente novamente.';
    } elseif (!$auth->isConfigured()) {
        $erro = 'Admin não configurado. Defina ADMIN_PASSWORD_HASH no arquivo .env.';
    } elseif ($auth->login($_POST['user'] ?? '', $_POST['password'] ?? '')) {
        header('Location: produtos.php');
        exit;
    } else {
        $erro = 'Usuário ou senha inválidos.';
    }
}

$csrf = $auth->csrfToken();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Admin Só Borracha</title>
    <meta name="robots" content="noindex, nofollow">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/admin.css">
</head>
<body class="login-page">
    <div class="login-box">
        <div class="logo">
            <img src="../images/LogoSB-Photoroom.png" alt="Só Borracha">
        </div>
        <h1>Painel Administrativo</h1>

        <?php if ($erro): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($erro); ?></div>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <input type="hidden" name="csrf" value="<?php echo htmlspecialchars($csrf); ?>">
            <div class="form-group">
                <label for="user">Usuário</label>
                <input type="text" id="user" name="user" required autofocus>
            </div>
            <div class="form-group">
                <label for="password">Senha</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center;">
                Entrar
            </button>
        </form>
    </div>
</body>
</html>
