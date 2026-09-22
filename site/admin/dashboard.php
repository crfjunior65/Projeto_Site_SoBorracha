<?php
require_once 'includes/Auth.php';
require_once 'includes/Supplier.php';

$auth = new Auth();
$auth->requireLogin();

$user = $auth->getUser();
$supplier = new Supplier();

// Obter estatísticas
$supplierStats = $supplier->getStats();

// Obter estatísticas gerais
$db = getDB();
$totalProducts = $db->fetchOne("SELECT COUNT(*) as count FROM products")['count'] ?? 0;
$activeProducts = $db->fetchOne("SELECT COUNT(*) as count FROM products WHERE status = 'active'")['count'] ?? 0;
$recentProducts = $db->fetchAll("SELECT name, created_at FROM products ORDER BY created_at DESC LIMIT 5");
$recentSuppliers = $db->fetchAll("SELECT name, company_name, created_at FROM suppliers ORDER BY created_at DESC LIMIT 5");
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Administração | Só Borracha Ltda</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <?php include 'includes/sidebar.php'; ?>
    
    <main class="main-content">
        <div class="content-header">
            <div class="content-title">
                <h1>Dashboard</h1>
                <p>Bem-vindo de volta, <?php echo htmlspecialchars($user['full_name']); ?>!</p>
            </div>
            <div class="content-actions">
                <span class="last-login">
                    <i class="fas fa-clock"></i>
                    Último acesso: <?php echo date('d/m/Y H:i'); ?>
                </span>
            </div>
        </div>
        
        <!-- Cards de Estatísticas -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon products">
                    <i class="fas fa-box"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number"><?php echo $totalProducts; ?></div>
                    <div class="stat-label">Total de Produtos</div>
                    <div class="stat-change positive">
                        <i class="fas fa-arrow-up"></i>
                        <?php echo $activeProducts; ?> ativos
                    </div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon suppliers">
                    <i class="fas fa-truck"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number"><?php echo $supplierStats['total']; ?></div>
                    <div class="stat-label">Total de Fornecedores</div>
                    <div class="stat-change positive">
                        <i class="fas fa-arrow-up"></i>
                        <?php echo $supplierStats['active']; ?> ativos
                    </div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon featured">
                    <i class="fas fa-star"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number"><?php echo $supplierStats['featured']; ?></div>
                    <div class="stat-label">Fornecedores em Destaque</div>
                    <div class="stat-change">
                        <i class="fas fa-eye"></i>
                        Visíveis no site
                    </div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon settings">
                    <i class="fas fa-cog"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">100%</div>
                    <div class="stat-label">Sistema Online</div>
                    <div class="stat-change positive">
                        <i class="fas fa-check"></i>
                        Funcionando
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Gráficos e Informações -->
        <div class="dashboard-grid">
            <!-- Ações Rápidas -->
            <div class="dashboard-card">
                <div class="card-header">
                    <h3>Ações Rápidas</h3>
                    <i class="fas fa-bolt"></i>
                </div>
                <div class="card-content">
                    <div class="quick-actions">
                        <a href="pages/products/create.php" class="quick-action">
                            <div class="action-icon">
                                <i class="fas fa-plus"></i>
                            </div>
                            <div class="action-content">
                                <h4>Novo Produto</h4>
                                <p>Adicionar produto ao catálogo</p>
                            </div>
                        </a>
                        
                        <a href="pages/suppliers/create.php" class="quick-action">
                            <div class="action-icon">
                                <i class="fas fa-truck-loading"></i>
                            </div>
                            <div class="action-content">
                                <h4>Novo Fornecedor</h4>
                                <p>Cadastrar novo fornecedor</p>
                            </div>
                        </a>
                        
                        <a href="pages/settings/site.php" class="quick-action">
                            <div class="action-icon">
                                <i class="fas fa-edit"></i>
                            </div>
                            <div class="action-content">
                                <h4>Editar Site</h4>
                                <p>Alterar conteúdo do site</p>
                            </div>
                        </a>
                        
                        <a href="pages/media/gallery.php" class="quick-action">
                            <div class="action-icon">
                                <i class="fas fa-images"></i>
                            </div>
                            <div class="action-content">
                                <h4>Galeria</h4>
                                <p>Gerenciar imagens</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Produtos Recentes -->
            <div class="dashboard-card">
                <div class="card-header">
                    <h3>Produtos Recentes</h3>
                    <a href="pages/products/" class="card-action">Ver todos</a>
                </div>
                <div class="card-content">
                    <?php if (empty($recentProducts)): ?>
                        <div class="empty-state">
                            <i class="fas fa-box-open"></i>
                            <p>Nenhum produto cadastrado ainda</p>
                            <a href="pages/products/create.php" class="btn btn-primary btn-small">
                                Adicionar Primeiro Produto
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="recent-list">
                            <?php foreach ($recentProducts as $product): ?>
                                <div class="recent-item">
                                    <div class="item-icon">
                                        <i class="fas fa-box"></i>
                                    </div>
                                    <div class="item-content">
                                        <h4><?php echo htmlspecialchars($product['name']); ?></h4>
                                        <p><?php echo date('d/m/Y H:i', strtotime($product['created_at'])); ?></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Fornecedores Recentes -->
            <div class="dashboard-card">
                <div class="card-header">
                    <h3>Fornecedores Recentes</h3>
                    <a href="pages/suppliers/" class="card-action">Ver todos</a>
                </div>
                <div class="card-content">
                    <?php if (empty($recentSuppliers)): ?>
                        <div class="empty-state">
                            <i class="fas fa-truck"></i>
                            <p>Nenhum fornecedor cadastrado ainda</p>
                            <a href="pages/suppliers/create.php" class="btn btn-primary btn-small">
                                Adicionar Primeiro Fornecedor
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="recent-list">
                            <?php foreach ($recentSuppliers as $supplier): ?>
                                <div class="recent-item">
                                    <div class="item-icon">
                                        <i class="fas fa-truck"></i>
                                    </div>
                                    <div class="item-content">
                                        <h4><?php echo htmlspecialchars($supplier['name']); ?></h4>
                                        <p><?php echo htmlspecialchars($supplier['company_name']); ?></p>
                                        <small><?php echo date('d/m/Y', strtotime($supplier['created_at'])); ?></small>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Fornecedores por Estado -->
            <div class="dashboard-card">
                <div class="card-header">
                    <h3>Fornecedores por Estado</h3>
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <div class="card-content">
                    <?php if (empty($supplierStats['by_state'])): ?>
                        <div class="empty-state">
                            <i class="fas fa-map"></i>
                            <p>Nenhum dado disponível</p>
                        </div>
                    <?php else: ?>
                        <div class="state-stats">
                            <?php foreach ($supplierStats['by_state'] as $state): ?>
                                <div class="state-item">
                                    <div class="state-name"><?php echo htmlspecialchars($state['state']); ?></div>
                                    <div class="state-count"><?php echo $state['count']; ?></div>
                                    <div class="state-bar">
                                        <div class="state-progress" style="width: <?php echo ($state['count'] / $supplierStats['total']) * 100; ?>%"></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>
    
    <script src="assets/js/admin.js"></script>
</body>
</html>
