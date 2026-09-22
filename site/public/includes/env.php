<?php
/**
 * Carregador simples de variáveis de ambiente (.env) - Só Borracha
 *
 * Lê um arquivo .env (KEY=VALUE por linha) e popula getenv()/$_ENV/$_SERVER,
 * sem depender de bibliotecas externas (Composer NÃO é necessário).
 *
 * Uso:
 *   require_once __DIR__ . '/env.php';
 *   load_env();                       // procura o .env automaticamente
 *   $senha = env('SMTP_PASSWORD', 'fallback');
 *
 * Prioridade:
 * - Variáveis já presentes no ambiente do servidor (ex.: injetadas pelo
 *   docker-compose ou pelo painel do cPanel) NÃO são sobrescritas pelo .env.
 * - Linhas iniciadas por # são comentários. Valores podem usar aspas.
 */

if (!function_exists('load_env')) {
    function load_env($path = null) {
        $candidates = [];

        if ($path !== null) {
            $candidates[] = $path;
        } else {
            // Locais possíveis do .env, cobrindo diferentes layouts (dev, Docker, cPanel).
            $candidates[] = __DIR__ . '/../.env';   // site/.env (layout do projeto)
            $candidates[] = __DIR__ . '/.env';      // includes/.env
            $candidates[] = getcwd() . '/.env';     // diretório de execução
            // Um nível acima do webroot (recomendado no cPanel: fora de public_html)
            if (isset($_SERVER['DOCUMENT_ROOT']) && $_SERVER['DOCUMENT_ROOT']) {
                $candidates[] = dirname($_SERVER['DOCUMENT_ROOT']) . '/.env';
                $candidates[] = $_SERVER['DOCUMENT_ROOT'] . '/.env';
            }
        }

        foreach ($candidates as $file) {
            if ($file && is_readable($file)) {
                return parse_env_file($file);
            }
        }

        return false; // Sem .env: usa apenas variáveis já presentes no ambiente
    }

    function parse_env_file($path) {
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if ($lines === false) {
            return false;
        }

        foreach ($lines as $line) {
            $line = trim($line);

            if ($line === '' || $line[0] === '#' || strpos($line, '=') === false) {
                continue;
            }

            list($name, $value) = explode('=', $line, 2);
            $name  = trim($name);
            $value = trim($value);

            // Remove aspas envolventes
            if (strlen($value) >= 2) {
                $first = $value[0];
                $last  = $value[strlen($value) - 1];
                if (($first === '"' && $last === '"') || ($first === "'" && $last === "'")) {
                    $value = substr($value, 1, -1);
                }
            }

            // Não sobrescreve variáveis já definidas no ambiente do servidor
            if (getenv($name) !== false || isset($_ENV[$name]) || isset($_SERVER[$name])) {
                continue;
            }

            putenv("$name=$value");
            $_ENV[$name]    = $value;
            $_SERVER[$name] = $value;
        }

        return true;
    }
}

if (!function_exists('env')) {
    /**
     * Lê uma variável de ambiente com valor padrão (fallback).
     */
    function env($key, $default = null) {
        $value = getenv($key);
        if ($value === false) {
            $value = $_ENV[$key] ?? $_SERVER[$key] ?? null;
        }
        if ($value === null || $value === '') {
            return $default;
        }

        switch (strtolower($value)) {
            case 'true':  return true;
            case 'false': return false;
            case 'null':  return null;
        }

        return $value;
    }
}
