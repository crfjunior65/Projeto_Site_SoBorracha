<?php 
$pageTitle = "Produtos - Borrachas Automotivas Multi Marcas | Só Borracha Ltda";
$pageDescription = "Confira nossa linha completa de borrachas automotivas: porta, parabrisa, vidro lateral e muito mais. Qualidade garantida para todas as marcas.";
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <meta name="description" content="<?php echo $pageDescription; ?>">
    <meta name="keywords" content="borrachas automotivas, borracha de porta, borracha de parabrisa, vedação automotiva, Campo Grande MS">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Styles -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/products.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <div class="page-header-content">
                <h1>Nossos Produtos</h1>
                <p>Borrachas automotivas de qualidade para todas as marcas e modelos</p>
                <nav class="breadcrumb">
                    <a href="index.php">Início</a>
                    <span>/</span>
                    <span>Produtos</span>
                </nav>
            </div>
        </div>
    </section>

    <!-- Products Filter -->
    <section class="products-filter">
        <div class="container">
            <div class="filter-content">
                <div class="filter-tabs">
                    <button class="filter-tab active" data-category="all">
                        <i class="fas fa-th"></i>
                        Todos os Produtos
                    </button>
                    <button class="filter-tab" data-category="porta">
                        <i class="fas fa-door-open"></i>
                        Borrachas de Porta
                    </button>
                    <button class="filter-tab" data-category="parabrisa">
                        <i class="fas fa-car"></i>
                        Borrachas de Parabrisa
                    </button>
                    <button class="filter-tab" data-category="vidro">
                        <i class="fas fa-window-maximize"></i>
                        Borrachas de Vidro
                    </button>
                    <button class="filter-tab" data-category="perfis">
                        <i class="fas fa-grip-lines"></i>
                        Perfis Especiais
                    </button>
                </div>
                <div class="search-box">
                    <input type="text" id="product-search" placeholder="Buscar por marca ou modelo...">
                    <i class="fas fa-search"></i>
                </div>
            </div>
        </div>
    </section>

    <!-- Products Grid -->
    <section class="products-main">
        <div class="container">
            <div class="products-grid" id="products-grid">
                
                <!-- Borrachas de Porta -->
                <div class="product-item" data-category="porta">
                    <div class="product-card">
                        <div class="product-image">
                            <img src="images/borracha-porta-universal.jpg" alt="Borracha de Porta Universal" loading="lazy">
                            <div class="product-badge">Mais Vendido</div>
                            <div class="product-overlay">
                                <button class="btn btn-small" onclick="openProductModal('porta-universal')">
                                    <i class="fas fa-eye"></i>
                                    Ver Detalhes
                                </button>
                            </div>
                        </div>
                        <div class="product-info">
                            <h3>Borracha de Porta Universal</h3>
                            <p>Vedação perfeita para portas de veículos nacionais e importados</p>
                            <div class="product-features">
                                <span class="feature-tag">Universal</span>
                                <span class="feature-tag">Resistente</span>
                                <span class="feature-tag">Fácil Instalação</span>
                            </div>
                            <div class="product-brands">
                                <small>Compatível com: Volkswagen, Fiat, Chevrolet, Ford, Toyota</small>
                            </div>
                            <div class="product-actions">
                                <button class="btn btn-primary" onclick="sendWhatsAppMessage('Gostaria de saber mais sobre Borracha de Porta Universal')">
                                    <i class="fab fa-whatsapp"></i>
                                    Consultar Preço
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Borrachas de Parabrisa -->
                <div class="product-item" data-category="parabrisa">
                    <div class="product-card">
                        <div class="product-image">
                            <img src="images/borracha-parabrisa-gol.jpg" alt="Borracha de Parabrisa Gol" loading="lazy">
                            <div class="product-overlay">
                                <button class="btn btn-small" onclick="openProductModal('parabrisa-gol')">
                                    <i class="fas fa-eye"></i>
                                    Ver Detalhes
                                </button>
                            </div>
                        </div>
                        <div class="product-info">
                            <h3>Borracha de Parabrisa Gol</h3>
                            <p>Específica para Volkswagen Gol G2, G3, G4 e G5</p>
                            <div class="product-features">
                                <span class="feature-tag">Original</span>
                                <span class="feature-tag">Garantia</span>
                                <span class="feature-tag">Instalação Inclusa</span>
                            </div>
                            <div class="product-brands">
                                <small>Compatível com: VW Gol 1995-2012</small>
                            </div>
                            <div class="product-actions">
                                <button class="btn btn-primary" onclick="sendWhatsAppMessage('Gostaria de saber mais sobre Borracha de Parabrisa Gol')">
                                    <i class="fab fa-whatsapp"></i>
                                    Consultar Preço
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Borrachas de Vidro -->
                <div class="product-item" data-category="vidro">
                    <div class="product-card">
                        <div class="product-image">
                            <img src="images/borracha-vidro-lateral.jpg" alt="Borracha de Vidro Lateral" loading="lazy">
                            <div class="product-overlay">
                                <button class="btn btn-small" onclick="openProductModal('vidro-lateral')">
                                    <i class="fas fa-eye"></i>
                                    Ver Detalhes
                                </button>
                            </div>
                        </div>
                        <div class="product-info">
                            <h3>Borracha de Vidro Lateral</h3>
                            <p>Para vidros laterais fixos e móveis de diversos modelos</p>
                            <div class="product-features">
                                <span class="feature-tag">Multi Marcas</span>
                                <span class="feature-tag">Durável</span>
                                <span class="feature-tag">Vedação Perfeita</span>
                            </div>
                            <div class="product-brands">
                                <small>Compatível com: Diversos modelos nacionais</small>
                            </div>
                            <div class="product-actions">
                                <button class="btn btn-primary" onclick="sendWhatsAppMessage('Gostaria de saber mais sobre Borracha de Vidro Lateral')">
                                    <i class="fab fa-whatsapp"></i>
                                    Consultar Preço
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Perfis Especiais -->
                <div class="product-item" data-category="perfis">
                    <div class="product-card">
                        <div class="product-image">
                            <img src="images/perfil-borracha-especial.jpg" alt="Perfil de Borracha Especial" loading="lazy">
                            <div class="product-overlay">
                                <button class="btn btn-small" onclick="openProductModal('perfil-especial')">
                                    <i class="fas fa-eye"></i>
                                    Ver Detalhes
                                </button>
                            </div>
                        </div>
                        <div class="product-info">
                            <h3>Perfis de Borracha Especiais</h3>
                            <p>Perfis customizados para aplicações específicas</p>
                            <div class="product-features">
                                <span class="feature-tag">Customizado</span>
                                <span class="feature-tag">Sob Medida</span>
                                <span class="feature-tag">Qualidade Premium</span>
                            </div>
                            <div class="product-brands">
                                <small>Desenvolvido conforme necessidade</small>
                            </div>
                            <div class="product-actions">
                                <button class="btn btn-primary" onclick="sendWhatsAppMessage('Gostaria de saber mais sobre Perfis de Borracha Especiais')">
                                    <i class="fab fa-whatsapp"></i>
                                    Consultar Preço
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mais produtos podem ser adicionados aqui -->
                
            </div>

            <!-- Load More Button -->
            <div class="load-more-section">
                <button class="btn btn-outline" id="load-more-btn">
                    <i class="fas fa-plus"></i>
                    Carregar Mais Produtos
                </button>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="services-highlight">
        <div class="container">
            <div class="services-content">
                <h2>Nossos Diferenciais</h2>
                <div class="services-grid">
                    <div class="service-item">
                        <div class="service-icon">
                            <i class="fas fa-tools"></i>
                        </div>
                        <h3>Instalação Profissional</h3>
                        <p>Equipe especializada para instalação segura e garantida</p>
                    </div>
                    <div class="service-item">
                        <div class="service-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h3>Garantia de Qualidade</h3>
                        <p>Produtos com garantia e qualidade comprovada</p>
                    </div>
                    <div class="service-item">
                        <div class="service-icon">
                            <i class="fas fa-truck-fast"></i>
                        </div>
                        <h3>Entrega Rápida</h3>
                        <p>Entrega ágil em Campo Grande e região</p>
                    </div>
                    <div class="service-item">
                        <div class="service-icon">
                            <i class="fas fa-handshake"></i>
                        </div>
                        <h3>Atendimento Personalizado</h3>
                        <p>Consultoria especializada para cada necessidade</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="products-cta">
        <div class="container">
            <div class="cta-content">
                <h2>Não encontrou o que procura?</h2>
                <p>Entre em contato conosco! Temos uma ampla variedade de borrachas automotivas para todas as marcas e modelos.</p>
                <div class="cta-buttons">
                    <a href="https://wa.me/5567999180553" class="btn btn-whatsapp" target="_blank">
                        <i class="fab fa-whatsapp"></i>
                        WhatsApp
                    </a>
                    <a href="contato.php" class="btn btn-outline">
                        <i class="fas fa-envelope"></i>
                        Formulário de Contato
                    </a>
                </div>
            </div>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>

    <!-- Product Modal -->
    <div id="product-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modal-title">Detalhes do Produto</h3>
                <button class="modal-close" onclick="closeProductModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body" id="modal-body">
                <!-- Content will be loaded dynamically -->
            </div>
        </div>
    </div>

    <script src="js/main.js"></script>
    <script src="js/products.js"></script>
</body>
</html>
