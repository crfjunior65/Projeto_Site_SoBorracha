<?php
/**
 * Configuração de caminhos para o sistema administrativo
 * Este arquivo detecta automaticamente os caminhos corretos baseado na localização atual
 */

// Função para detectar o caminho correto do config
function getConfigPath() {
    $possiblePaths = [
        '../config/database.php',
        '../../config/database.php', 
        '../../../config/database.php',
        __DIR__ . '/../../config/database.php'
    ];
    
    foreach ($possiblePaths as $path) {
        if (file_exists($path)) {
            return $path;
        }
    }
    
    // Fallback para caminho absoluto
    return dirname(__DIR__, 2) . '/config/database.php';
}

// Função para detectar o caminho correto dos assets
function getAssetPath($asset) {
    $currentDir = dirname($_SERVER['PHP_SELF']);
    $depth = substr_count($currentDir, '/') - 2; // -2 porque começamos em /admin/
    
    $prefix = str_repeat('../', max(0, $depth));
    return $prefix . $asset;
}

// Função para detectar o caminho correto dos uploads
function getUploadPath($type = '') {
    $currentDir = dirname($_SERVER['PHP_SELF']);
    $depth = substr_count($currentDir, '/') - 2;
    
    $prefix = str_repeat('../', max(0, $depth));
    return $prefix . 'uploads/' . ($type ? $type . '/' : '');
}

// Definir constantes de caminho
if (!defined('CONFIG_PATH')) {
    define('CONFIG_PATH', getConfigPath());
}

if (!defined('ADMIN_ROOT')) {
    define('ADMIN_ROOT', dirname(__DIR__));
}

if (!defined('PROJECT_ROOT')) {
    define('PROJECT_ROOT', dirname(__DIR__, 2));
}
?>
