<?php
require_once __DIR__ . '/../includes/AdminAuth.php';
require_once __DIR__ . '/../includes/SupplierStore.php';

$auth = new AdminAuth();
$auth->requireLogin();

$store = new SupplierStore();
$fornecedores = $store->all(false);
$csrf = $auth->csrfToken();

$flash = $_GET['msg'] ?? '';
$pageTitle = 'Fornecedores';
include __DIR__ . '/_header.php';
?>
<div class="page-head">
    <div>
        <h1><i class="fas fa-truck"></i> Fornecedores</h1>
        <p>Gerencie os fornecedores parceiros exibidos no site.</p>
    </div>
    <a href="fornecedor_form.php" class="btn btn-primary"><i class="fas fa-plus"></i> Novo Fornecedor</a>
</div>

<?php if ($flash === 'salvo'): ?>
    <div class="alert alert-success">Fornecedor salvo com sucesso.</div>
<?php elseif ($flash === 'excluido'): ?>
    <div class="alert alert-success">Fornecedor excluído com sucesso.</div>
<?php elseif ($flash === 'erro'): ?>
    <div class="alert alert-error">Ocorreu um erro ao processar a solicitação.</div>
<?php endif; ?>

<div class="card" style="padding:0;overflow:hidden;">
    <table class="table">
        <thead>
            <tr>
                <th>Logo</th>
                <th>Nome</th>
                <th>Empresa</th>
                <th>Cidade/UF</th>
                <th>Status</th>
                <th style="text-align:right;">Ações</th>
            </tr>
        </thead>
        <tbody>
        <?php if (empty($fornecedores)): ?>
            <tr><td colspan="6" style="text-align:center;color:#64748b;padding:32px;">Nenhum fornecedor cadastrado.</td></tr>
        <?php else: foreach ($fornecedores as $f): ?>
            <tr>
                <td>
                    <?php if (!empty($f['logo_image'])): ?>
                        <img class="thumb" src="../uploads/fornecedores/<?php echo htmlspecialchars($f['logo_image']); ?>" alt="">
                    <?php else: ?>
                        <span style="color:#cbd5e1;"><i class="fas fa-building fa-2x"></i></span>
                    <?php endif; ?>
                </td>
                <td>
                    <strong><?php echo htmlspecialchars($f['name']); ?></strong>
                    <?php if (!empty($f['featured'])): ?><br><small style="color:#dc2626;">★ Destaque</small><?php endif; ?>
                </td>
                <td><?php echo htmlspecialchars($f['company_name'] ?? ''); ?></td>
                <td><?php echo htmlspecialchars(trim(($f['city'] ?? '') . ' / ' . ($f['state'] ?? ''), ' /')); ?></td>
                <td>
                    <?php if (($f['status'] ?? 'active') === 'active'): ?>
                        <span class="badge badge-on">Ativo</span>
                    <?php else: ?>
                        <span class="badge badge-off">Inativo</span>
                    <?php endif; ?>
                </td>
                <td>
                    <div class="actions" style="justify-content:flex-end;">
                        <a href="fornecedor_form.php?id=<?php echo urlencode($f['id']); ?>" class="btn btn-secondary btn-sm"><i class="fas fa-edit"></i> Editar</a>
                        <form method="POST" action="fornecedor_excluir.php" onsubmit="return confirm('Excluir este fornecedor?');" style="margin:0;">
                            <input type="hidden" name="csrf" value="<?php echo htmlspecialchars($csrf); ?>">
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($f['id']); ?>">
                            <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
        <?php endforeach; endif; ?>
        </tbody>
    </table>
</div>

</div><!-- /admin-wrap -->
</body>
</html>
