<?php
require_once '../../includes/Auth.php';
require_once '../../includes/url_helper.php';

$auth = new Auth();
$auth->requireLogin();

$user = $auth->getUser();
$pageTitle = 'Produtos';
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
                    <h1><i class="fas fa-box"></i> Produtos</h1>
                    <p>Gerencie o catálogo de produtos</p>
                </div>
                <div class="content-actions">
                    <button class="btn btn-primary">
                        <i class="fas fa-plus"></i>
                        Novo Produto
                    </button>
                </div>
            </div>
            
            <div class="content-body">
                <div class="coming-soon">
                    <div class="coming-soon-icon">
                        <i class="fas fa-box"></i>
                    </div>
                    <h3>Gestão de Produtos</h3>
                    <p>Esta funcionalidade está em desenvolvimento.</p>
                    <p>Em breve você poderá gerenciar todo o catálogo de produtos da Só Borracha.</p>
                </div>
            </div>
        </main>
    </div>
    
    <script src="<?php echo adminAsset('js/admin.js'); ?>"></script>
</body>
</html>
