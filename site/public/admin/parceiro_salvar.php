<?php
require_once __DIR__ . '/../includes/AdminAuth.php';
require_once __DIR__ . '/../includes/PartnerStore.php';

$auth = new AdminAuth();
$auth->requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$auth->checkCsrf($_POST['csrf'] ?? '')) {
    header('Location: parceiros.php?msg=erro');
    exit;
}

$store = new PartnerStore();

$name = trim($_POST['name'] ?? '');
if ($name === '') {
    header('Location: parceiro_form.php?msg=erro');
    exit;
}

$logo = trim($_POST['logo_atual'] ?? '');

if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
    $novoLogo = processarUploadLogo($_FILES['logo'], $store->slugify($name));
    if ($novoLogo === false) {
        header('Location: parceiro_form.php?msg=erro');
        exit;
    }
    $logo = $novoLogo;
}

$parceiro = $store->save([
    'id'           => $_POST['id'] ?? '',
    'name'         => $name,
    'company_name' => $_POST['company_name'] ?? '',
    'tipo'         => $_POST['tipo'] ?? 'revendedor',
    'email'        => $_POST['email'] ?? '',
    'phone'        => $_POST['phone'] ?? '',
    'whatsapp'     => $_POST['whatsapp'] ?? '',
    'website'      => $_POST['website'] ?? '',
    'city'         => $_POST['city'] ?? '',
    'state'        => $_POST['state'] ?? '',
    'description'  => $_POST['description'] ?? '',
    'logo_image'   => $logo,
    'status'       => isset($_POST['status']) ? 'active' : 'inactive',
    'featured'     => isset($_POST['featured']),
]);

header('Location: parceiros.php?msg=' . ($parceiro ? 'salvo' : 'erro'));
exit;

/** Upload seguro de logo -> uploads/parceiros/. Retorna basename ou false. */
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

    $destDir = __DIR__ . '/../uploads/parceiros';
    if (!is_dir($destDir)) {
        @mkdir($destDir, 0755, true);
    }

    $filename = $baseName . '-' . substr(md5(uniqid('', true)), 0, 8) . '.' . $ext;
    if (!move_uploaded_file($file['tmp_name'], $destDir . '/' . $filename)) {
        return false;
    }

    return $filename;
}
