<?php
/**
 * AdminAuth - Autenticação simples do painel admin (sem banco de dados).
 * Só Borracha
 *
 * Credenciais vêm do .env:
 *   ADMIN_USER            -> nome de usuário
 *   ADMIN_PASSWORD_HASH   -> hash bcrypt da senha (password_hash)
 *
 * Fornece: login/logout, verificação de sessão, proteção de página e token CSRF.
 */

require_once __DIR__ . '/env.php';
load_env();

class AdminAuth {

    private $user;
    private $hash;
    private $sessionTtl = 7200; // 2 horas

    public function __construct() {
        $this->user = env('ADMIN_USER', 'admin');
        $this->hash = env('ADMIN_PASSWORD_HASH', '');

        if (session_status() === PHP_SESSION_NONE) {
            session_name('soborracha_admin');
            session_start();
        }
    }

    /** Tenta autenticar. Retorna true em sucesso. */
    public function login($user, $password) {
        $userOk = hash_equals((string) $this->user, (string) $user);
        $passOk = $this->hash !== '' && password_verify($password, $this->hash);

        if ($userOk && $passOk) {
            session_regenerate_id(true);
            $_SESSION['admin_logged'] = true;
            $_SESSION['admin_user']   = $this->user;
            $_SESSION['admin_time']   = time();
            return true;
        }
        return false;
    }

    public function logout() {
        $_SESSION = [];
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
    }

    /** Verifica se há sessão válida (e não expirada). */
    public function isLoggedIn() {
        if (empty($_SESSION['admin_logged'])) {
            return false;
        }
        if (time() - ($_SESSION['admin_time'] ?? 0) > $this->sessionTtl) {
            $this->logout();
            return false;
        }
        $_SESSION['admin_time'] = time(); // renova a janela
        return true;
    }

    /** Protege uma página: redireciona ao login se não autenticado. */
    public function requireLogin($loginPage = 'login.php') {
        if (!$this->isLoggedIn()) {
            header('Location: ' . $loginPage);
            exit;
        }
    }

    /** Indica se as credenciais estão configuradas no .env. */
    public function isConfigured() {
        return $this->hash !== '';
    }

    // ----- CSRF -----

    public function csrfToken() {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    public function checkCsrf($token) {
        return !empty($_SESSION['csrf_token'])
            && is_string($token)
            && hash_equals($_SESSION['csrf_token'], $token);
    }

    public function currentUser() {
        return $_SESSION['admin_user'] ?? null;
    }
}
