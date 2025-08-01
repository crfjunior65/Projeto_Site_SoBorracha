<?php
// ===== PROCESSADOR DE FORMULÁRIO DE CONTATO =====

// Configurações
$to_email = "ronaldo@soborracha.com.br";
$from_email = "admin@soborracha.com.br";
$site_name = "Só Borracha Ltda";

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

// Função para validar email
function validate_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Função para validar telefone brasileiro
function validate_phone($phone) {
    $phone = preg_replace('/\D/', '', $phone);
    return preg_match('/^(\d{10}|\d{11})$/', $phone);
}

// Capturar e sanitizar dados do formulário
$name = sanitize_input($_POST['name'] ?? '');
$email = sanitize_input($_POST['email'] ?? '');
$phone = sanitize_input($_POST['phone'] ?? '');
$subject = sanitize_input($_POST['subject'] ?? '');
$vehicle = sanitize_input($_POST['vehicle'] ?? '');
$message = sanitize_input($_POST['message'] ?? '');
$newsletter = isset($_POST['newsletter']) ? 'Sim' : 'Não';

// Array para armazenar erros
$errors = [];

// Validações
if (empty($name)) {
    $errors[] = 'Nome é obrigatório';
}

if (empty($email)) {
    $errors[] = 'E-mail é obrigatório';
} elseif (!validate_email($email)) {
    $errors[] = 'E-mail inválido';
}

if (!empty($phone) && !validate_phone($phone)) {
    $errors[] = 'Telefone inválido';
}

if (empty($message)) {
    $errors[] = 'Mensagem é obrigatória';
}

// Se houver erros, retornar
if (!empty($errors)) {
    http_response_code(400);
    echo json_encode([
        'success' => false, 
        'message' => 'Dados inválidos',
        'errors' => $errors
    ]);
    exit;
}

// Preparar assunto do e-mail
$email_subject = "Contato via Site - $site_name";
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
<!DOCTYPE html>
<html lang='pt-BR'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Contato via Site</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #dc2626; color: white; padding: 20px; text-align: center; }
        .content { background: #f9f9f9; padding: 20px; }
        .field { margin-bottom: 15px; }
        .field strong { color: #dc2626; }
        .footer { background: #333; color: white; padding: 15px; text-align: center; font-size: 12px; }
    </style>
</head>
<body>
    <div class='container'>
        <div class='header'>
            <h1>$site_name</h1>
            <p>Nova mensagem via formulário de contato</p>
        </div>
        
        <div class='content'>
            <div class='field'>
                <strong>Nome:</strong> $name
            </div>
            
            <div class='field'>
                <strong>E-mail:</strong> $email
            </div>
            
            " . (!empty($phone) ? "<div class='field'><strong>Telefone:</strong> $phone</div>" : "") . "
            
            " . (!empty($subject) ? "<div class='field'><strong>Assunto:</strong> " . ($subject_map[$subject] ?? $subject) . "</div>" : "") . "
            
            " . (!empty($vehicle) ? "<div class='field'><strong>Veículo:</strong> $vehicle</div>" : "") . "
            
            <div class='field'>
                <strong>Mensagem:</strong><br>
                " . nl2br($message) . "
            </div>
            
            <div class='field'>
                <strong>Deseja receber newsletter:</strong> $newsletter
            </div>
            
            <div class='field'>
                <strong>Data/Hora:</strong> " . date('d/m/Y H:i:s') . "
            </div>
            
            <div class='field'>
                <strong>IP:</strong> " . ($_SERVER['REMOTE_ADDR'] ?? 'N/A') . "
            </div>
        </div>
        
        <div class='footer'>
            <p>Esta mensagem foi enviada através do formulário de contato do site $site_name</p>
        </div>
    </div>
</body>
</html>
";

// Headers do e-mail
$headers = [
    'MIME-Version: 1.0',
    'Content-type: text/html; charset=UTF-8',
    "From: $site_name <$from_email>",
    "Reply-To: $name <$email>",
    'X-Mailer: PHP/' . phpversion(),
    'X-Priority: 3',
    'X-MSMail-Priority: Normal'
];

// Tentar enviar o e-mail
try {
    $mail_sent = mail($to_email, $email_subject, $email_body, implode("\r\n", $headers));
    
    if ($mail_sent) {
        // Log do sucesso (opcional)
        error_log("Formulário de contato enviado com sucesso - Nome: $name, Email: $email");
        
        // Se solicitou newsletter, salvar em arquivo (implementar conforme necessário)
        if ($newsletter === 'Sim') {
            $newsletter_data = date('Y-m-d H:i:s') . " - $name - $email\n";
            file_put_contents('newsletter_subscribers.txt', $newsletter_data, FILE_APPEND | LOCK_EX);
        }
        
        // Resposta de sucesso
        echo json_encode([
            'success' => true,
            'message' => 'Mensagem enviada com sucesso! Entraremos em contato em breve.'
        ]);
        
    } else {
        throw new Exception('Falha ao enviar e-mail');
    }
    
} catch (Exception $e) {
    // Log do erro
    error_log("Erro ao enviar formulário de contato: " . $e->getMessage());
    
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erro interno do servidor. Tente novamente ou entre em contato via WhatsApp.'
    ]);
}

// Função para detectar spam (implementação básica)
function is_spam($name, $email, $message) {
    $spam_words = ['viagra', 'casino', 'lottery', 'winner', 'congratulations', 'click here'];
    $content = strtolower($name . ' ' . $email . ' ' . $message);
    
    foreach ($spam_words as $word) {
        if (strpos($content, $word) !== false) {
            return true;
        }
    }
    
    // Verificar se há muitos links
    if (substr_count($message, 'http') > 2) {
        return true;
    }
    
    return false;
}

// Rate limiting básico (implementação simples)
function check_rate_limit($ip) {
    $rate_limit_file = 'rate_limit.txt';
    $current_time = time();
    $rate_limit_duration = 300; // 5 minutos
    $max_requests = 5;
    
    if (!file_exists($rate_limit_file)) {
        file_put_contents($rate_limit_file, '');
    }
    
    $requests = file($rate_limit_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $recent_requests = [];
    
    foreach ($requests as $request) {
        list($request_ip, $request_time) = explode('|', $request);
        
        // Manter apenas requisições recentes
        if ($current_time - $request_time < $rate_limit_duration) {
            $recent_requests[] = $request;
        }
    }
    
    // Contar requisições do IP atual
    $ip_requests = 0;
    foreach ($recent_requests as $request) {
        list($request_ip, $request_time) = explode('|', $request);
        if ($request_ip === $ip) {
            $ip_requests++;
        }
    }
    
    // Adicionar requisição atual
    $recent_requests[] = $ip . '|' . $current_time;
    
    // Salvar requisições atualizadas
    file_put_contents($rate_limit_file, implode("\n", $recent_requests));
    
    return $ip_requests < $max_requests;
}

// Verificar rate limiting
$client_ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
if (!check_rate_limit($client_ip)) {
    http_response_code(429);
    echo json_encode([
        'success' => false,
        'message' => 'Muitas tentativas. Tente novamente em alguns minutos.'
    ]);
    exit;
}

// Verificar spam
if (is_spam($name, $email, $message)) {
    error_log("Possível spam detectado - IP: $client_ip, Email: $email");
    
    // Retornar sucesso para não alertar spammers, mas não enviar e-mail
    echo json_encode([
        'success' => true,
        'message' => 'Mensagem enviada com sucesso!'
    ]);
    exit;
}
?>
