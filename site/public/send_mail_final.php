<?php
/**
 * PROCESSADOR FINAL DE FORMULÁRIO DE CONTATO - SÓ BORRACHA ""
 * Envia via SoBorrachaMailer (SMTP com fallback para mail() nativo).
 * Mantém rate limiting, validação, anti-spam e resposta JSON.
 */

// Garante resposta JSON limpa: erros/warnings vão para o log, nunca para a saída.
ini_set('display_errors', '0');
error_reporting(E_ALL);
header('Content-Type: application/json; charset=utf-8');

// Localiza o SoBorrachaMailer.php de forma robusta (funciona esteja o
// includes/ ao lado do arquivo, um nível acima, ou na raiz da conta cPanel).
$mailerCandidates = [
    __DIR__ . '/includes/SoBorrachaMailer.php',       // public_html/includes/
    __DIR__ . '/../includes/SoBorrachaMailer.php',    // um nível acima (fora do público)
];
if (!empty($_SERVER['DOCUMENT_ROOT'])) {
    $mailerCandidates[] = $_SERVER['DOCUMENT_ROOT'] . '/includes/SoBorrachaMailer.php';
    $mailerCandidates[] = dirname($_SERVER['DOCUMENT_ROOT']) . '/includes/SoBorrachaMailer.php';
}

$mailerFile = null;
foreach ($mailerCandidates as $cand) {
    if (is_readable($cand)) { $mailerFile = $cand; break; }
}

if ($mailerFile === null) {
    error_log('send_mail_final: SoBorrachaMailer.php não encontrado. Procurado em: ' . implode(' | ', $mailerCandidates));
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erro de configuração do servidor. Entre em contato via WhatsApp: (67) 99918-0553'
    ]);
    exit;
}

require_once $mailerFile;

// Configurações da Só Borracha
$config = [
    'to_email'   => 'ronaldo@soborracha.com.br',
    'from_email' => 'webmaster@soborracha.com.br',
    'from_name'  => 'Só Borracha',
    'site_name'  => 'Só Borracha',
    'site_url'   => 'https://soborracha.com.br'
];

// Headers de segurança
// Headers de segurança
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');

// Verificar método
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método não permitido']);
    exit;
}

// Funções auxiliares
function sanitize_input($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

function validate_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function validate_phone($phone) {
    $phone = preg_replace('/\D/', '', $phone);
    return preg_match('/^(\d{10}|\d{11})$/', $phone);
}

function check_rate_limit($ip) {
    $file = sys_get_temp_dir() . '/rate_limit_soborracha_' . md5($ip) . '.txt';
    $now = time();
    $window = 300; // 5 minutos
    $maxRequests = 5;

    $requests = [];
    if (file_exists($file)) {
        $content = file_get_contents($file);
        $requests = $content ? explode("\n", trim($content)) : [];
    }

    $recentRequests = array_filter($requests, function($timestamp) use ($now, $window) {
        return ($now - intval($timestamp)) < $window;
    });

    $recentRequests[] = $now;
    file_put_contents($file, implode("\n", $recentRequests));

    return count($recentRequests) <= $maxRequests;
}

function is_spam($name, $email, $message) {
    $spamWords = ['viagra', 'casino', 'lottery', 'winner', 'congratulations', 'click here'];
    $content = strtolower($name . ' ' . $email . ' ' . $message);

    foreach ($spamWords as $word) {
        if (strpos($content, $word) !== false) {
            return true;
        }
    }

    return substr_count($message, 'http') > 2;
}

try {
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

    // Anti-spam: honeypot + time-trap.
    // 1) honeypot: campo "website" é invisível no formulário; humanos não
    //    preenchem, bots que completam todos os inputs sim. Se veio com
    //    conteúdo, é bot.
    // 2) time-trap: "form_time" guarda o horário de carregamento da página.
    //    Envios quase instantâneos (< $minFillSeconds) são de bots, pois um
    //    humano leva alguns segundos para preencher o formulário.
    // Em ambos os casos respondemos "sucesso" para não sinalizar ao bot que
    // foi bloqueado (mesma estratégia do filtro is_spam mais abaixo).
    $minFillSeconds = 3;
    $formTime = isset($_POST['form_time']) ? intval($_POST['form_time']) : 0;
    $elapsed = time() - $formTime;
    $honeypotFilled = !empty($_POST['website']);
    $tooFast = ($formTime <= 0) || ($elapsed < $minFillSeconds);

    if ($honeypotFilled || $tooFast) {
        $motivo = $honeypotFilled ? 'honeypot' : "time-trap (elapsed={$elapsed}s)";
        error_log("Bot detectado ($motivo) - IP: $client_ip");
        echo json_encode([
            'success' => true,
            'message' => 'Mensagem enviada com sucesso!'
        ]);
        exit;
    }

    // Capturar dados
    $formData = [
        'name'       => sanitize_input($_POST['name'] ?? ''),
        'email'      => sanitize_input($_POST['email'] ?? ''),
        'phone'      => sanitize_input($_POST['phone'] ?? ''),
        'subject'    => sanitize_input($_POST['subject'] ?? ''),
        'vehicle'    => sanitize_input($_POST['vehicle'] ?? ''),
        'message'    => sanitize_input($_POST['message'] ?? ''),
        'newsletter' => isset($_POST['newsletter'])
    ];

    // Validações
    $errors = [];

    if (empty($formData['name'])) {
        $errors[] = 'Nome é obrigatório';
    }

    if (empty($formData['email'])) {
        $errors[] = 'E-mail é obrigatório';
    } elseif (!validate_email($formData['email'])) {
        $errors[] = 'E-mail inválido';
    }

    if (!empty($formData['phone']) && !validate_phone($formData['phone'])) {
        $errors[] = 'Telefone inválido';
    }

    if (empty($formData['message'])) {
        $errors[] = 'Mensagem é obrigatória';
    }

    if (strlen($formData['message']) > 2000) {
        $errors[] = 'Mensagem muito longa (máximo 2000 caracteres)';
    }

    if (!empty($errors)) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Dados inválidos',
            'errors'  => $errors
        ]);
        exit;
    }

    // Verificar spam (responde sucesso silenciosamente para não dar feedback ao bot)
    if (is_spam($formData['name'], $formData['email'], $formData['message'])) {
        error_log("Spam detectado - IP: $client_ip, Email: {$formData['email']}");
        echo json_encode([
            'success' => true,
            'message' => 'Mensagem enviada com sucesso!'
        ]);
        exit;
    }

    // Deduplicação: evita e-mails idênticos em curto intervalo (ex.: cliques
    // duplos ou handlers de JS duplicados enviando 2-3 vezes). Se o mesmo
    // conteúdo já foi enviado nos últimos DEDUP_WINDOW segundos, não reenvia.
    $dedupWindow = 120; // segundos
    $fingerprint = md5($formData['email'] . '|' . $formData['subject'] . '|' . $formData['message']);
    $dedupFile = sys_get_temp_dir() . '/dedup_soborracha_' . $fingerprint . '.txt';
    if (file_exists($dedupFile) && (time() - filemtime($dedupFile)) < $dedupWindow) {
        error_log("Envio duplicado ignorado (dedup) - Email: {$formData['email']}, IP: $client_ip");
        echo json_encode([
            'success' => true,
            'message' => 'Mensagem enviada com sucesso! Entraremos em contato em breve.'
        ]);
        exit;
    }
    // Marca este conteúdo como já processado ANTES de enviar (janela de proteção)
    @file_put_contents($dedupFile, (string) time());

    // Enviar via SoBorrachaMailer (SMTP com fallback para mail() nativo).
    // O método sendContactForm() monta o assunto, o template HTML e o Reply-To
    // (nome/e-mail do remetente do formulário) automaticamente.
    $mailer = new SoBorrachaMailer(false);
    $emailSent = $mailer->sendContactForm($formData);

    if ($emailSent) {
        error_log("Formulário enviado com sucesso - Nome: {$formData['name']}, Email: {$formData['email']}, IP: $client_ip");

        // Salvar newsletter
        if ($formData['newsletter']) {
            $newsletterFile = sys_get_temp_dir() . '/newsletter_soborracha.txt';
            $entry = date('Y-m-d H:i:s') . " - {$formData['name']} - {$formData['email']}\n";
            file_put_contents($newsletterFile, $entry, FILE_APPEND | LOCK_EX);
        }

        echo json_encode([
            'success' => true,
            'message' => 'Mensagem enviada com sucesso! Entraremos em contato em breve.'
        ]);
    } else {
        // Envio falhou: remove a marca de dedup para permitir nova tentativa.
        if (isset($dedupFile) && file_exists($dedupFile)) {
            @unlink($dedupFile);
        }
        throw new Exception('Falha no envio do email: ' . $mailer->getLastError());
    }

} catch (Exception $e) {
    error_log("Erro no formulário de contato: " . $e->getMessage());

    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erro interno do servidor. Tente novamente ou entre em contato via WhatsApp: (67) 99918-0553'
    ]);
}
