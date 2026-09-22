<?php
require_once '../../includes/Auth.php';
require_once '../../includes/url_helper.php';

$auth = new Auth();
$auth->requireLogin();

$user = $auth->getUser();

// Diretórios de imagens
$imageDirectories = [
    'site' => '../../uploads/site/',
    'products' => '../../uploads/products/',
    'suppliers' => '../../uploads/suppliers/',
    'public' => '../../../public/images/'
];

$currentDir = $_GET['dir'] ?? 'public';
if (!isset($imageDirectories[$currentDir])) {
    $currentDir = 'public';
}

$uploadDir = $imageDirectories[$currentDir];
$images = [];

// Buscar imagens no diretório
if (is_dir($uploadDir)) {
    $files = scandir($uploadDir);
    foreach ($files as $file) {
        if (in_array(strtolower(pathinfo($file, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'webp', 'gif'])) {
            $filePath = $uploadDir . $file;
            $images[] = [
                'name' => $file,
                'path' => $filePath,
                'url' => str_replace('../../../', '/', $filePath),
                'size' => filesize($filePath),
                'modified' => filemtime($filePath)
            ];
        }
    }
}

// Ordenar por data de modificação (mais recente primeiro)
usort($images, function($a, $b) {
    return $b['modified'] - $a['modified'];
});

// Processar upload
$uploadMessage = '';
if ($_POST && isset($_FILES['images'])) {
    $uploadCount = 0;
    $errors = [];
    
    foreach ($_FILES['images']['tmp_name'] as $key => $tmpName) {
        if ($_FILES['images']['error'][$key] === UPLOAD_ERR_OK) {
            $fileName = $_FILES['images']['name'][$key];
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            
            if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'webp', 'gif'])) {
                $newFileName = uniqid() . '.' . $fileExtension;
                $destination = $uploadDir . $newFileName;
                
                if (move_uploaded_file($tmpName, $destination)) {
                    $uploadCount++;
                } else {
                    $errors[] = "Erro ao fazer upload de $fileName";
                }
            } else {
                $errors[] = "Formato não suportado: $fileName";
            }
        }
    }
    
    if ($uploadCount > 0) {
        $uploadMessage = "✅ $uploadCount imagem(ns) enviada(s) com sucesso!";
        // Recarregar a página para mostrar as novas imagens
        header("Location: gallery.php?dir=$currentDir&uploaded=1");
        exit;
    }
    
    if (!empty($errors)) {
        $uploadMessage = "❌ " . implode(", ", $errors);
    }
}

$pageTitle = 'Galeria de Imagens';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?> - Administração | Só Borracha Ltda</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="<?php echo adminAsset('css/admin.css'); ?>">
    
    <style>
        .gallery-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        
        .image-card {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: transform 0.2s ease;
        }
        
        .image-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
        
        .image-preview {
            width: 100%;
            height: 150px;
            object-fit: cover;
            cursor: pointer;
        }
        
        .image-info {
            padding: 12px;
        }
        
        .image-name {
            font-size: 12px;
            font-weight: 500;
            margin-bottom: 4px;
            word-break: break-all;
        }
        
        .image-meta {
            font-size: 11px;
            color: #666;
            margin-bottom: 8px;
        }
        
        .image-actions {
            display: flex;
            gap: 4px;
        }
        
        .btn-small {
            padding: 4px 8px;
            font-size: 11px;
        }
        
        .directory-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            border-bottom: 1px solid #e1e8ed;
        }
        
        .directory-tab {
            padding: 10px 16px;
            background: none;
            border: none;
            border-bottom: 2px solid transparent;
            cursor: pointer;
            font-weight: 500;
            color: #666;
            text-decoration: none;
        }
        
        .directory-tab.active {
            color: #007bff;
            border-bottom-color: #007bff;
        }
        
        .upload-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .upload-area {
            border: 2px dashed #ccc;
            border-radius: 8px;
            padding: 40px;
            text-align: center;
            cursor: pointer;
            transition: border-color 0.2s ease;
        }
        
        .upload-area:hover {
            border-color: #007bff;
        }
        
        .upload-area.dragover {
            border-color: #007bff;
            background: #f0f8ff;
        }
        
        /* Modal para visualização de imagem */
        .image-modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.9);
        }
        
        .image-modal.active {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .modal-image {
            max-width: 90%;
            max-height: 90%;
            object-fit: contain;
        }
        
        .modal-close {
            position: absolute;
            top: 20px;
            right: 30px;
            color: white;
            font-size: 40px;
            cursor: pointer;
        }
        
        .empty-gallery {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }
        
        .empty-gallery i {
            font-size: 48px;
            margin-bottom: 16px;
            opacity: 0.5;
        }
    </style>
</head>
<body>
    <?php include '../../includes/header.php'; ?>
    
    <div class="admin-container">
        <?php include '../../includes/sidebar.php'; ?>
        
        <main class="main-content">
            <div class="content-header">
                <div class="content-title">
                    <h1><i class="fas fa-images"></i> Galeria de Imagens</h1>
                    <p>Gerencie todas as imagens do sistema</p>
                </div>
            </div>
            
            <div class="content-body">
                <?php if (isset($_GET['uploaded'])): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        Imagens enviadas com sucesso!
                    </div>
                <?php endif; ?>
                
                <?php if ($uploadMessage): ?>
                    <div class="alert <?php echo strpos($uploadMessage, '✅') !== false ? 'alert-success' : 'alert-error'; ?>">
                        <?php echo $uploadMessage; ?>
                    </div>
                <?php endif; ?>
                
                <!-- Abas de Diretórios -->
                <div class="directory-tabs">
                    <a href="?dir=public" class="directory-tab <?php echo $currentDir === 'public' ? 'active' : ''; ?>">
                        <i class="fas fa-globe"></i> Imagens Públicas (<?php echo count($currentDir === 'public' ? $images : []); ?>)
                    </a>
                    <a href="?dir=products" class="directory-tab <?php echo $currentDir === 'products' ? 'active' : ''; ?>">
                        <i class="fas fa-box"></i> Produtos (<?php echo count($currentDir === 'products' ? $images : []); ?>)
                    </a>
                    <a href="?dir=suppliers" class="directory-tab <?php echo $currentDir === 'suppliers' ? 'active' : ''; ?>">
                        <i class="fas fa-truck"></i> Fornecedores (<?php echo count($currentDir === 'suppliers' ? $images : []); ?>)
                    </a>
                    <a href="?dir=site" class="directory-tab <?php echo $currentDir === 'site' ? 'active' : ''; ?>">
                        <i class="fas fa-cog"></i> Sistema (<?php echo count($currentDir === 'site' ? $images : []); ?>)
                    </a>
                </div>
                
                <!-- Seção de Upload -->
                <div class="upload-section">
                    <h3><i class="fas fa-cloud-upload-alt"></i> Enviar Novas Imagens</h3>
                    <form method="POST" enctype="multipart/form-data" id="upload-form">
                        <div class="upload-area" id="upload-area">
                            <i class="fas fa-cloud-upload-alt" style="font-size: 48px; color: #ccc; margin-bottom: 16px;"></i>
                            <p>Clique aqui ou arraste imagens para fazer upload</p>
                            <p style="font-size: 12px; color: #666;">Formatos aceitos: JPG, PNG, WebP, GIF</p>
                            <input type="file" name="images[]" multiple accept="image/*" id="file-input" style="display: none;">
                        </div>
                        <div style="margin-top: 16px;">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-upload"></i>
                                Enviar Imagens
                            </button>
                        </div>
                    </form>
                </div>
                
                <!-- Galeria de Imagens -->
                <?php if (!empty($images)): ?>
                    <div class="gallery-container">
                        <?php foreach ($images as $image): ?>
                            <div class="image-card">
                                <img src="<?php echo htmlspecialchars($image['url']); ?>" 
                                     alt="<?php echo htmlspecialchars($image['name']); ?>"
                                     class="image-preview"
                                     onclick="openImageModal('<?php echo htmlspecialchars($image['url']); ?>')">
                                
                                <div class="image-info">
                                    <div class="image-name"><?php echo htmlspecialchars($image['name']); ?></div>
                                    <div class="image-meta">
                                        <?php echo number_format($image['size'] / 1024, 1); ?> KB • 
                                        <?php echo date('d/m/Y H:i', $image['modified']); ?>
                                    </div>
                                    <div class="image-actions">
                                        <button onclick="copyImageUrl('<?php echo htmlspecialchars($image['url']); ?>')" 
                                                class="btn btn-small btn-outline" title="Copiar URL">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                        <button onclick="downloadImage('<?php echo htmlspecialchars($image['url']); ?>', '<?php echo htmlspecialchars($image['name']); ?>')" 
                                                class="btn btn-small btn-outline" title="Download">
                                            <i class="fas fa-download"></i>
                                        </button>
                                        <button onclick="deleteImage('<?php echo htmlspecialchars($image['name']); ?>')" 
                                                class="btn btn-small btn-danger" title="Excluir">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="empty-gallery">
                        <i class="fas fa-images"></i>
                        <h3>Nenhuma imagem encontrada</h3>
                        <p>Faça upload de imagens usando a área acima</p>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
    
    <!-- Modal para visualização de imagem -->
    <div id="image-modal" class="image-modal">
        <span class="modal-close" onclick="closeImageModal()">&times;</span>
        <img id="modal-image" class="modal-image" src="" alt="">
    </div>
    
    <script src="<?php echo adminAsset('js/admin.js'); ?>"></script>
    <script>
        // Upload por drag and drop
        const uploadArea = document.getElementById('upload-area');
        const fileInput = document.getElementById('file-input');
        
        uploadArea.addEventListener('click', () => fileInput.click());
        
        uploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadArea.classList.add('dragover');
        });
        
        uploadArea.addEventListener('dragleave', () => {
            uploadArea.classList.remove('dragover');
        });
        
        uploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadArea.classList.remove('dragover');
            fileInput.files = e.dataTransfer.files;
        });
        
        // Modal de imagem
        function openImageModal(imageUrl) {
            const modal = document.getElementById('image-modal');
            const modalImage = document.getElementById('modal-image');
            modalImage.src = imageUrl;
            modal.classList.add('active');
        }
        
        function closeImageModal() {
            const modal = document.getElementById('image-modal');
            modal.classList.remove('active');
        }
        
        // Fechar modal ao clicar fora da imagem
        document.getElementById('image-modal').addEventListener('click', (e) => {
            if (e.target.id === 'image-modal') {
                closeImageModal();
            }
        });
        
        // Copiar URL da imagem
        function copyImageUrl(url) {
            const fullUrl = window.location.origin + url;
            navigator.clipboard.writeText(fullUrl).then(() => {
                alert('URL copiada para a área de transferência!');
            });
        }
        
        // Download da imagem
        function downloadImage(url, filename) {
            const link = document.createElement('a');
            link.href = url;
            link.download = filename;
            link.click();
        }
        
        // Excluir imagem
        function deleteImage(filename) {
            if (confirm('Tem certeza que deseja excluir esta imagem?')) {
                // Implementar exclusão via AJAX
                fetch('delete_image.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ 
                        filename: filename,
                        directory: '<?php echo $currentDir; ?>'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Erro ao excluir imagem: ' + data.message);
                    }
                })
                .catch(error => {
                    alert('Erro ao excluir imagem');
                    console.error('Error:', error);
                });
            }
        }
        
        // Tecla ESC para fechar modal
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                closeImageModal();
            }
        });
    </script>
</body>
</html>
