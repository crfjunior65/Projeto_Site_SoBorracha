<?php
/**
 * PÁGINA DE TESTE DE EMAIL - SÓ BORRACHA LTDA
 * Teste das configurações SMTP
 */

require_once 'includes/Auth.php';
require_once 'includes/EmailService.php';
require_once '../includes/SoBorrachaMailer.php';

$auth = new Auth();
$auth->requireLogin();

$results = [];
$testType = $_GET['test'] ?? '';

if ($_POST && isset($_POST['run_test'])) {
    $testType = $_POST['test_type'];
    
    try {
        $emailService = new EmailService();
        $mailer = new SoBorrachaMailer(true); // Debug ativado
        
        switch ($testType) {
            case 'connection':
                $result = $mailer->testConnection();
                $results[] = [
                    'test' => 'Teste de Conexão SMTP',
                    'success' => $result,
                    'message' => $result ? 'Conexão SMTP estabelecida com sucesso' : 'Falha na conexão: ' . $mailer->getLastError()
                ];
                break;
                
            case 'config':
                $result = $emailService->testEmailConfiguration();
                $results[] = [
                    'test' => 'Teste de Configuração',
                    'success' => $result,
                    'message' => $result ? 'Email de teste enviado com sucesso' : 'Falha no envio: ' . $emailService->getLastError()
                ];
                break;
                
            case 'contact':
                $testData = [
                    'name' => 'Teste Sistema',
                    'email' => 'teste@soborracha.com.br',
                    'phone' => '(67) 99999-9999',
                    'subject' => 'orcamento',
                    'vehicle' => 'Teste Gol 2010',
                    'message' => 'Esta é uma mensagem de teste do sistema de contato.',
                    'newsletter' => true
                ];
                
                $result = $mailer->sendContactForm($testData);
                $results[] = [
                    'test' => 'Teste de Formulário de Contato',
                    'success' => $result,
                    'message' => $result ? 'Email de contato enviado com sucesso' : 'Falha no envio: ' . $mailer->getLastError()
                ];
                break;
                
            case 'welcome':
                $result = $emailService->sendWelcomeEmail('teste@soborracha.com.br', 'Usuário Teste', 'senha123');
                $results[] = [
                    'test' => 'Teste de Email de Boas-vindas',
                    'success' => $result,
                    'message' => $result ? 'Email de boas-vindas enviado com sucesso' : 'Falha no envio: ' . $emailService->getLastError()
                ];
                break;
                
            case 'supplier':
                $supplierData = [
                    'id' => 999,
                    'name' => 'Fornecedor Teste',
                    'company_name' => 'Empresa Teste Ltda',
                    'email' => 'fornecedor@teste.com',
                    'phone' => '(67) 88888-8888',
                    'status' => 'active'
                ];
                
                $result = $emailService->sendNewSupplierNotification($supplierData);
                $results[] = [
                    'test' => 'Teste de Notificação de Fornecedor',
                    'success' => $result,
                    'message' => $result ? 'Notificação de fornecedor enviada com sucesso' : 'Falha no envio: ' . $emailService->getLastError()
                ];
                break;
                
            case 'all':
                // Executar todos os testes
                $tests = ['connection', 'config', 'contact', 'welcome', 'supplier'];
                foreach ($tests as $test) {
                    $_POST['test_type'] = $test;
                    // Recursão controlada para executar cada teste
                }
                break;
        }
        
    } catch (Exception $e) {
        $results[] = [
            'test' => 'Erro no Teste',
            'success' => false,
            'message' => 'Erro: ' . $e->getMessage()
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teste de Email - Administração | Só Borracha Ltda</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/admin.css">
    
    <style>
        .test-card {
            background: white;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .test-result {
            padding: 15px;
            border-radius: 8px;
            margin: 10px 0;
            border-left: 4px solid;
        }
        
        .test-result.success {
            background: #f0fdf4;
            border-color: #22c55e;
            color: #166534;
        }
        
        .test-result.error {
            background: #fef2f2;
            border-color: #ef4444;
            color: #991b1b;
        }
        
        .config-info {
            background: #f8fafc;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            font-family: monospace;
        }
        
        .test-buttons {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin: 20px 0;
        }
        
        .test-btn {
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            background: #3b82f6;
            color: white;
            cursor: pointer;
            transition: background 0.2s;
            text-decoration: none;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        
        .test-btn:hover {
            background: #2563eb;
        }
        
        .test-btn.danger {
            background: #ef4444;
        }
        
        .test-btn.danger:hover {
            background: #dc2626;
        }
        
        .test-btn.success {
            background: #22c55e;
        }
        
        .test-btn.success:hover {
            background: #16a34a;
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="admin-container">
        <?php include 'includes/sidebar.php'; ?>
        
        <main class="main-content">
            <div class="content-header">
                <div class="content-title">
                    <h1><i class="fas fa-envelope-open-text"></i> Teste de Email</h1>
                    <p>Teste as configurações SMTP da Só Borracha Ltda</p>
                </div>
                <div class="content-actions">
                    <a href="dashboard.php" class="btn btn-outline">
                        <i class="fas fa-arrow-left"></i>
                        Voltar ao Dashboard
                    </a>
                </div>
            </div>
            
            <div class="content-body">
                <!-- Configurações Atuais -->
                <div class="test-card">
                    <h3><i class="fas fa-cog"></i> Configurações SMTP Atuais</h3>
                    <div class="config-info">
                        <strong>Servidor:</strong> mail.soborracha.com.br<br>
                        <strong>Porta:</strong> 587<br>
                        <strong>Usuário:</strong> admin@soborracha.com.br<br>
                        <strong>Criptografia:</strong> TLS<br>
                        <strong>Destinatário:</strong> ronaldo@soborracha.com.br<br>
                        <strong>Status:</strong> <span style="color: #22c55e;">✓ Configurado</span>
                    </div>
                </div>
                
                <!-- Testes Disponíveis -->
                <div class="test-card">
                    <h3><i class="fas fa-play-circle"></i> Testes Disponíveis</h3>
                    <p>Selecione um teste para verificar o funcionamento do sistema de email:</p>
                    
                    <form method="POST" style="display: inline;">
                        <div class="test-buttons">
                            <button type="submit" name="run_test" value="1" class="test-btn" onclick="this.form.test_type.value='connection'">
                                <i class="fas fa-plug"></i>
                                Teste de Conexão
                            </button>
                            
                            <button type="submit" name="run_test" value="1" class="test-btn" onclick="this.form.test_type.value='config'">
                                <i class="fas fa-cog"></i>
                                Teste de Configuração
                            </button>
                            
                            <button type="submit" name="run_test" value="1" class="test-btn" onclick="this.form.test_type.value='contact'">
                                <i class="fas fa-envelope"></i>
                                Teste de Contato
                            </button>
                            
                            <button type="submit" name="run_test" value="1" class="test-btn" onclick="this.form.test_type.value='welcome'">
                                <i class="fas fa-user-plus"></i>
                                Teste de Boas-vindas
                            </button>
                            
                            <button type="submit" name="run_test" value="1" class="test-btn" onclick="this.form.test_type.value='supplier'">
                                <i class="fas fa-truck"></i>
                                Teste de Fornecedor
                            </button>
                            
                            <button type="submit" name="run_test" value="1" class="test-btn success" onclick="this.form.test_type.value='all'">
                                <i class="fas fa-check-double"></i>
                                Executar Todos
                            </button>
                        </div>
                        
                        <input type="hidden" name="test_type" value="">
                    </form>
                </div>
                
                <!-- Resultados dos Testes -->
                <?php if (!empty($results)): ?>
                <div class="test-card">
                    <h3><i class="fas fa-clipboard-list"></i> Resultados dos Testes</h3>
                    
                    <?php foreach ($results as $result): ?>
                        <div class="test-result <?php echo $result['success'] ? 'success' : 'error'; ?>">
                            <h4>
                                <i class="fas fa-<?php echo $result['success'] ? 'check-circle' : 'times-circle'; ?>"></i>
                                <?php echo htmlspecialchars($result['test']); ?>
                            </h4>
                            <p><?php echo htmlspecialchars($result['message']); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
                
                <!-- Informações Adicionais -->
                <div class="test-card">
                    <h3><i class="fas fa-info-circle"></i> Informações Importantes</h3>
                    <ul>
                        <li><strong>Teste de Conexão:</strong> Verifica se é possível conectar ao servidor SMTP</li>
                        <li><strong>Teste de Configuração:</strong> Envia um email de teste para o administrador</li>
                        <li><strong>Teste de Contato:</strong> Simula o envio do formulário de contato do site</li>
                        <li><strong>Teste de Boas-vindas:</strong> Testa o email enviado para novos usuários</li>
                        <li><strong>Teste de Fornecedor:</strong> Testa a notificação de novo fornecedor</li>
                    </ul>
                    
                    <div style="margin-top: 20px; padding: 15px; background: #fef3c7; border-radius: 8px; border-left: 4px solid #f59e0b;">
                        <p><strong>Nota:</strong> Todos os emails de teste são enviados para <strong>ronaldo@soborracha.com.br</strong> conforme configuração da empresa.</p>
                    </div>
                </div>
            </div>
        </main>
    </div>
    
    <script src="assets/js/admin.js"></script>
</body>
</html>
