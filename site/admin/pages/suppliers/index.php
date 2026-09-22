<?php
require_once '../../includes/Auth.php';
require_once '../../includes/Supplier.php';
require_once '../../includes/url_helper.php';

$auth = new Auth();
$auth->requireLogin();

$supplier = new Supplier();
$suppliers = $supplier->getAll();
$user = $auth->getUser();

$pageTitle = 'Fornecedores';
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
                    <h1><i class="fas fa-truck"></i> Fornecedores</h1>
                    <p>Gerencie todos os fornecedores cadastrados</p>
                </div>
                <div class="content-actions">
                    <a href="create.php" class="btn btn-primary">
                        <i class="fas fa-plus"></i>
                        Novo Fornecedor
                    </a>
                </div>
            </div>
            
            <div class="content-body">
                <!-- Filtros -->
                <div class="filters-section">
                    <div class="filters-row">
                        <div class="filter-group">
                            <input type="text" id="search-input" placeholder="Buscar fornecedores..." class="form-input">
                        </div>
                        <div class="filter-group">
                            <select id="status-filter" class="form-select">
                                <option value="">Todos os Status</option>
                                <option value="active">Ativo</option>
                                <option value="inactive">Inativo</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <select id="category-filter" class="form-select">
                                <option value="">Todas as Categorias</option>
                                <option value="borrachas">Borrachas</option>
                                <option value="vedacoes">Vedações</option>
                                <option value="acessorios">Acessórios</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <!-- Lista de Fornecedores -->
                <div class="data-table-container">
                    <table class="data-table" id="suppliers-table">
                        <thead>
                            <tr>
                                <th>Fornecedor</th>
                                <th>Contato</th>
                                <th>Categoria</th>
                                <th>Status</th>
                                <th>Cadastrado</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($suppliers as $sup): ?>
                            <tr data-status="<?php echo $sup['status']; ?>" data-category="<?php echo strtolower($sup['category']); ?>">
                                <td>
                                    <div class="supplier-info">
                                        <?php if ($sup['logo']): ?>
                                            <img src="../../uploads/suppliers/<?php echo $sup['logo']; ?>" alt="<?php echo htmlspecialchars($sup['name']); ?>" class="supplier-logo">
                                        <?php else: ?>
                                            <div class="supplier-avatar">
                                                <i class="fas fa-building"></i>
                                            </div>
                                        <?php endif; ?>
                                        <div class="supplier-details">
                                            <strong><?php echo htmlspecialchars($sup['name']); ?></strong>
                                            <small><?php echo htmlspecialchars($sup['cnpj'] ?? 'CNPJ não informado'); ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="contact-info">
                                        <?php if ($sup['phone']): ?>
                                            <div><i class="fas fa-phone"></i> <?php echo htmlspecialchars($sup['phone']); ?></div>
                                        <?php endif; ?>
                                        <?php if ($sup['email']): ?>
                                            <div><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($sup['email']); ?></div>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <span class="category-badge category-<?php echo strtolower($sup['category']); ?>">
                                        <?php echo htmlspecialchars($sup['category']); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="status-badge status-<?php echo $sup['status']; ?>">
                                        <?php echo $sup['status'] === 'active' ? 'Ativo' : 'Inativo'; ?>
                                    </span>
                                </td>
                                <td>
                                    <time datetime="<?php echo $sup['created_at']; ?>">
                                        <?php echo date('d/m/Y', strtotime($sup['created_at'])); ?>
                                    </time>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="view.php?id=<?php echo $sup['id']; ?>" class="btn btn-small btn-outline" title="Ver Detalhes">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="edit.php?id=<?php echo $sup['id']; ?>" class="btn btn-small btn-primary" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button onclick="deleteSupplier(<?php echo $sup['id']; ?>)" class="btn btn-small btn-danger" title="Excluir">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    
                    <?php if (empty($suppliers)): ?>
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="fas fa-truck"></i>
                        </div>
                        <h3>Nenhum fornecedor cadastrado</h3>
                        <p>Comece adicionando seu primeiro fornecedor</p>
                        <a href="create.php" class="btn btn-primary">
                            <i class="fas fa-plus"></i>
                            Adicionar Fornecedor
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
    
    <script src="<?php echo adminAsset('js/admin.js'); ?>"></script>
    <script>
        // Filtros
        document.getElementById('search-input').addEventListener('input', filterSuppliers);
        document.getElementById('status-filter').addEventListener('change', filterSuppliers);
        document.getElementById('category-filter').addEventListener('change', filterSuppliers);
        
        function filterSuppliers() {
            const searchTerm = document.getElementById('search-input').value.toLowerCase();
            const statusFilter = document.getElementById('status-filter').value;
            const categoryFilter = document.getElementById('category-filter').value;
            const rows = document.querySelectorAll('#suppliers-table tbody tr');
            
            rows.forEach(row => {
                const supplierName = row.querySelector('.supplier-details strong').textContent.toLowerCase();
                const supplierStatus = row.dataset.status;
                const supplierCategory = row.dataset.category;
                
                const matchesSearch = supplierName.includes(searchTerm);
                const matchesStatus = !statusFilter || supplierStatus === statusFilter;
                const matchesCategory = !categoryFilter || supplierCategory === categoryFilter;
                
                if (matchesSearch && matchesStatus && matchesCategory) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
        
        // Função para excluir fornecedor
        function deleteSupplier(id) {
            if (confirm('Tem certeza que deseja excluir este fornecedor?')) {
                fetch('delete.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Erro ao excluir fornecedor: ' + data.message);
                    }
                })
                .catch(error => {
                    alert('Erro ao excluir fornecedor');
                    console.error('Error:', error);
                });
            }
        }
    </script>
</body>
</html>
