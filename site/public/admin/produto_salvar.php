<?php
require_once __DIR__ . '/../includes/AdminAuth.php';
require_once __DIR__ . '/../includes/ProductStore.php';

$auth = new AdminAuth();
$auth->requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$auth->checkCsrf($_POST['csrf'] ?? '')) {
    header('Location: produtos.php?msg=erro');
    exit;
}

$store = new ProductStore();
$acao = $_POST['acao'] ?? '';

// ---- Salvar textos da vitrine ----
if ($acao === 'settings') {
    $store->saveSettings([
        'vitrine_titulo'    => $_POST['vitrine_titulo'] ?? '',
        'vitrine_subtitulo' => $_POST['vitrine_subtitulo'] ?? '',
        'aviso_multimarcas' => $_POST['aviso_multimarcas'] ?? '',
    ]);
    header('Location: produtos.php?msg=config');
    exit;
}

// ---- Salvar produto ----
if ($acao === 'produto') {
    $nome = trim($_POST['nome'] ?? '');
    if ($nome === '') {
        header('Location: produto_form.php?msg=erro');
        exit;
    }

    $imagem = trim($_POST['imagem_atual'] ?? ''); // mantém a atual por padrão

    // Upload de nova imagem (opcional)
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        $novaImagem = processarUpload($_FILES['imagem'], $store->slugify($nome));
        if ($novaImagem === false) {
            header('Location: produto_form.php?msg=erro');
            exit;
        }
        $imagem = $novaImagem;
    }

    $produto = $store->save([
        'id'              => $_POST['id'] ?? '',
        'nome'            => $nome,
        'categoria'       => $_POST['categoria'] ?? 'outros',
        'descricao'       => $_POST['descricao'] ?? '',
        'imagem'          => $imagem,
        'features'        => $_POST['features'] ?? '',
        'compatibilidade' => $_POST['compatibilidade'] ?? '',
        'destaque'        => isset($_POST['destaque']),
        'badge'           => $_POST['badge'] ?? '',
        'ordem'           => $_POST['ordem'] ?? 999,
        'ativo'           => isset($_POST['ativo']),
    ]);

    header('Location: produtos.php?msg=' . ($produto ? 'salvo' : 'erro'));
    exit;
}

header('Location: produtos.php?msg=erro');
exit;

/**
 * Processa upload de imagem de forma segura.
 * Retorna o caminho relativo (ex.: images/produtos/xxx.jpg) ou false em erro.
 */
function processarUpload($file, $baseName) {
    $maxSize = 4 * 1024 * 1024; // 4 MB
    if ($file['size'] > $maxSize) {
        return false;
    }

    // Valida o tipo real do arquivo (não confia na extensão enviada)
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($file['tmp_name']);
    $permitidos = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp',
    ];
    if (!isset($permitidos[$mime])) {
        return false;
    }
    $ext = $permitidos[$mime];

    // Destino: images/produtos/
    $destDir = __DIR__ . '/../images/produtos';
    if (!is_dir($destDir)) {
        @mkdir($destDir, 0755, true);
    }

    // Nome único e seguro
    $filename = $baseName . '-' . substr(md5(uniqid('', true)), 0, 8) . '.' . $ext;
    $destPath = $destDir . '/' . $filename;

    if (!move_uploaded_file($file['tmp_name'], $destPath)) {
        return false;
    }

    // Caminho relativo usado pelo site (a partir de public/)
    return 'images/produtos/' . $filename;
}
