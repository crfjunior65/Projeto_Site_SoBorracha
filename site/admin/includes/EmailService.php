<?php
/**
 * Serviço de Email para Sistema Administrativo
 * Só Borracha Ltda - Configurações SMTP Oficiais
 */

require_once __DIR__ . '/../../includes/SoBorrachaMailer.php';

class EmailService {
    private $mailer;
    
    public function __construct() {
        $this->mailer = new SoBorrachaMailer(false); // Debug desativado em produção
    }
    
    /**
     * Enviar email de notificação para administradores
     */
    public function sendAdminNotification($subject, $message, $data = []) {
        return $this->mailer->sendAdminNotification($subject, $message, $data);
    }
    
    /**
     * Enviar email de boas-vindas para novo usuário
     */
    public function sendWelcomeEmail($userEmail, $userName, $tempPassword = null) {
        return $this->mailer->sendWelcomeEmail($userEmail, $userName, $tempPassword);
    }
    
    /**
     * Enviar email de reset de senha
     */
    public function sendPasswordReset($userEmail, $userName, $resetToken) {
        $subject = 'Redefinição de Senha - Sistema Só Borracha';
        $data = [
            'user_name' => $userName,
            'reset_link' => 'http://localhost:8080/admin/reset-password.php?token=' . $resetToken,
            'expires_in' => '1 hora'
        ];
        
        $body = $this->buildPasswordResetTemplate($data);
        
        return $this->mailer->sendEmail($userEmail, $subject, $body);
    }
    
    /**
     * Enviar relatório de backup
     */
    public function sendBackupReport($status, $details = []) {
        $subject = 'Relatório de Backup - ' . ($status ? 'Sucesso' : 'Falha');
        $message = $status ? 'Backup realizado com sucesso' : 'Falha no backup do sistema';
        
        return $this->mailer->sendAdminNotification($subject, $message, $details);
    }
    
    /**
     * Enviar notificação de novo fornecedor
     */
    public function sendNewSupplierNotification($supplierData) {
        $subject = 'Novo Fornecedor Cadastrado - ' . $supplierData['name'];
        $body = $this->buildNewSupplierTemplate($supplierData);
        
        return $this->mailer->sendEmail('', $subject, $body); // Usa destinatário padrão
    }
    
    /**
     * Enviar notificação de sistema crítico
     */
    public function sendCriticalAlert($title, $description, $details = []) {
        $subject = '[CRÍTICO] ' . $title . ' - Sistema Só Borracha';
        $message = $description;
        
        return $this->mailer->sendAdminNotification($subject, $message, $details);
    }
    
    /**
     * Enviar relatório diário
     */
    public function sendDailyReport($stats) {
        $subject = 'Relatório Diário - ' . date('d/m/Y');
        $message = 'Resumo das atividades do sistema nas últimas 24 horas';
        
        return $this->mailer->sendAdminNotification($subject, $message, $stats);
    }
    
    /**
     * Testar configuração de email
     */
    public function testEmailConfiguration() {
        $subject = 'Teste de Configuração - Sistema Só Borracha';
        $message = 'Este é um email de teste para verificar se as configurações SMTP estão funcionando corretamente.';
        $data = [
            'servidor_smtp' => 'mail.soborracha.com.br',
            'porta' => '587',
            'usuario' => 'admin@soborracha.com.br',
            'data_teste' => date('d/m/Y H:i:s')
        ];
        
        return $this->mailer->sendAdminNotification($subject, $message, $data);
    }
    
    /**
     * Obter último erro
     */
    public function getLastError() {
        return $this->mailer->getLastError();
    }
    
    /**
     * Testar conexão SMTP
     */
    public function testConnection() {
        return $this->mailer->testConnection();
    }
    
    /**
     * Template para reset de senha
     */
    private function buildPasswordResetTemplate($data) {
        return "
        <!DOCTYPE html>
        <html lang='pt-BR'>
        <head>
            <meta charset='UTF-8'>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; }
                .header { background: #dc2626; color: white; padding: 30px 20px; text-align: center; }
                .content { padding: 30px 20px; }
                .button { display: inline-block; padding: 12px 24px; background: #dc2626; color: white; text-decoration: none; border-radius: 5px; margin: 15px 0; }
                .warning { background: #fef3c7; padding: 15px; border-radius: 5px; border-left: 4px solid #f59e0b; margin: 15px 0; }
                .footer { background: #374151; color: white; padding: 15px; text-align: center; font-size: 12px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>Redefinição de Senha</h1>
                    <p>Só Borracha Ltda</p>
                </div>
                <div class='content'>
                    <h2>Olá, {$data['user_name']}!</h2>
                    <p>Recebemos uma solicitação para redefinir sua senha no sistema administrativo.</p>
                    
                    <p>Para criar uma nova senha, clique no botão abaixo:</p>
                    <a href='{$data['reset_link']}' class='button'>Redefinir Senha</a>
                    
                    <div class='warning'>
                        <p><strong>Importante:</strong></p>
                        <ul>
                            <li>Este link expira em {$data['expires_in']}</li>
                            <li>Se você não solicitou esta redefinição, ignore este email</li>
                            <li>Por segurança, não compartilhe este link</li>
                        </ul>
                    </div>
                </div>
                <div class='footer'>
                    <p>Sistema de Administração - Só Borracha Ltda</p>
                </div>
            </div>
        </body>
        </html>";
    }
    
    /**
     * Template para novo fornecedor
     */
    private function buildNewSupplierTemplate($supplier) {
        return "
        <!DOCTYPE html>
        <html lang='pt-BR'>
        <head>
            <meta charset='UTF-8'>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; }
                .header { background: #059669; color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; }
                .supplier-info { background: #f0fdf4; padding: 15px; border-radius: 5px; margin: 15px 0; border-left: 4px solid #059669; }
                .button { display: inline-block; padding: 12px 24px; background: #059669; color: white; text-decoration: none; border-radius: 5px; margin: 15px 0; }
                .footer { background: #374151; color: white; padding: 15px; text-align: center; font-size: 12px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>Novo Fornecedor Cadastrado</h1>
                    <p>Sistema Só Borracha</p>
                </div>
                <div class='content'>
                    <p>Um novo fornecedor foi cadastrado no sistema:</p>
                    
                    <div class='supplier-info'>
                        <h3>{$supplier['name']}</h3>
                        <p><strong>Empresa:</strong> " . ($supplier['company_name'] ?? $supplier['name']) . "</p>
                        " . (!empty($supplier['email']) ? "<p><strong>Email:</strong> {$supplier['email']}</p>" : "") . "
                        " . (!empty($supplier['phone']) ? "<p><strong>Telefone:</strong> {$supplier['phone']}</p>" : "") . "
                        <p><strong>Status:</strong> " . ($supplier['status'] === 'active' ? 'Ativo' : 'Inativo') . "</p>
                        <p><strong>Data de Cadastro:</strong> " . date('d/m/Y H:i:s') . "</p>
                    </div>
                    
                    <a href='http://localhost:8080/admin/pages/suppliers/view.php?id={$supplier['id']}' class='button'>Ver Detalhes</a>
                </div>
                <div class='footer'>
                    <p>Sistema de Administração - Só Borracha Ltda</p>
                </div>
            </div>
        </body>
        </html>";
    }
}
?>
