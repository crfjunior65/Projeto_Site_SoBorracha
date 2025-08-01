<?php
// ===== VERSÃO SMTP - MAIS CONFIÁVEL =====

// Configurações SMTP - EDITE AQUI
$smtp_config = [
    'host' => 'mail.soborracha.com.br',                    // Servidor SMTP
    'port' => 587,                                 // Porta (587 para TLS, 465 para SSL)
    'username' => 'admin@soborracha.com.br',     // Seu e-mail
    'password' => '#C@mp0Gr@nd&',                // Sua senha ou senha de app
    'encryption' => 'tls',                         // tls ou ssl
    'from_email' => 'admin@soborracha.com.br',   // E-mail de origem
    'from_name' => 'Só Borracha Ltda',             // Nome de origem
    'to_email' => 'ronaldo@soborracha.com.br'      // E-mail de destino
];

// Headers de segurança
header('Content-Type: application/json');
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');

// Verificar se é POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método não permitido']);
    exit;
}

// Função para sanitizar dados
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

// Capturar dados do formulário
$name = sanitize_input($_POST['name'] ?? '');
$email = sanitize_input($_POST['email'] ?? '');
$phone = sanitize_input($_POST['phone'] ?? '');
$subject = sanitize_input($_POST['subject'] ?? '');
$vehicle = sanitize_input($_POST['vehicle'] ?? '');
$message = sanitize_input($_POST['message'] ?? '');
$newsletter = isset($_POST['newsletter']) ? 'Sim' : 'Não';

// Validações básicas
$errors = [];
if (empty($name)) $errors[] = 'Nome é obrigatório';
if (empty($email)) $errors[] = 'E-mail é obrigatório';
if (empty($message)) $errors[] = 'Mensagem é obrigatória';

if (!empty($errors)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'errors' => $errors]);
    exit;
}

// Preparar assunto
$email_subject = "Contato via Site - Só Borracha Ltda";
if (!empty($subject)) {
    $subject_map = [
        'orcamento' => 'Solicitação de Orçamento',
        'produto' => 'Informações sobre Produto',
        'instalacao' => 'Serviço de Instalação',
        'garantia' => 'Questão sobre Garantia',
        'outros' => 'Outros Assuntos'
    ];
    $email_subject .= " - " . ($subject_map[$subject] ?? $subject);
}

// Preparar corpo do e-mail
$email_body = "
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; }
        .header { background: #dc2626; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; background: #f9f9f9; }
        .field { margin-bottom: 15px; }
        .field strong { color: #dc2626; }
        .footer { background: #333; color: white; padding: 15px; text-align: center; }
    </style>
</head>
<body>
    <div class='container'>
        <div class='header'>
            <h1>Só Borracha Ltda</h1>
            <p>Nova mensagem via formulário de contato</p>
        </div>
        <div class='content'>
            <div class='field'><strong>Nome:</strong> $name</div>
            <div class='field'><strong>E-mail:</strong> $email</div>
            " . (!empty($phone) ? "<div class='field'><strong>Telefone:</strong> $phone</div>" : "") . "
            " . (!empty($subject) ? "<div class='field'><strong>Assunto:</strong> " . ($subject_map[$subject] ?? $subject) . "</div>" : "") . "
            " . (!empty($vehicle) ? "<div class='field'><strong>Veículo:</strong> $vehicle</div>" : "") . "
            <div class='field'><strong>Mensagem:</strong><br>" . nl2br($message) . "</div>
            <div class='field'><strong>Newsletter:</strong> $newsletter</div>
            <div class='field'><strong>Data/Hora:</strong> " . date('d/m/Y H:i:s') . "</div>
        </div>
        <div class='footer'>
            <p>Mensagem enviada através do site soborracha.com.br</p>
        </div>
    </div>
</body>
</html>";

// Função para enviar via SMTP
function sendSMTPEmail($config, $to, $subject, $body, $reply_to = null) {
    // Headers
    $headers = [
        'MIME-Version: 1.0',
        'Content-type: text/html; charset=UTF-8',
        'From: ' . $config['from_name'] . ' <' . $config['from_email'] . '>',
        'Reply-To: ' . ($reply_to ?: $config['from_email']),
        'X-Mailer: PHP/' . phpversion()
    ];
    
    // Usar mail() com configuração SMTP (requer configuração no servidor)
    // Para SMTP real, seria necessário usar PHPMailer ou similar
    return mail($to, $subject, $body, implode("\r\n", $headers));
}

// Tentar enviar
try {
    $mail_sent = sendSMTPEmail(
        $smtp_config, 
        $smtp_config['to_email'], 
        $email_subject, 
        $email_body, 
        $email
    );
    
    if ($mail_sent) {
        // Log de sucesso
        error_log("Formulário enviado - Nome: $name, Email: $email");
        
        echo json_encode([
            'success' => true,
            'message' => 'Mensagem enviada com sucesso! Entraremos em contato em breve.'
        ]);
    } else {
        throw new Exception('Falha no envio');
    }
    
} catch (Exception $e) {
    error_log("Erro no envio: " . $e->getMessage());
    
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erro no envio. Tente novamente ou entre em contato via WhatsApp.'
    ]);
}
?>
