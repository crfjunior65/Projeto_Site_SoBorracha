<?php
require_once '../../includes/Auth.php';
require_once '../../includes/url_helper.php';

$auth = new Auth();
$auth->requireLogin();

$user = $auth->getUser();
$pageTitle = 'Configurações';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?> - Administração | Só Borracha Ltda</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="<?php echo adminAsset('css/admin.css'); ?>">
</head>
<body>
    <?php include '../../includes/header.php'; ?>
    
    <div class="admin-container">
        <?php include '../../includes/sidebar.php'; ?>
        
        <main class="main-content">
            <div class="content-header">
                <div class="content-title">
                    <h1><i class="fas fa-cog"></i> Configurações</h1>
                    <p>Configure as opções do sistema</p>
                </div>
            </div>
            
            <div class="content-body">
                <div class="settings-grid">
                    <div class="setting-card">
                        <div class="setting-icon">
                            <i class="fas fa-globe"></i>
                        </div>
                        <h3>Configurações do Site</h3>
                        <p>Informações gerais, contato e redes sociais</p>
                        <a href="site.php" class="btn btn-outline">
                            <i class="fas fa-edit"></i>
                            Configurar
                        </a>
                    </div>
                    
                    <div class="setting-card">
                        <div class="setting-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <h3>Email</h3>
                        <p>Configurações de SMTP e templates</p>
                        <button class="btn btn-outline" disabled>
                            <i class="fas fa-clock"></i>
                            Em breve
                        </button>
                    </div>
                    
                    <div class="setting-card">
                        <div class="setting-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h3>Segurança</h3>
                        <p>Configurações de segurança e backup</p>
                        <button class="btn btn-outline" disabled>
                            <i class="fas fa-clock"></i>
                            Em breve
                        </button>
                    </div>
                    
                    <div class="setting-card">
                        <div class="setting-icon">
                            <i class="fas fa-paint-brush"></i>
                        </div>
                        <h3>Aparência</h3>
                        <p>Cores, logos e personalização visual</p>
                        <button class="btn btn-outline" disabled>
                            <i class="fas fa-clock"></i>
                            Em breve
                        </button>
                    </div>
                </div>
            </div>
        </main>
    </div>
    
    <script src="<?php echo adminAsset('js/admin.js'); ?>"></script>
</body>
</html>
