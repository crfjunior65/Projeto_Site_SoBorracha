<?php
require_once '../../includes/Auth.php';
require_once '../../includes/Supplier.php';
require_once '../../includes/url_helper.php';

$auth = new Auth();
$auth->requireLogin();

$supplier = new Supplier();
$user = $auth->getUser();

$error = '';
$success = '';

// Processar formulário
if ($_POST) {
    try {
        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'company_name' => trim($_POST['name'] ?? ''), // Usar o mesmo nome como company_name
            'cnpj' => trim($_POST['cnpj'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'phone' => trim($_POST['phone'] ?? ''),
            'address' => trim($_POST['address'] ?? ''),
            'city' => trim($_POST['city'] ?? ''),
            'state' => trim($_POST['state'] ?? ''),
            'zip_code' => trim($_POST['zip_code'] ?? ''),
            'category' => $_POST['category'] ?? '',
            'description' => trim($_POST['description'] ?? ''),
            'website' => trim($_POST['website'] ?? ''),
            'contact_person' => trim($_POST['contact_person'] ?? ''),
            'status' => $_POST['status'] ?? 'active'
        ];
        
        // Validações básicas
        if (empty($data['name'])) {
            throw new Exception('Nome do fornecedor é obrigatório');
        }
        
        if (empty($data['category'])) {
            throw new Exception('Categoria é obrigatória');
        }
        
        $supplierId = $supplier->create($data);
        
        if ($supplierId) {
            // Enviar notificação por email
            try {
                require_once '../../includes/EmailService.php';
                $emailService = new EmailService();
                
                $supplierData = array_merge($data, ['id' => $supplierId]);
                $emailService->sendNewSupplierNotification($supplierData);
            } catch (Exception $e) {
                // Log do erro, mas não falha a criação
                error_log("Erro ao enviar email de notificação: " . $e->getMessage());
            }
            
            $success = "Fornecedor criado com sucesso!";
        } else {
            throw new Exception("Erro ao criar fornecedor no banco de dados");
        }
        
        // Upload de logo se fornecido
        if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
            $logoPath = $supplier->uploadLogo($supplierId, $_FILES['logo']);
            if ($logoPath) {
                $supplier->update($supplierId, ['logo' => $logoPath]);
            }
        }
        
        $success = 'Fornecedor criado com sucesso!';
        
        // Redirecionar após 2 segundos
        header('refresh:2;url=index.php');
        
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

$pageTitle = 'Novo Fornecedor';
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
</head>
<body>
    <?php include '../../includes/header.php'; ?>
    
    <div class="admin-container">
        <?php include '../../includes/sidebar.php'; ?>
        
        <main class="main-content">
            <div class="content-header">
                <div class="content-title">
                    <h1><i class="fas fa-plus"></i> Novo Fornecedor</h1>
                    <p>Adicione um novo fornecedor ao sistema</p>
                </div>
                <div class="content-actions">
                    <a href="index.php" class="btn btn-outline">
                        <i class="fas fa-arrow-left"></i>
                        Voltar
                    </a>
                </div>
            </div>
            
            <div class="content-body">
                <?php if ($error): ?>
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i>
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        <?php echo htmlspecialchars($success); ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST" enctype="multipart/form-data" class="admin-form">
                    <div class="form-grid">
                        <!-- Informações Básicas -->
                        <div class="form-section">
                            <h3><i class="fas fa-info-circle"></i> Informações Básicas</h3>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="name">Nome do Fornecedor *</label>
                                    <input type="text" id="name" name="name" class="form-input" required 
                                           value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
                                </div>
                                
                                <div class="form-group">
                                    <label for="cnpj">CNPJ</label>
                                    <input type="text" id="cnpj" name="cnpj" class="form-input" 
                                           placeholder="00.000.000/0000-00"
                                           value="<?php echo htmlspecialchars($_POST['cnpj'] ?? ''); ?>">
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="category">Categoria *</label>
                                    <select id="category" name="category" class="form-select" required>
                                        <option value="">Selecione uma categoria</option>
                                        <option value="Borrachas" <?php echo ($_POST['category'] ?? '') === 'Borrachas' ? 'selected' : ''; ?>>Borrachas</option>
                                        <option value="Vedações" <?php echo ($_POST['category'] ?? '') === 'Vedações' ? 'selected' : ''; ?>>Vedações</option>
                                        <option value="Acessórios" <?php echo ($_POST['category'] ?? '') === 'Acessórios' ? 'selected' : ''; ?>>Acessórios</option>
                                        <option value="Ferramentas" <?php echo ($_POST['category'] ?? '') === 'Ferramentas' ? 'selected' : ''; ?>>Ferramentas</option>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select id="status" name="status" class="form-select">
                                        <option value="active" <?php echo ($_POST['status'] ?? 'active') === 'active' ? 'selected' : ''; ?>>Ativo</option>
                                        <option value="inactive" <?php echo ($_POST['status'] ?? '') === 'inactive' ? 'selected' : ''; ?>>Inativo</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="description">Descrição</label>
                                <textarea id="description" name="description" class="form-textarea" rows="3"
                                          placeholder="Descreva os produtos/serviços do fornecedor"><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
                            </div>
                        </div>
                        
                        <!-- Contato -->
                        <div class="form-section">
                            <h3><i class="fas fa-phone"></i> Informações de Contato</h3>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" id="email" name="email" class="form-input"
                                           value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                                </div>
                                
                                <div class="form-group">
                                    <label for="phone">Telefone</label>
                                    <input type="text" id="phone" name="phone" class="form-input"
                                           placeholder="(67) 99999-9999"
                                           value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>">
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="website">Website</label>
                                    <input type="url" id="website" name="website" class="form-input"
                                           placeholder="https://www.exemplo.com"
                                           value="<?php echo htmlspecialchars($_POST['website'] ?? ''); ?>">
                                </div>
                                
                                <div class="form-group">
                                    <label for="contact_person">Pessoa de Contato</label>
                                    <input type="text" id="contact_person" name="contact_person" class="form-input"
                                           value="<?php echo htmlspecialchars($_POST['contact_person'] ?? ''); ?>">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Endereço -->
                        <div class="form-section">
                            <h3><i class="fas fa-map-marker-alt"></i> Endereço</h3>
                            
                            <div class="form-group">
                                <label for="address">Endereço</label>
                                <input type="text" id="address" name="address" class="form-input"
                                       placeholder="Rua, número, complemento"
                                       value="<?php echo htmlspecialchars($_POST['address'] ?? ''); ?>">
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="city">Cidade</label>
                                    <input type="text" id="city" name="city" class="form-input"
                                           value="<?php echo htmlspecialchars($_POST['city'] ?? ''); ?>">
                                </div>
                                
                                <div class="form-group">
                                    <label for="state">Estado</label>
                                    <select id="state" name="state" class="form-select">
                                        <option value="">Selecione</option>
                                        <option value="MS" <?php echo ($_POST['state'] ?? '') === 'MS' ? 'selected' : ''; ?>>Mato Grosso do Sul</option>
                                        <option value="MT" <?php echo ($_POST['state'] ?? '') === 'MT' ? 'selected' : ''; ?>>Mato Grosso</option>
                                        <option value="SP" <?php echo ($_POST['state'] ?? '') === 'SP' ? 'selected' : ''; ?>>São Paulo</option>
                                        <option value="RJ" <?php echo ($_POST['state'] ?? '') === 'RJ' ? 'selected' : ''; ?>>Rio de Janeiro</option>
                                        <!-- Adicionar outros estados conforme necessário -->
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="zip_code">CEP</label>
                                    <input type="text" id="zip_code" name="zip_code" class="form-input"
                                           placeholder="00000-000"
                                           value="<?php echo htmlspecialchars($_POST['zip_code'] ?? ''); ?>">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Logo -->
                        <div class="form-section">
                            <h3><i class="fas fa-image"></i> Logo do Fornecedor</h3>
                            
                            <div class="form-group">
                                <label for="logo">Logo (opcional)</label>
                                <input type="file" id="logo" name="logo" class="form-file" accept="image/*">
                                <small class="form-help">Formatos aceitos: JPG, PNG, WebP. Tamanho máximo: 2MB</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i>
                            Salvar Fornecedor
                        </button>
                        <a href="index.php" class="btn btn-outline">
                            <i class="fas fa-times"></i>
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </main>
    </div>
    
    <script src="<?php echo adminAsset('js/admin.js'); ?>"></script>
    <script>
        // Máscara para CNPJ
        document.getElementById('cnpj').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            value = value.replace(/^(\d{2})(\d)/, '$1.$2');
            value = value.replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3');
            value = value.replace(/\.(\d{3})(\d)/, '.$1/$2');
            value = value.replace(/(\d{4})(\d)/, '$1-$2');
            e.target.value = value;
        });
        
        // Máscara para telefone
        document.getElementById('phone').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            value = value.replace(/^(\d{2})(\d)/, '($1) $2');
            value = value.replace(/(\d{5})(\d)/, '$1-$2');
            e.target.value = value;
        });
        
        // Máscara para CEP
        document.getElementById('zip_code').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            value = value.replace(/^(\d{5})(\d)/, '$1-$2');
            e.target.value = value;
        });
    </script>
</body>
</html>
