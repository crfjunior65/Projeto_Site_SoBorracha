<?php 
$pageTitle = "Contato - Só Borracha Ltda | Fale Conosco - Campo Grande MS";
$pageDescription = "Entre em contato com a Só Borracha Ltda. Telefone: (67) 99918-0553. Endereço: Av. Calogeras, 1300, Campo Grande - MS. Atendimento especializado.";
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <meta name="description" content="<?php echo $pageDescription; ?>">
    <meta name="keywords" content="contato só borracha, telefone, endereço Campo Grande, borrachas automotivas contato">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Styles -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/contact.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <div class="page-header-content">
                <h1>Fale Conosco</h1>
                <p>Estamos prontos para atender você com qualidade e agilidade</p>
                <nav class="breadcrumb">
                    <a href="index.php">Início</a>
                    <span>/</span>
                    <span>Contato</span>
                </nav>
            </div>
        </div>
    </section>

    <!-- Contact Methods -->
    <section class="contact-methods">
        <div class="container">
            <div class="methods-grid">
                <div class="method-card whatsapp-card">
                    <div class="method-icon">
                        <i class="fab fa-whatsapp"></i>
                    </div>
                    <h3>WhatsApp</h3>
                    <p>Atendimento rápido e direto</p>
                    <div class="method-info">
                        <strong>(67) 99918-0553</strong>
                        <span>Seg-Sex: 7:30 às 17:30</span>
                    </div>
                    <a href="https://wa.me/5567999180553" class="btn btn-whatsapp" target="_blank">
                        <i class="fab fa-whatsapp"></i>
                        Conversar Agora
                    </a>
                </div>
                
                <div class="method-card phone-card">
                    <div class="method-icon">
                        <i class="fas fa-phone"></i>
                    </div>
                    <h3>Telefone</h3>
                    <p>Ligue diretamente para nós</p>
                    <div class="method-info">
                        <strong>(67) 99918-0553</strong>
                        <span>Horário comercial</span>
                    </div>
                    <a href="tel:+5567999180553" class="btn btn-primary">
                        <i class="fas fa-phone"></i>
                        Ligar Agora
                    </a>
                </div>
                
                <div class="method-card email-card">
                    <div class="method-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <h3>E-mail</h3>
                    <p>Envie sua mensagem</p>
                    <div class="method-info">
                        <strong>ronaldo@soborracha.com.br</strong>
                        <span>Resposta em até 24h</span>
                    </div>
                    <a href="mailto:ronaldo@soborracha.com.br" class="btn btn-outline">
                        <i class="fas fa-envelope"></i>
                        Enviar E-mail
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Form & Info -->
    <section class="contact-main">
        <div class="container">
            <div class="contact-content">
                <div class="contact-form-section">
                    <h2>Envie sua Mensagem</h2>
                    <p>Preencha o formulário abaixo e entraremos em contato o mais breve possível.</p>
                    
                    <form id="contact-form" class="contact-form" method="POST" action="send_mail.php">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="name">Nome Completo *</label>
                                <input type="text" id="name" name="name" required>
                            </div>
                            <div class="form-group">
                                <label for="email">E-mail *</label>
                                <input type="email" id="email" name="email" required>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="phone">Telefone</label>
                                <input type="tel" id="phone" name="phone" placeholder="(67) 99999-9999">
                            </div>
                            <div class="form-group">
                                <label for="subject">Assunto</label>
                                <select id="subject" name="subject">
                                    <option value="">Selecione um assunto</option>
                                    <option value="orcamento">Solicitar Orçamento</option>
                                    <option value="produto">Informações sobre Produto</option>
                                    <option value="instalacao">Serviço de Instalação</option>
                                    <option value="garantia">Garantia</option>
                                    <option value="outros">Outros</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="vehicle">Veículo (Marca/Modelo/Ano)</label>
                            <input type="text" id="vehicle" name="vehicle" placeholder="Ex: Volkswagen Gol 2010">
                        </div>
                        
                        <div class="form-group">
                            <label for="message">Mensagem *</label>
                            <textarea id="message" name="message" rows="5" required placeholder="Descreva sua necessidade ou dúvida..."></textarea>
                        </div>
                        
                        <div class="form-group checkbox-group">
                            <label class="checkbox-label">
                                <input type="checkbox" id="newsletter" name="newsletter">
                                <span class="checkmark"></span>
                                Quero receber novidades e promoções por e-mail
                            </label>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-large">
                            <i class="fas fa-paper-plane"></i>
                            Enviar Mensagem
                        </button>
                    </form>
                </div>
                
                <div class="contact-info-section">
                    <div class="info-card">
                        <h3>Informações de Contato</h3>
                        
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="info-content">
                                <strong>Endereço</strong>
                                <span>Av. Calogeras, 1300<br>Campo Grande - MS<br>CEP: 79004-390</span>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="info-content">
                                <strong>Horário de Funcionamento</strong>
                                <span>Segunda a Sexta: 7:30 às 17:30<br>Sábado: 8:00 às 12:00<br>Domingo: Fechado</span>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div class="info-content">
                                <strong>Telefone / WhatsApp</strong>
                                <span>(67) 99918-0553</span>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="info-content">
                                <strong>E-mail</strong>
                                <span>ronaldo@soborracha.com.br</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="services-card">
                        <h3>Nossos Serviços</h3>
                        <ul class="services-list">
                            <li>
                                <i class="fas fa-check"></i>
                                <span>Borrachas de Porta</span>
                            </li>
                            <li>
                                <i class="fas fa-check"></i>
                                <span>Borrachas de Parabrisa</span>
                            </li>
                            <li>
                                <i class="fas fa-check"></i>
                                <span>Borrachas de Vidro</span>
                            </li>
                            <li>
                                <i class="fas fa-check"></i>
                                <span>Perfis Especiais</span>
                            </li>
                            <li>
                                <i class="fas fa-check"></i>
                                <span>Instalação Profissional</span>
                            </li>
                            <li>
                                <i class="fas fa-check"></i>
                                <span>Atendimento Varejo e Atacado</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Location Map -->
    <section class="location-map-section">
        <div class="container">
            <div class="map-content">
                <div class="map-info">
                    <h2>Nossa Localização</h2>
                    <p>Estamos localizados em ponto estratégico de Campo Grande, com fácil acesso e estacionamento disponível.</p>
                    
                    <div class="location-features">
                        <div class="feature-item">
                            <i class="fas fa-parking"></i>
                            <span>Estacionamento Gratuito</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-bus"></i>
                            <span>Próximo ao Transporte Público</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-road"></i>
                            <span>Fácil Acesso</span>
                        </div>
                    </div>
                    
                    <div class="map-actions">
                        <a href="https://maps.google.com/?q=Av.+Calogeras,+1300,+Campo+Grande+-+MS" class="btn btn-primary" target="_blank">
                            <i class="fas fa-map-marker-alt"></i>
                            Ver no Google Maps
                        </a>
                        <a href="https://wa.me/5567999180553?text=Olá! Gostaria de saber como chegar até a loja." class="btn btn-outline" target="_blank">
                            <i class="fas fa-route"></i>
                            Como Chegar
                        </a>
                    </div>
                </div>
                
                <div class="map-container">
                    <div class="map-placeholder">
                        <i class="fas fa-map"></i>
                        <h3>Mapa Interativo</h3>
                        <p>Av. Calogeras, 1300<br>Campo Grande - MS</p>
                        <small>Clique em "Ver no Google Maps" para visualizar</small>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="faq-section">
        <div class="container">
            <div class="section-header">
                <h2>Perguntas Frequentes</h2>
                <p>Tire suas dúvidas mais comuns</p>
            </div>
            
            <div class="faq-grid">
                <div class="faq-item">
                    <div class="faq-question">
                        <h3>Vocês fazem instalação?</h3>
                        <i class="fas fa-plus"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Sim! Temos equipe especializada para instalação profissional de todas as borrachas automotivas. A instalação é feita com garantia de vedação perfeita.</p>
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">
                        <h3>Atendem todas as marcas de veículos?</h3>
                        <i class="fas fa-plus"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Sim, atendemos todas as marcas nacionais e importadas. Temos um amplo estoque de borrachas para mais de 50 marcas diferentes.</p>
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">
                        <h3>Qual o prazo de entrega?</h3>
                        <i class="fas fa-plus"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Para produtos em estoque, a entrega é imediata. Para produtos sob encomenda, o prazo varia de 3 a 7 dias úteis.</p>
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">
                        <h3>Oferecem garantia?</h3>
                        <i class="fas fa-plus"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Todos os nossos produtos têm garantia de fábrica. A instalação também possui garantia de 6 meses contra defeitos de mão de obra.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>

    <script src="js/main.js"></script>
    <script src="js/contact.js"></script>
</body>
</html>
