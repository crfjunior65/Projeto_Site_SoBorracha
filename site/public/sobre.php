<?php 
$pageTitle = "Sobre Nós - Só Borracha Ltda | 25 Anos de Experiência em Campo Grande - MS";
$pageDescription = "Conheça a história da Só Borracha Ltda. Há mais de 25 anos oferecendo borrachas automotivas de qualidade em Campo Grande - MS. Tradição e confiança.";
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <meta name="description" content="<?php echo $pageDescription; ?>">
    <meta name="keywords" content="sobre só borracha, história empresa, borrachas automotivas Campo Grande, tradição qualidade">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Styles -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/about.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <div class="page-header-content">
                <h1>Nossa História</h1>
                <p>Mais de 25 anos de tradição em borrachas automotivas</p>
                <nav class="breadcrumb">
                    <a href="index.php">Início</a>
                    <span>/</span>
                    <span>Sobre</span>
                </nav>
            </div>
        </div>
    </section>

    <!-- Company Story -->
    <section class="company-story">
        <div class="container">
            <div class="story-content">
                <div class="story-text">
                    <div class="story-intro">
                        <h2>Só Borracha Ltda</h2>
                        <p class="lead">
                            Fundada em 1998 em Campo Grande - MS, a Só Borracha Ltda nasceu do sonho de 
                            oferecer produtos de qualidade superior no segmento de borrachas automotivas.
                        </p>
                    </div>
                    
                    <div class="story-timeline">
                        <div class="timeline-item">
                            <div class="timeline-year">1998</div>
                            <div class="timeline-content">
                                <h3>Fundação</h3>
                                <p>Início das atividades com foco em borrachas para veículos nacionais, 
                                atendendo pequenas oficinas da região.</p>
                            </div>
                        </div>
                        
                        <div class="timeline-item">
                            <div class="timeline-year">2005</div>
                            <div class="timeline-content">
                                <h3>Expansão</h3>
                                <p>Ampliação do catálogo para incluir veículos importados e início 
                                do atendimento ao varejo.</p>
                            </div>
                        </div>
                        
                        <div class="timeline-item">
                            <div class="timeline-year">2012</div>
                            <div class="timeline-content">
                                <h3>Modernização</h3>
                                <p>Investimento em tecnologia e treinamento da equipe, consolidando 
                                nossa posição no mercado regional.</p>
                            </div>
                        </div>
                        
                        <div class="timeline-item">
                            <div class="timeline-year">2024</div>
                            <div class="timeline-content">
                                <h3>Presente</h3>
                                <p>Referência em borrachas automotivas em MS, com mais de 1000 clientes 
                                satisfeitos e presença digital.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="story-image">
                    <img src="images/loja-fachada.jpg" alt="Fachada da Só Borracha Ltda" loading="lazy">
                    <div class="image-caption">
                        <p>Nossa loja na Av. Calogeras, 1300 - Campo Grande/MS</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Mission Vision Values -->
    <section class="mission-vision-values">
        <div class="container">
            <div class="mvv-grid">
                <div class="mvv-item">
                    <div class="mvv-icon">
                        <i class="fas fa-bullseye"></i>
                    </div>
                    <h3>Missão</h3>
                    <p>
                        Fornecer borrachas automotivas de alta qualidade, garantindo segurança 
                        e satisfação aos nossos clientes através de produtos confiáveis e 
                        atendimento especializado.
                    </p>
                </div>
                
                <div class="mvv-item">
                    <div class="mvv-icon">
                        <i class="fas fa-eye"></i>
                    </div>
                    <h3>Visão</h3>
                    <p>
                        Ser reconhecida como a principal referência em borrachas automotivas 
                        no Centro-Oeste, expandindo nossa atuação e mantendo a excelência 
                        no atendimento.
                    </p>
                </div>
                
                <div class="mvv-item">
                    <div class="mvv-icon">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h3>Valores</h3>
                    <ul>
                        <li>Qualidade em primeiro lugar</li>
                        <li>Atendimento personalizado</li>
                        <li>Transparência nas relações</li>
                        <li>Compromisso com prazos</li>
                        <li>Inovação constante</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Statistics -->
    <section class="company-stats">
        <div class="container">
            <div class="stats-content">
                <h2>Números que Falam por Si</h2>
                <div class="stats-grid">
                    <div class="stat-item">
                        <div class="stat-number" data-count="25">0</div>
                        <div class="stat-label">Anos de Experiência</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number" data-count="1000">0</div>
                        <div class="stat-label">Clientes Satisfeitos</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number" data-count="50">0</div>
                        <div class="stat-label">Marcas Atendidas</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number" data-count="5000">0</div>
                        <div class="stat-label">Produtos Instalados</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Team -->
    <section class="team-section">
        <div class="container">
            <div class="section-header">
                <h2>Nossa Equipe</h2>
                <p>Profissionais especializados e comprometidos com a qualidade</p>
            </div>
            
            <div class="team-grid">
                <div class="team-member">
                    <div class="member-photo">
                        <img src="images/team-ronaldo.jpg" alt="Ronaldo - Proprietário" loading="lazy">
                    </div>
                    <div class="member-info">
                        <h3>Ronaldo</h3>
                        <p class="member-role">Proprietário e Fundador</p>
                        <p class="member-description">
                            Com mais de 25 anos de experiência no setor automotivo, 
                            Ronaldo é especialista em borrachas para todas as marcas.
                        </p>
                        <div class="member-contact">
                            <a href="mailto:ronaldo@soborracha.com.br">
                                <i class="fas fa-envelope"></i>
                            </a>
                            <a href="https://wa.me/5567999180553">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="team-member">
                    <div class="member-photo">
                        <img src="images/team-tecnico.jpg" alt="Equipe Técnica" loading="lazy">
                    </div>
                    <div class="member-info">
                        <h3>Equipe Técnica</h3>
                        <p class="member-role">Instalação Especializada</p>
                        <p class="member-description">
                            Nossa equipe técnica é treinada para instalação profissional, 
                            garantindo vedação perfeita e durabilidade.
                        </p>
                    </div>
                </div>
                
                <div class="team-member">
                    <div class="member-photo">
                        <img src="images/team-atendimento.jpg" alt="Atendimento" loading="lazy">
                    </div>
                    <div class="member-info">
                        <h3>Atendimento</h3>
                        <p class="member-role">Consultoria Especializada</p>
                        <p class="member-description">
                            Equipe preparada para orientar na escolha do produto ideal 
                            para cada veículo e necessidade.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Location -->
    <section class="location-section">
        <div class="container">
            <div class="location-content">
                <div class="location-info">
                    <h2>Venha nos Conhecer</h2>
                    <p class="location-intro">
                        Estamos localizados em ponto estratégico de Campo Grande, 
                        com fácil acesso e estacionamento disponível.
                    </p>
                    
                    <div class="location-details">
                        <div class="detail-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <div>
                                <strong>Endereço</strong>
                                <span>Av. Calogeras, 1300<br>Campo Grande - MS</span>
                            </div>
                        </div>
                        
                        <div class="detail-item">
                            <i class="fas fa-clock"></i>
                            <div>
                                <strong>Horário de Funcionamento</strong>
                                <span>Segunda a Sexta: 7:30 às 17:30<br>Sábado: 8:00 às 12:00</span>
                            </div>
                        </div>
                        
                        <div class="detail-item">
                            <i class="fas fa-phone"></i>
                            <div>
                                <strong>Contato</strong>
                                <span>(67) 99918-0553<br>ronaldo@soborracha.com.br</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="location-actions">
                        <a href="https://wa.me/5567999180553" class="btn btn-whatsapp" target="_blank">
                            <i class="fab fa-whatsapp"></i>
                            Falar Conosco
                        </a>
                        <a href="contato.php" class="btn btn-outline">
                            <i class="fas fa-envelope"></i>
                            Formulário
                        </a>
                    </div>
                </div>
                
                <div class="location-map">
                    <div class="map-placeholder">
                        <i class="fas fa-map"></i>
                        <p>Mapa Interativo</p>
                        <small>Av. Calogeras, 1300 - Campo Grande/MS</small>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>

    <script src="js/main.js"></script>
    <script src="js/about.js"></script>
</body>
</html>
