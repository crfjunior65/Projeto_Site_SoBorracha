<?php
require_once __DIR__ . '/includes/ProductStore.php';
$store = new ProductStore();
$vitrine = $store->getSettings();
// Destaques para a home: prioriza os marcados como destaque e completa com os
// demais ativos até no máximo 6, para a grade não ficar incompleta.
$ativos = $store->all(true);
$comDestaque = array_values(array_filter($ativos, function ($p) { return !empty($p['destaque']); }));
$semDestaque = array_values(array_filter($ativos, function ($p) { return empty($p['destaque']); }));
$destaques = array_slice(array_merge($comDestaque, $semDestaque), 0, 6);

// Mapa de categorias -> rótulo amigável (para o badge)
$catLabels = ProductStore::$categorias;

$pageTitle = "Só Borracha - Borrachas Automotivas Multi Marcas | Campo Grande - MS";
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
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Styles -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/components.css">
    <link rel="stylesheet" href="css/refinamentos.css">
    <link rel="stylesheet" href="css/home-moderna.css">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="images/favicon.png">
</head>
<body class="hm">
    <?php include 'includes/header.php'; ?>

    <!-- Hero -->
    <section class="hm-hero">
        <span class="blob b1"></span><span class="blob b2"></span>
        <div class="container">
            <div class="hm-hero-grid">
                <div>
                    <span class="badge-top"><i class="fas fa-award"></i> Há mais de 25 anos em Campo Grande - MS</span>
                    <h1>Borrachas Automotivas <span class="hl">para todas as marcas</span></h1>
                    <p class="sub">
                        Varejo e atacado com qualidade garantida. Milhares de opções,
                        atendimento especializado e instalação profissional.
                    </p>
                    <div class="highlights">
                        <span class="chip"><i class="fas fa-check-circle"></i> Qualidade garantida</span>
                        <span class="chip"><i class="fas fa-truck"></i> Entrega rápida</span>
                        <span class="chip"><i class="fas fa-tools"></i> Instalação especializada</span>
                    </div>
                </div>
                <div class="hm-hero-visual">
                    <div class="hm-hero-img">
                        <img src="images/hero-borrachas.jpg" alt="Borrachas Automotivas de Qualidade" loading="lazy">
                    </div>
                    <div class="hm-float-card">
                        <div class="ic"><i class="fas fa-star"></i></div>
                        <div>
                            <div class="t">Multimarcas</div>
                            <div class="s">Nacionais e importados</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="hm-hero-wave">
            <svg viewBox="0 0 1440 70" preserveAspectRatio="none"><path fill="#0f172a" d="M0,32 C360,80 1080,-16 1440,32 L1440,70 L0,70 Z"></path></svg>
        </div>
    </section>

    <!-- Faixa de credibilidade -->
    <div class="hm-cred">
        <div class="container">
            <div><div class="num" data-count="25">25+</div><div class="lbl">Anos de experiência</div></div>
            <div><div class="num" data-count="50">50+</div><div class="lbl">Marcas atendidas</div></div>
            <div><div class="num" data-count="1000">1000+</div><div class="lbl">Clientes satisfeitos</div></div>
            <div><div class="num">A+V</div><div class="lbl">Atacado e varejo</div></div>
        </div>
    </div>

    <!-- Serviços -->
    <section>
        <div class="container">
            <div class="sec-head reveal">
                <span class="kicker">O que fazemos</span>
                <h2>Nossos Serviços</h2>
                <p>Soluções completas em borrachas automotivas</p>
            </div>
            <div class="hm-svc-grid">
                <div class="hm-svc-card reveal">
                    <div class="hm-svc-ico"><i class="fas fa-car"></i></div>
                    <h3>Varejo</h3>
                    <p>Atendimento personalizado para proprietários de veículos com as melhores marcas do mercado.</p>
                    <a href="produtos.php">Ver produtos →</a>
                </div>
                <div class="hm-svc-card reveal">
                    <div class="hm-svc-ico"><i class="fas fa-industry"></i></div>
                    <h3>Atacado</h3>
                    <p>Fornecimento para oficinas e revendedores com preços especiais e condições diferenciadas.</p>
                    <a href="parceiros.php">Seja parceiro →</a>
                </div>
                <div class="hm-svc-card reveal">
                    <div class="hm-svc-ico"><i class="fas fa-wrench"></i></div>
                    <h3>Instalação</h3>
                    <p>Equipe especializada para instalação profissional de borrachas automotivas.</p>
                    <a href="contato.php">Fale conosco →</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Produtos (6 destaques dinâmicos do JSON) -->
    <section class="bg-alt">
        <div class="container">
            <div class="sec-head reveal">
                <span class="kicker">Catálogo</span>
                <h2>Principais Produtos</h2>
                <p>Alguns exemplos do nosso catálogo — trabalhamos com milhares de itens</p>
            </div>
            <div class="hm-prod-grid">
                <?php foreach ($destaques as $p): ?>
                <div class="hm-prod-card reveal">
                    <div class="hm-prod-img">
                        <?php $catKey = $p['categoria'] ?? 'outros'; $catNome = $catLabels[$catKey] ?? 'Produto'; ?>
                        <span class="hm-prod-badge"><?php echo htmlspecialchars(!empty($p['badge']) ? $p['badge'] : $catNome); ?></span>
                        <img src="<?php echo htmlspecialchars($p['imagem'] ?: 'images/BorrachaAutomotiva.png'); ?>" alt="<?php echo htmlspecialchars($p['nome']); ?>" loading="lazy">
                    </div>
                    <div class="hm-prod-body">
                        <h3><?php echo htmlspecialchars($p['nome']); ?></h3>
                        <p><?php echo htmlspecialchars($p['descricao']); ?></p>
                        <?php if (!empty($p['features'])): ?>
                        <div class="hm-tags">
                            <?php foreach (array_slice($p['features'], 0, 2) as $feat): ?>
                                <span class="hm-tag"><?php echo htmlspecialchars($feat); ?></span>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="hm-prod-cta reveal">
                <p>Estes são apenas alguns exemplos. Temos <strong>milhares de opções</strong> para todas as marcas e modelos.</p>
                <a href="produtos.php" class="btn btn-primary">Ver Todos os Produtos</a>
            </div>
        </div>
    </section>

    <!-- Sobre + Números -->
    <section>
        <div class="container">
            <div class="hm-about-grid">
                <div class="reveal">
                    <span class="kicker">Quem somos</span>
                    <h2>Sobre a Só Borracha</h2>
                    <p>
                        Há mais de 25 anos no mercado, somos referência em borrachas automotivas
                        em Campo Grande - MS, oferecendo produtos de qualidade e atendimento especializado.
                    </p>
                    <div class="hm-stats">
                        <div class="hm-stat"><div class="n">25+</div><div class="l">Anos de experiência</div></div>
                        <div class="hm-stat"><div class="n">1000+</div><div class="l">Clientes satisfeitos</div></div>
                        <div class="hm-stat"><div class="n">50+</div><div class="l">Marcas atendidas</div></div>
                    </div>
                    <a href="sobre.php" class="btn btn-outline">Saiba Mais</a>
                </div>
                <div class="hm-about-visual reveal">
                    <div class="hm-about-img">
                        <img src="images/loja-fachada.jpg" alt="Fachada da Só Borracha" loading="lazy">
                    </div>
                    <span class="hm-seal"><i class="fas fa-store"></i> Loja física em Campo Grande</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Diferenciais -->
    <section class="bg-alt">
        <div class="container">
            <div class="sec-head reveal">
                <span class="kicker">Diferenciais</span>
                <h2>Por que a Só Borracha</h2>
                <p>O que faz a diferença no nosso atendimento</p>
            </div>
            <div class="hm-diff-grid">
                <div class="hm-diff reveal"><div class="ic"><i class="fas fa-layer-group"></i></div><h4>Multimarcas</h4><p>Peças para todas as montadoras, nacionais e importadas.</p></div>
                <div class="hm-diff reveal"><div class="ic"><i class="fas fa-boxes-stacked"></i></div><h4>Atacado e Varejo</h4><p>Condições especiais para oficinas e revendedores.</p></div>
                <div class="hm-diff reveal"><div class="ic"><i class="fas fa-screwdriver-wrench"></i></div><h4>Instalação</h4><p>Mão de obra profissional com garantia.</p></div>
                <div class="hm-diff reveal"><div class="ic"><i class="fab fa-whatsapp"></i></div><h4>Atendimento Rápido</h4><p>Consulte sua peça direto pelo WhatsApp.</p></div>
            </div>
        </div>
    </section>

    <!-- CTA Final -->
    <section class="hm-cta">
        <span class="blob"></span>
        <div class="container">
            <h2>Precisa de Borrachas Automotivas?</h2>
            <p>Entre em contato e receba atendimento especializado</p>
            <div class="hm-cta-info">
                <span class="it"><i class="fas fa-map-marker-alt"></i> Av. Calogeras, 1300 - Campo Grande/MS</span>
                <span class="it"><i class="fas fa-phone"></i> (67) 99918-0553</span>
                <span class="it"><i class="fas fa-clock"></i> Seg-Sex: 7:30 às 17:30 · Sáb: 8:00 às 12:00</span>
            </div>
            <div class="hm-cta-btns">
                <a href="contato.php" class="btn btn-light"><i class="fas fa-envelope"></i> Formulário de Contato</a>
            </div>
            <div class="social">
                <a href="https://www.instagram.com/" target="_blank" rel="noopener" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                <a href="https://www.facebook.com/" target="_blank" rel="noopener" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                <a href="https://wa.me/5567999180553" target="_blank" rel="noopener" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a>
            </div>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
