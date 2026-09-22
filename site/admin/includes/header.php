<?php require_once __DIR__ . '/url_helper.php'; ?>
<header class="admin-header">
    <div class="header-left">
        <button class="sidebar-toggle" id="sidebarToggle">
            <i class="fas fa-bars"></i>
        </button>
        <div class="header-logo">
            <img src="<?php echo publicAsset('LogoSB-Photoroom.png'); ?>" alt="Só Borracha" class="logo-img">
            <span class="logo-text">Administração</span>
        </div>
    </div>
    
    <div class="header-center">
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Buscar produtos, fornecedores..." id="globalSearch">
        </div>
    </div>
    
    <div class="header-right">
        <div class="header-actions">
            <a href="<?php echo adminUrl('../index.php'); ?>" class="header-action" title="Ver Site" target="_blank">
                <i class="fas fa-external-link-alt"></i>
            </a>
            
            <div class="notifications-dropdown">
                <button class="header-action" id="notificationsToggle">
                    <i class="fas fa-bell"></i>
                    <span class="notification-badge">3</span>
                </button>
                <div class="dropdown-menu" id="notificationsMenu">
                    <div class="dropdown-header">
                        <h4>Notificações</h4>
                    </div>
                    <div class="notification-list">
                        <div class="notification-item">
                            <i class="fas fa-info-circle text-blue"></i>
                            <div class="notification-content">
                                <p>Sistema atualizado com sucesso</p>
                                <small>2 horas atrás</small>
                            </div>
                        </div>
                        <div class="notification-item">
                            <i class="fas fa-exclamation-triangle text-yellow"></i>
                            <div class="notification-content">
                                <p>Backup automático realizado</p>
                                <small>1 dia atrás</small>
                            </div>
                        </div>
                        <div class="notification-item">
                            <i class="fas fa-check-circle text-green"></i>
                            <div class="notification-content">
                                <p>Novo fornecedor cadastrado</p>
                                <small>2 dias atrás</small>
                            </div>
                        </div>
                    </div>
                    <div class="notification-footer">
                        <a href="<?php echo adminUrl('pages/notifications.php'); ?>">Ver todas</a>
                    </div>
                </div>
            </div>
            
            <div class="user-dropdown">
                <button class="header-action user-menu-toggle" id="userMenuToggle">
                    <img src="<?php echo adminAsset('images/default-avatar.png'); ?>" alt="<?php echo htmlspecialchars($user['full_name']); ?>" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="user-avatar-fallback">
                        <i class="fas fa-user"></i>
                    </div>
                    <span class="user-name"><?php echo htmlspecialchars($user['full_name']); ?></span>
                    <i class="fas fa-chevron-down"></i>
                </button>
                
                <div class="dropdown-menu" id="userMenu">
                    <div class="dropdown-header">
                        <img src="<?php echo adminAsset('images/default-avatar.png'); ?>" alt="<?php echo htmlspecialchars($user['full_name']); ?>" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="user-avatar-fallback">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="user-info">
                            <strong><?php echo htmlspecialchars($user['full_name']); ?></strong>
                            <small><?php echo htmlspecialchars($user['email']); ?></small>
                        </div>
                    </div>
                    
                    <div class="dropdown-divider"></div>
                    
                    <a href="<?php echo adminUrl('pages/profile.php'); ?>" class="dropdown-item">
                        <i class="fas fa-user"></i>
                        Meu Perfil
                    </a>
                    <a href="<?php echo adminUrl('pages/settings/index.php'); ?>" class="dropdown-item">
                        <i class="fas fa-cog"></i>
                        Configurações
                    </a>
                    
                    <div class="dropdown-divider"></div>
                    
                    <a href="<?php echo adminUrl('logout.php'); ?>" class="dropdown-item text-danger">
                        <i class="fas fa-sign-out-alt"></i>
                        Sair
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>
