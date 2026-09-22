<?php
require_once __DIR__ . '/../includes/AdminAuth.php';
require_once __DIR__ . '/../includes/SupplierStore.php';

$auth = new AdminAuth();
$auth->requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$auth->checkCsrf($_POST['csrf'] ?? '')) {
    header('Location: fornecedores.php?msg=erro');
    exit;
}

$store = new SupplierStore();

$name = trim($_POST['name'] ?? '');
if ($name === '') {
    header('Location: fornecedor_form.php?msg=erro');
    exit;
}

$logo = trim($_POST['logo_atual'] ?? ''); // mantém o atual por padrão

if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
    $novoLogo = processarUploadLogo($_FILES['logo'], $store->slugify($name));
    if ($novoLogo === false) {
        header('Location: fornecedor_form.php?msg=erro');
        exit;
    }
    $logo = $novoLogo;
}

$fornecedor = $store->save([
    'id'           => $_POST['id'] ?? '',
    'name'         => $name,
    'company_name' => $_POST['company_name'] ?? '',
    'email'        => $_POST['email'] ?? '',
    'phone'        => $_POST['phone'] ?? '',
    'whatsapp'     => $_POST['whatsapp'] ?? '',
    'website'      => $_POST['website'] ?? '',
    'city'         => $_POST['city'] ?? '',
    'state'        => $_POST['state'] ?? '',
    'description'  => $_POST['description'] ?? '',
    'logo_image'   => $logo,
    'specialties'  => $_POST['specialties'] ?? '',
    'status'       => isset($_POST['status']) ? 'active' : 'inactive',
    'featured'     => isset($_POST['featured']),
]);

header('Location: fornecedores.php?msg=' . ($fornecedor ? 'salvo' : 'erro'));
exit;

/**
 * Upload seguro de logo. Retorna nome do arquivo salvo (só o basename) ou false.
 * O arquivo é gravado em uploads/fornecedores/.
 */
function processarUploadLogo($file, $baseName) {
    $maxSize = 3 * 1024 * 1024; // 3 MB
    if ($file['size'] > $maxSize) {
        return false;
    }

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

    $destDir = __DIR__ . '/../uploads/fornecedores';
    if (!is_dir($destDir)) {
        @mkdir($destDir, 0755, true);
    }

    $filename = $baseName . '-' . substr(md5(uniqid('', true)), 0, 8) . '.' . $ext;
    if (!move_uploaded_file($file['tmp_name'], $destDir . '/' . $filename)) {
        return false;
    }

    return $filename; // apenas o nome; o site monta o caminho uploads/fornecedores/
}
