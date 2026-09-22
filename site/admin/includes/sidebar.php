<?php
require_once __DIR__ . '/url_helper.php';

$user = $auth->getUser();
?>
<aside class="sidebar">
    <div class="sidebar-header">
        <div class="sidebar-logo">
            <i class="fas fa-cog"></i>
            <span>Admin</span>
        </div>
    </div>
    
    <nav class="sidebar-nav">
        <div class="nav-section">
            <div class="nav-section-title">Principal</div>
            <ul class="nav-list">
                <li class="nav-item">
                    <a href="<?php echo adminUrl('dashboard.php'); ?>" class="nav-link <?php echo activeClass('dashboard.php'); ?>">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
            </ul>
        </div>
        
        <div class="nav-section">
            <div class="nav-section-title">Catálogo</div>
            <ul class="nav-list">
                <li class="nav-item">
                    <a href="<?php echo adminUrl('pages/products/index.php'); ?>" class="nav-link <?php echo activeClass('/products/'); ?>">
                        <i class="fas fa-box"></i>
                        <span>Produtos</span>
                        <span class="nav-badge"><?php echo $totalProducts ?? 0; ?></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo adminUrl('pages/categories/index.php'); ?>" class="nav-link <?php echo activeClass('/categories/'); ?>">
                        <i class="fas fa-tags"></i>
                        <span>Categorias</span>
                        <span class="nav-badge"><?php echo $totalCategories ?? 0; ?></span>
                    </a>
                </li>
            </ul>
        </div>
        
        <div class="nav-section">
            <div class="nav-section-title">Fornecedores</div>
            <ul class="nav-list">
                <li class="nav-item">
                    <a href="<?php echo adminUrl('pages/suppliers/index.php'); ?>" class="nav-link <?php echo activeClass('/suppliers/'); ?>">
                        <i class="fas fa-truck"></i>
                        <span>Todos os Fornecedores</span>
                        <span class="nav-badge"><?php echo $totalSuppliers ?? 0; ?></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo adminUrl('pages/suppliers/create.php'); ?>" class="nav-link">
                        <i class="fas fa-plus"></i>
                        <span>Novo Fornecedor</span>
                    </a>
                </li>
            </ul>
        </div>
        
        <div class="nav-section">
            <div class="nav-section-title">Mídia</div>
            <ul class="nav-list">
                <li class="nav-item">
                    <a href="<?php echo adminUrl('pages/media/gallery.php'); ?>" class="nav-link <?php echo activeClass('/media/'); ?>">
                        <i class="fas fa-images"></i>
                        <span>Galeria de Imagens</span>
                        <span class="nav-badge"><?php echo $totalImages ?? 34; ?></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo adminUrl('pages/settings/site.php'); ?>" class="nav-link <?php echo activeClass('/settings/site'); ?>">
                        <i class="fas fa-edit"></i>
                        <span>Editar Site</span>
                    </a>
                </li>
            </ul>
        </div>
        
        <div class="nav-section">
            <div class="nav-section-title">Sistema</div>
            <ul class="nav-list">
                <li class="nav-item">
                    <a href="<?php echo adminUrl('pages/settings/index.php'); ?>" class="nav-link <?php echo activeClass('/settings/') && !activeClass('/settings/site'); ?>">
                        <i class="fas fa-cog"></i>
                        <span>Configurações</span>
                    </a>
                </li>
                <?php if ($user['role'] === 'admin'): ?>
                <li class="nav-item">
                    <a href="<?php echo adminUrl('pages/users/index.php'); ?>" class="nav-link <?php echo activeClass('/users/'); ?>">
                        <i class="fas fa-users"></i>
                        <span>Usuários</span>
                        <span class="nav-badge"><?php echo $totalUsers ?? 1; ?></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo adminUrl('pages/logs/index.php'); ?>" class="nav-link <?php echo activeClass('/logs/'); ?>">
                        <i class="fas fa-history"></i>
                        <span>Logs do Sistema</span>
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>
    
    <div class="sidebar-footer">
        <div class="user-info">
            <div class="user-avatar">
                <i class="fas fa-user"></i>
            </div>
            <div class="user-details">
                <strong><?php echo htmlspecialchars($user['full_name']); ?></strong>
                <small><?php echo htmlspecialchars($user['role']); ?></small>
            </div>
        </div>
        
        <div class="sidebar-actions">
            <a href="<?php echo adminUrl('../index.php'); ?>" class="sidebar-action" title="Ver Site" target="_blank">
                <i class="fas fa-external-link-alt"></i>
            </a>
            <a href="<?php echo adminUrl('pages/help.php'); ?>" class="sidebar-action" title="Ajuda">
                <i class="fas fa-question-circle"></i>
            </a>
            <a href="<?php echo adminUrl('logout.php'); ?>" class="sidebar-action" title="Sair">
                <i class="fas fa-sign-out-alt"></i>
            </a>
        </div>
    </div>
</aside>
