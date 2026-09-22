<?php
/**
 * Script para resetar senha do administrador
 * Execute este arquivo uma vez para resetar a senha
 */

require_once '../config/database.php';

try {
    $db = getDB();
    
    // Nova senha
    $newPassword = 'admin123';
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    
    // Atualizar senha do usuário admin
    $result = $db->update(
        'admin_users',
        ['password' => $hashedPassword],
        'username = :username',
        ['username' => 'admin']
    );
    
    echo "<!DOCTYPE html>";
    echo "<html><head><title>Reset Password</title></head><body>";
    echo "<h2>✅ Senha resetada com sucesso!</h2>";
    echo "<p><strong>Usuário:</strong> admin</p>";
    echo "<p><strong>Nova senha:</strong> admin123</p>";
    echo "<p><strong>Hash gerado:</strong> " . htmlspecialchars($hashedPassword) . "</p>";
    echo "<hr>";
    echo "<p><a href='login.php'>🔐 Ir para Login</a></p>";
    echo "<p><strong>⚠️ IMPORTANTE:</strong> Delete este arquivo após usar!</p>";
    echo "</body></html>";
    
} catch (Exception $e) {
    echo "<!DOCTYPE html>";
    echo "<html><head><title>Erro</title></head><body>";
    echo "<h2>❌ Erro ao resetar senha:</h2>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
    echo "</body></html>";
}
?>
