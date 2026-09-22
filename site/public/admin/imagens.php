<?php
require_once __DIR__ . '/../includes/AdminAuth.php';

$auth = new AdminAuth();
$auth->requireLogin();

// Imagens principais do site que podem ser trocadas.
// A chave é o nome do arquivo alvo (mantido para não alterar o HTML).
$imagensSite = [
    'hero-borrachas.jpg'    => ['label' => 'Banner principal (home)', 'desc' => 'Imagem grande exibida no topo da página inicial.'],
    'loja-fachada.jpg'      => ['label' => 'Fachada da loja',        'desc' => 'Foto usada na home e na página Sobre.'],
    'LogoSB-Photoroom.png'  => ['labeL' => 'Logo', 'label' => 'Logotipo', 'desc' => 'Logo exibido no cabeçalho e rodapé.'],
];

$msg = '';
$msgTipo = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!$auth->checkCsrf($_POST['csrf'] ?? '')) {
        $msg = 'Sessão expirada. Recarregue a página.'; $msgTipo = 'error';
    } else {
        $alvo = $_POST['alvo'] ?? '';
        if (!isset($imagensSite[$alvo])) {
            $msg = 'Imagem inválida.'; $msgTipo = 'error';
        } elseif (!isset($_FILES['arquivo']) || $_FILES['arquivo']['error'] !== UPLOAD_ERR_OK) {
            $msg = 'Selecione um arquivo válido para enviar.'; $msgTipo = 'error';
        } else {
            $res = substituirImagemSite($_FILES['arquivo'], $alvo);
            if ($res === true) {
                $msg = 'Imagem "' . $imagensSite[$alvo]['label'] . '" atualizada com sucesso!'; $msgTipo = 'success';
            } else {
                $msg = $res; $msgTipo = 'error';
            }
        }
    }
}

$csrf = $auth->csrfToken();
$pageTitle = 'Imagens do site';
include __DIR__ . '/_header.php';

/**
 * Substitui uma imagem do site mantendo o nome do arquivo alvo.
 * A extensão do alvo define o formato aceito (jpg->jpeg, png->png).
 * Retorna true em sucesso ou uma mensagem de erro (string).
 */
function substituirImagemSite($file, $alvo) {
    $maxSize = 5 * 1024 * 1024; // 5 MB
    if ($file['size'] > $maxSize) {
        return 'Arquivo muito grande (máximo 5 MB).';
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($file['tmp_name']);

    // Extensão alvo -> mimes aceitos
    $ext = strtolower(pathinfo($alvo, PATHINFO_EXTENSION));
    $regras = [
        'jpg'  => ['image/jpeg'],
        'jpeg' => ['image/jpeg'],
        'png'  => ['image/png'],
        'webp' => ['image/webp'],
    ];
    if (!isset($regras[$ext]) || !in_array($mime, $regras[$ext], true)) {
        return 'O arquivo enviado deve ser do tipo ' . strtoupper($ext) . ' para substituir esta imagem.';
    }

    $destDir = __DIR__ . '/../images';
    $destPath = $destDir . '/' . $alvo;

    // Backup da imagem atual (por segurança)
    if (is_file($destPath)) {
        @copy($destPath, $destDir . '/_backup_' . $alvo);
    }

    if (!move_uploaded_file($file['tmp_name'], $destPath)) {
        return 'Falha ao salvar a imagem. Verifique as permissões da pasta images/.';
    }

    return true;
}
?>
<div class="page-head">
    <div>
        <h1><i class="fas fa-image"></i> Imagens do site</h1>
        <p>Troque as imagens principais para deixar o site mais moderno e atraente.</p>
    </div>
    <a href="produtos.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Voltar</a>
</div>

<?php if ($msg): ?>
    <div class="alert alert-<?php echo $msgTipo === 'success' ? 'success' : 'error'; ?>"><?php echo htmlspecialchars($msg); ?></div>
<?php endif; ?>

<?php foreach ($imagensSite as $arquivo => $info): ?>
<div class="card">
    <div style="display:flex;gap:24px;align-items:flex-start;flex-wrap:wrap;">
        <div style="flex:0 0 220px;">
            <img src="../images/<?php echo htmlspecialchars($arquivo); ?>?v=<?php echo @filemtime(__DIR__ . '/../images/' . $arquivo) ?: time(); ?>"
                 alt="<?php echo htmlspecialchars($info['label']); ?>"
                 style="width:220px;height:150px;object-fit:contain;background:#f1f5f9;border-radius:8px;border:1px solid #e2e8f0;">
        </div>
        <div style="flex:1;min-width:260px;">
            <h2 style="margin:0 0 4px;font-size:1.1rem;"><?php echo htmlspecialchars($info['label']); ?></h2>
            <p class="form-hint" style="margin-top:0;"><?php echo htmlspecialchars($info['desc']); ?></p>
            <p class="form-hint">Formato: <strong><?php echo strtoupper(pathinfo($arquivo, PATHINFO_EXTENSION)); ?></strong> · Arquivo: <code><?php echo htmlspecialchars($arquivo); ?></code></p>
            <form method="POST" action="imagens.php" enctype="multipart/form-data" style="margin-top:12px;">
                <input type="hidden" name="csrf" value="<?php echo htmlspecialchars($csrf); ?>">
                <input type="hidden" name="alvo" value="<?php echo htmlspecialchars($arquivo); ?>">
                <div class="form-group">
                    <input type="file" name="arquivo" accept="image/*" required>
                    <div class="form-hint">Envie no mesmo formato (<?php echo strtoupper(pathinfo($arquivo, PATHINFO_EXTENSION)); ?>), máx. 5 MB.</div>
                </div>
                <button type="submit" class="btn btn-primary"><i class="fas fa-upload"></i> Trocar imagem</button>
            </form>
        </div>
    </div>
</div>
<?php endforeach; ?>

</div><!-- /admin-wrap -->
</body>
</html>
