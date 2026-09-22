<?php
require_once '../../includes/Auth.php';
require_once '../../includes/url_helper.php';

$auth = new Auth();
$auth->requireLogin();
$auth->requirePermission('admin'); // Só admin pode ver logs

$user = $auth->getUser();
$pageTitle = 'Logs do Sistema';
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
                    <h1><i class="fas fa-history"></i> Logs do Sistema</h1>
                    <p>Monitore atividades e eventos do sistema</p>
                </div>
            </div>
            
            <div class="content-body">
                <div class="coming-soon">
                    <div class="coming-soon-icon">
                        <i class="fas fa-history"></i>
                    </div>
                    <h3>Logs do Sistema</h3>
                    <p>Esta funcionalidade está em desenvolvimento.</p>
                    <p>Em breve você poderá visualizar logs de atividades, erros e eventos do sistema.</p>
                </div>
            </div>
        </main>
    </div>
    
    <script src="<?php echo adminAsset('js/admin.js'); ?>"></script>
</body>
</html>
