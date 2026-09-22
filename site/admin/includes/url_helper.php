<?php
/**
 * Helper para URLs do sistema administrativo
 */

/**
 * Gera URL absoluta baseada no diretório admin
 */
function adminUrl($path = '') {
    // Usar URL absoluta simples
    $path = ltrim($path, '/');
    return '/admin/' . $path;
}

/**
 * Gera URL para assets (CSS, JS, imagens)
 */
function adminAsset($path = '') {
    $path = ltrim($path, '/');
    return '/admin/assets/' . $path;
}

/**
 * Gera URL para uploads
 */
function adminUpload($path = '') {
    $path = ltrim($path, '/');
    return '/admin/uploads/' . $path;
}

/**
 * Gera URL para imagens públicas
 */
function publicAsset($path = '') {
    $path = ltrim($path, '/');
    return '/images/' . $path;
}

/**
 * Verifica se a URL atual corresponde ao path
 */
function isCurrentPage($path) {
    $currentUri = $_SERVER['REQUEST_URI'];
    return strpos($currentUri, $path) !== false;
}

/**
 * Gera classe CSS 'active' se for a página atual
 */
function activeClass($path) {
    return isCurrentPage($path) ? 'active' : '';
}
?>
