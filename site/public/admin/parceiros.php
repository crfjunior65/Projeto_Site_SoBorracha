<?php
require_once __DIR__ . '/../includes/AdminAuth.php';
require_once __DIR__ . '/../includes/PartnerStore.php';

$auth = new AdminAuth();
$auth->requireLogin();

$store = new PartnerStore();
$parceiros = $store->all(false);
$csrf = $auth->csrfToken();

$flash = $_GET['msg'] ?? '';
$pageTitle = 'Parceiros';
include __DIR__ . '/_header.php';
?>
<div class="page-head">
    <div>
        <h1><i class="fas fa-handshake"></i> Parceiros</h1>
        <p>Gerencie a rede de distribuidores, revendedores e oficinas parceiras.</p>
    </div>
    <a href="parceiro_form.php" class="btn btn-primary"><i class="fas fa-plus"></i> Novo Parceiro</a>
</div>

<?php if ($flash === 'salvo'): ?>
    <div class="alert alert-success">Parceiro salvo com sucesso.</div>
<?php elseif ($flash === 'excluido'): ?>
    <div class="alert alert-success">Parceiro excluído com sucesso.</div>
<?php elseif ($flash === 'erro'): ?>
    <div class="alert alert-error">Ocorreu um erro ao processar a solicitação.</div>
<?php endif; ?>

<div class="card" style="padding:0;overflow:hidden;">
    <table class="table">
        <thead>
            <tr>
                <th>Logo</th>
                <th>Nome</th>
                <th>Tipo</th>
                <th>Cidade/UF</th>
                <th>Status</th>
                <th style="text-align:right;">Ações</th>
            </tr>
        </thead>
        <tbody>
        <?php if (empty($parceiros)): ?>
            <tr><td colspan="6" style="text-align:center;color:#64748b;padding:32px;">Nenhum parceiro cadastrado.</td></tr>
        <?php else: foreach ($parceiros as $p): ?>
            <tr>
                <td>
                    <?php if (!empty($p['logo_image'])): ?>
                        <img class="thumb" src="../uploads/parceiros/<?php echo htmlspecialchars($p['logo_image']); ?>" alt="">
                    <?php else: ?>
                        <span style="color:#cbd5e1;"><i class="fas fa-handshake fa-2x"></i></span>
                    <?php endif; ?>
                </td>
                <td>
                    <strong><?php echo htmlspecialchars($p['name']); ?></strong>
                    <?php if (!empty($p['featured'])): ?><br><small style="color:#dc2626;">★ Destaque</small><?php endif; ?>
                </td>
                <td><span class="badge badge-cat"><?php echo htmlspecialchars(PartnerStore::$tipos[$p['tipo']] ?? $p['tipo'] ?? ''); ?></span></td>
                <td><?php echo htmlspecialchars(trim(($p['city'] ?? '') . ' / ' . ($p['state'] ?? ''), ' /')); ?></td>
                <td>
                    <?php if (($p['status'] ?? 'active') === 'active'): ?>
                        <span class="badge badge-on">Ativo</span>
                    <?php else: ?>
                        <span class="badge badge-off">Inativo</span>
                    <?php endif; ?>
                </td>
                <td>
                    <div class="actions" style="justify-content:flex-end;">
                        <a href="parceiro_form.php?id=<?php echo urlencode($p['id']); ?>" class="btn btn-secondary btn-sm"><i class="fas fa-edit"></i> Editar</a>
                        <form method="POST" action="parceiro_excluir.php" onsubmit="return confirm('Excluir este parceiro?');" style="margin:0;">
                            <input type="hidden" name="csrf" value="<?php echo htmlspecialchars($csrf); ?>">
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($p['id']); ?>">
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
