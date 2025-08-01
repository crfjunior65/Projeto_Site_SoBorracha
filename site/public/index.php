<?php 
$pageTitle = "Só Borracha Ltda - Borrachas Automotivas Multi Marcas | Campo Grande - MS";
$pageDescription = "Especialistas em borrachas automotivas para todas as marcas. Varejo e atacado em Campo Grande - MS. Qualidade garantida há mais de 25 anos.";
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <meta name="description" content="<?php echo $pageDescription; ?>">
    <meta name="keywords" content="borrachas automotivas, borracha de porta, borracha de parabrisa, Campo Grande MS, auto peças">
    
    <!-- Open Graph -->
    <meta property="og:title" content="<?php echo $pageTitle; ?>">
    <meta property="og:description" content="<?php echo $pageDescription; ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://soborracha.com.br">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Styles -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/components.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="images/favicon.png">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-background">
            <div class="hero-overlay"></div>
        </div>
        <div class="container">
            <div class="hero-content">
                <div class="hero-text">
                    <h1 class="hero-title">
                        <span class="highlight">Borrachas Automotivas</span>
                        <span>Multi Marcas</span>
                    </h1>
                    <p class="hero-subtitle">
                        Especialistas há mais de 25 anos em borrachas automotivas para todas as marcas. 
                        Atendemos varejo e atacado com qualidade garantida.
                    </p>
                    <div class="hero-features">
                        <div class="feature-item">
                            <i class="fas fa-check-circle"></i>
                            <span>Qualidade Garantida</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-truck"></i>
                            <span>Entrega Rápida</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-tools"></i>
                            <span>Instalação Especializada</span>
                        </div>
                    </div>
                    <div class="hero-buttons">
                        <a href="produtos.php" class="btn btn-primary">
                            <i class="fas fa-search"></i>
                            Ver Produtos
                        </a>
                        <a href="https://wa.me/5567999180553" class="btn btn-outline" target="_blank">
                            <i class="fab fa-whatsapp"></i>
                            Fale Conosco
                        </a>
                    </div>
                </div>
                <div class="hero-image">
                    <img src="images/hero-borrachas.jpg" alt="Borrachas Automotivas de Qualidade" loading="lazy">
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="services-section">
        <div class="container">
            <div class="section-header">
                <h2>Nossos Serviços</h2>
                <p>Soluções completas em borrachas automotivas</p>
            </div>
            <div class="services-grid">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-car"></i>
                    </div>
                    <h3>Varejo</h3>
                    <p>Atendimento personalizado para proprietários de veículos com as melhores marcas do mercado.</p>
                </div>
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-industry"></i>
                    </div>
                    <h3>Atacado</h3>
                    <p>Fornecimento para oficinas e revendedores com preços especiais e condições diferenciadas.</p>
                </div>
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-wrench"></i>
                    </div>
                    <h3>Instalação</h3>
                    <p>Equipe especializada para instalação profissional de borrachas automotivas.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Products Section -->
    <section class="products-section">
        <div class="container">
            <div class="section-header">
                <h2>Principais Produtos</h2>
                <p>Borrachas automotivas para todas as marcas e modelos</p>
            </div>
            <div class="products-grid">
                <div class="product-card">
                    <div class="product-image">
                        <img src="images/borracha-porta.jpg" alt="Borracha de Porta Automotiva" loading="lazy">
                        <div class="product-overlay">
                            <a href="produtos.php" class="btn btn-small">Ver Detalhes</a>
                        </div>
                    </div>
                    <div class="product-info">
                        <h3>Borrachas de Porta</h3>
                        <p>Vedação perfeita para todas as marcas de veículos</p>
                        <div class="product-features">
                            <span class="feature-tag">Multi Marcas</span>
                            <span class="feature-tag">Qualidade Premium</span>
                        </div>
                    </div>
                </div>
                
                <div class="product-card">
                    <div class="product-image">
                        <img src="images/borracha-parabrisa.jpg" alt="Borracha de Parabrisa" loading="lazy">
                        <div class="product-overlay">
                            <a href="produtos.php" class="btn btn-small">Ver Detalhes</a>
                        </div>
                    </div>
                    <div class="product-info">
                        <h3>Borrachas de Parabrisa</h3>
                        <p>Instalação segura e vedação garantida</p>
                        <div class="product-features">
                            <span class="feature-tag">Instalação Inclusa</span>
                            <span class="feature-tag">Garantia</span>
                        </div>
                    </div>
                </div>
                
                <div class="product-card">
                    <div class="product-image">
                        <img src="images/borracha-vidro.jpg" alt="Borracha de Vidro Lateral" loading="lazy">
                        <div class="product-overlay">
                            <a href="produtos.php" class="btn btn-small">Ver Detalhes</a>
                        </div>
                    </div>
                    <div class="product-info">
                        <h3>Borrachas de Vidro</h3>
                        <p>Vedação lateral e traseira para todos os modelos</p>
                        <div class="product-features">
                            <span class="feature-tag">Resistente</span>
                            <span class="feature-tag">Durável</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="products-cta">
                <a href="produtos.php" class="btn btn-primary">Ver Todos os Produtos</a>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="about-section">
        <div class="container">
            <div class="about-content">
                <div class="about-text">
                    <h2>Sobre a Só Borracha Ltda</h2>
                    <p class="about-intro">
                        Há mais de 25 anos no mercado, somos referência em borrachas automotivas 
                        em Campo Grande - MS, oferecendo produtos de qualidade e atendimento especializado.
                    </p>
                    <div class="about-stats">
                        <div class="stat-item">
                            <div class="stat-number">25+</div>
                            <div class="stat-label">Anos de Experiência</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">1000+</div>
                            <div class="stat-label">Clientes Satisfeitos</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">50+</div>
                            <div class="stat-label">Marcas Atendidas</div>
                        </div>
                    </div>
                    <a href="sobre.php" class="btn btn-outline">Saiba Mais</a>
                </div>
                <div class="about-image">
                    <img src="images/loja-fachada.jpg" alt="Fachada da Só Borracha Ltda" loading="lazy">
                </div>
            </div>
        </div>
    </section>

    <!-- Contact CTA Section -->
    <section class="contact-cta-section">
        <div class="container">
            <div class="cta-content">
                <div class="cta-text">
                    <h2>Precisa de Borrachas Automotivas?</h2>
                    <p>Entre em contato conosco e receba atendimento especializado</p>
                </div>
                <div class="cta-info">
                    <div class="contact-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <div>
                            <strong>Endereço</strong>
                            <span>Av. Calogeras, 1300, Campo Grande - MS</span>
                        </div>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-phone"></i>
                        <div>
                            <strong>Telefone</strong>
                            <span>(67) 99918-0553</span>
                        </div>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-clock"></i>
                        <div>
                            <strong>Horário</strong>
                            <span>Seg-Sex: 7:30 às 17:30</span>
                        </div>
                    </div>
                </div>
                <div class="cta-buttons">
                    <a href="https://wa.me/5567999180553" class="btn btn-whatsapp" target="_blank">
                        <i class="fab fa-whatsapp"></i>
                        WhatsApp
                    </a>
                    <a href="contato.php" class="btn btn-outline">
                        <i class="fas fa-envelope"></i>
                        Formulário
                    </a>
                </div>
            </div>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
