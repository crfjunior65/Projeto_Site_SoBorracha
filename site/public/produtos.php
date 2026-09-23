<?php 
require_once __DIR__ . '/includes/ProductStore.php';
$store = new ProductStore();
$produtos = $store->all(true); // apenas ativos
$vitrine = $store->getSettings();

$pageTitle = "Produtos - Borrachas Automotivas Multi Marcas | Só Borracha";
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
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Styles -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/products.css">
    <link rel="stylesheet" href="css/refinamentos.css">
    <link rel="stylesheet" href="css/home-moderna.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <div class="page-header-content">
                <h1><?php echo htmlspecialchars($vitrine['vitrine_titulo'] ?: 'Nossos Produtos'); ?></h1>
                <p><?php echo htmlspecialchars($vitrine['vitrine_subtitulo'] ?: 'Borrachas automotivas de qualidade para todas as marcas e modelos'); ?></p>
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
                <?php if (empty($produtos)): ?>
                    <p style="grid-column:1/-1;text-align:center;color:#64748b;">Nenhum produto cadastrado no momento. Fale conosco no WhatsApp para consultar nosso catálogo completo.</p>
                <?php else: foreach ($produtos as $p): ?>
                <div class="product-item" data-category="<?php echo htmlspecialchars($p['categoria']); ?>">
                    <div class="product-card">
                        <div class="product-image">
                            <img src="<?php echo htmlspecialchars($p['imagem'] ?: 'images/BorrachaAutomotiva.png'); ?>" alt="<?php echo htmlspecialchars($p['nome']); ?>" loading="lazy">
                            <?php if (!empty($p['badge'])): ?>
                                <div class="product-badge"><?php echo htmlspecialchars($p['badge']); ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="product-info">
                            <h3><?php echo htmlspecialchars($p['nome']); ?></h3>
                            <p><?php echo htmlspecialchars($p['descricao']); ?></p>
                            <?php if (!empty($p['features'])): ?>
                            <div class="product-features">
                                <?php foreach ($p['features'] as $feat): ?>
                                    <span class="feature-tag"><?php echo htmlspecialchars($feat); ?></span>
                                <?php endforeach; ?>
                            </div>
                            <?php endif; ?>
                            <?php if (!empty($p['compatibilidade'])): ?>
                            <div class="product-brands">
                                <small>Compatível com: <?php echo htmlspecialchars($p['compatibilidade']); ?></small>
                            </div>
                            <?php endif; ?>
                            <div class="product-actions">
                                <button class="btn btn-primary" onclick="sendWhatsAppMessage('Gostaria de saber mais sobre <?php echo htmlspecialchars(addslashes($p['nome'])); ?>')">
                                    <i class="fab fa-whatsapp"></i>
                                    Consultar Preço
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; endif; ?>
            </div>

            <!-- Aviso: milhares de opções / multimarcas -->
            <div class="products-more-info" style="margin-top:40px;text-align:center;background:#fff5f5;border:1px solid #fecaca;border-radius:12px;padding:32px 24px;">
                <h3 style="margin:0 0 10px;color:#dc2626;"><i class="fas fa-warehouse"></i> Temos milhares de opções em estoque</h3>
                <p style="margin:0 auto 20px;max-width:640px;color:#374151;">
                    <?php echo htmlspecialchars($vitrine['aviso_multimarcas']); ?>
                </p>
                <a href="https://wa.me/5567999180553?text=Ol%C3%A1!%20Gostaria%20de%20consultar%20uma%20borracha%20para%20o%20meu%20ve%C3%ADculo." class="btn btn-whatsapp" target="_blank" rel="noopener">
                    <i class="fab fa-whatsapp"></i>
                    Consultar minha peça no WhatsApp
                </a>
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
