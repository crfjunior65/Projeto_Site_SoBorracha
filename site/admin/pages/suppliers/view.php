<?php
require_once '../../includes/Auth.php';
require_once '../../includes/Supplier.php';
require_once '../../includes/url_helper.php';

$auth = new Auth();
$auth->requireLogin();

$supplier = new Supplier();
$user = $auth->getUser();

$supplierId = $_GET['id'] ?? null;
if (!$supplierId || !is_numeric($supplierId)) {
    header('Location: index.php');
    exit;
}

$supplierData = $supplier->getById($supplierId);
if (!$supplierData) {
    header('Location: index.php');
    exit;
}

$pageTitle = 'Visualizar Fornecedor';
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
                    <h1><i class="fas fa-eye"></i> <?php echo htmlspecialchars($supplierData['name']); ?></h1>
                    <p>Detalhes do fornecedor</p>
                </div>
                <div class="content-actions">
                    <a href="edit.php?id=<?php echo $supplierId; ?>" class="btn btn-primary">
                        <i class="fas fa-edit"></i>
                        Editar
                    </a>
                    <a href="index.php" class="btn btn-outline">
                        <i class="fas fa-arrow-left"></i>
                        Voltar
                    </a>
                </div>
            </div>
            
            <div class="content-body">
                <div class="supplier-details-grid">
                    <!-- Informações Básicas -->
                    <div class="detail-card">
                        <div class="detail-header">
                            <h3><i class="fas fa-info-circle"></i> Informações Básicas</h3>
                            <span class="status-badge status-<?php echo $supplierData['status']; ?>">
                                <?php echo $supplierData['status'] === 'active' ? 'Ativo' : 'Inativo'; ?>
                            </span>
                        </div>
                        
                        <div class="detail-content">
                            <?php if ($supplierData['logo']): ?>
                                <div class="supplier-logo-display">
                                    <img src="<?php echo adminUpload('suppliers/' . $supplierData['logo']); ?>" 
                                         alt="<?php echo htmlspecialchars($supplierData['name']); ?>"
                                         class="logo-preview">
                                </div>
                            <?php endif; ?>
                            
                            <div class="detail-row">
                                <label>Nome:</label>
                                <span><?php echo htmlspecialchars($supplierData['name']); ?></span>
                            </div>
                            
                            <?php if ($supplierData['cnpj']): ?>
                            <div class="detail-row">
                                <label>CNPJ:</label>
                                <span><?php echo htmlspecialchars($supplierData['cnpj']); ?></span>
                            </div>
                            <?php endif; ?>
                            
                            <div class="detail-row">
                                <label>Categoria:</label>
                                <span class="category-badge category-<?php echo strtolower($supplierData['category']); ?>">
                                    <?php echo htmlspecialchars($supplierData['category']); ?>
                                </span>
                            </div>
                            
                            <?php if ($supplierData['description']): ?>
                            <div class="detail-row">
                                <label>Descrição:</label>
                                <p><?php echo nl2br(htmlspecialchars($supplierData['description'])); ?></p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Informações de Contato -->
                    <div class="detail-card">
                        <div class="detail-header">
                            <h3><i class="fas fa-phone"></i> Contato</h3>
                        </div>
                        
                        <div class="detail-content">
                            <?php if ($supplierData['email']): ?>
                            <div class="detail-row">
                                <label>Email:</label>
                                <a href="mailto:<?php echo htmlspecialchars($supplierData['email']); ?>">
                                    <?php echo htmlspecialchars($supplierData['email']); ?>
                                </a>
                            </div>
                            <?php endif; ?>
                            
                            <?php if ($supplierData['phone']): ?>
                            <div class="detail-row">
                                <label>Telefone:</label>
                                <a href="tel:<?php echo htmlspecialchars($supplierData['phone']); ?>">
                                    <?php echo htmlspecialchars($supplierData['phone']); ?>
                                </a>
                            </div>
                            <?php endif; ?>
                            
                            <?php if ($supplierData['website']): ?>
                            <div class="detail-row">
                                <label>Website:</label>
                                <a href="<?php echo htmlspecialchars($supplierData['website']); ?>" target="_blank">
                                    <?php echo htmlspecialchars($supplierData['website']); ?>
                                    <i class="fas fa-external-link-alt"></i>
                                </a>
                            </div>
                            <?php endif; ?>
                            
                            <?php if ($supplierData['contact_person']): ?>
                            <div class="detail-row">
                                <label>Pessoa de Contato:</label>
                                <span><?php echo htmlspecialchars($supplierData['contact_person']); ?></span>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Endereço -->
                    <?php if ($supplierData['address'] || $supplierData['city'] || $supplierData['state']): ?>
                    <div class="detail-card">
                        <div class="detail-header">
                            <h3><i class="fas fa-map-marker-alt"></i> Endereço</h3>
                        </div>
                        
                        <div class="detail-content">
                            <?php if ($supplierData['address']): ?>
                            <div class="detail-row">
                                <label>Endereço:</label>
                                <span><?php echo htmlspecialchars($supplierData['address']); ?></span>
                            </div>
                            <?php endif; ?>
                            
                            <div class="detail-row">
                                <?php if ($supplierData['city']): ?>
                                <label>Cidade:</label>
                                <span><?php echo htmlspecialchars($supplierData['city']); ?></span>
                                <?php endif; ?>
                                
                                <?php if ($supplierData['state']): ?>
                                <label>Estado:</label>
                                <span><?php echo htmlspecialchars($supplierData['state']); ?></span>
                                <?php endif; ?>
                            </div>
                            
                            <?php if ($supplierData['zip_code']): ?>
                            <div class="detail-row">
                                <label>CEP:</label>
                                <span><?php echo htmlspecialchars($supplierData['zip_code']); ?></span>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Informações do Sistema -->
                    <div class="detail-card">
                        <div class="detail-header">
                            <h3><i class="fas fa-clock"></i> Informações do Sistema</h3>
                        </div>
                        
                        <div class="detail-content">
                            <div class="detail-row">
                                <label>Cadastrado em:</label>
                                <span><?php echo date('d/m/Y H:i', strtotime($supplierData['created_at'])); ?></span>
                            </div>
                            
                            <div class="detail-row">
                                <label>Última atualização:</label>
                                <span><?php echo date('d/m/Y H:i', strtotime($supplierData['updated_at'])); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    
    <script src="<?php echo adminAsset('js/admin.js'); ?>"></script>
</body>
</html>
