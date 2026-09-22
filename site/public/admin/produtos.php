<?php
require_once __DIR__ . '/../includes/AdminAuth.php';
require_once __DIR__ . '/../includes/ProductStore.php';

$auth = new AdminAuth();
$auth->requireLogin();

$store = new ProductStore();
$produtos = $store->all(false); // todos (inclui inativos) para o admin
$settings = $store->getSettings();
$csrf = $auth->csrfToken();

$flash = $_GET['msg'] ?? '';
$pageTitle = 'Produtos';
include __DIR__ . '/_header.php';
?>
<div class="page-head">
    <div>
        <h1><i class="fas fa-box"></i> Produtos</h1>
        <p>Gerencie os produtos em destaque exibidos no site.</p>
    </div>
    <a href="produto_form.php" class="btn btn-primary"><i class="fas fa-plus"></i> Novo Produto</a>
</div>

<?php if ($flash === 'salvo'): ?>
    <div class="alert alert-success">Produto salvo com sucesso.</div>
<?php elseif ($flash === 'excluido'): ?>
    <div class="alert alert-success">Produto excluído com sucesso.</div>
<?php elseif ($flash === 'config'): ?>
    <div class="alert alert-success">Textos da vitrine atualizados.</div>
<?php elseif ($flash === 'erro'): ?>
    <div class="alert alert-error">Ocorreu um erro ao processar a solicitação.</div>
<?php endif; ?>

<!-- Textos da vitrine (passar a ideia de "milhares de opções") -->
<div class="card">
    <h2 style="margin-top:0;font-size:1.1rem;"><i class="fas fa-bullhorn"></i> Textos da vitrine</h2>
    <p class="form-hint" style="margin-top:0;">Use estes textos para deixar claro ao cliente que o site mostra apenas exemplos e que a loja tem milhares de opções.</p>
    <form method="POST" action="produto_salvar.php">
        <input type="hidden" name="csrf" value="<?php echo htmlspecialchars($csrf); ?>">
        <input type="hidden" name="acao" value="settings">
        <div class="form-group">
            <label for="vitrine_titulo">Título da seção</label>
            <input type="text" id="vitrine_titulo" name="vitrine_titulo" value="<?php echo htmlspecialchars($settings['vitrine_titulo']); ?>">
        </div>
        <div class="form-group">
            <label for="vitrine_subtitulo">Subtítulo</label>
            <textarea id="vitrine_subtitulo" name="vitrine_subtitulo"><?php echo htmlspecialchars($settings['vitrine_subtitulo']); ?></textarea>
        </div>
        <div class="form-group">
            <label for="aviso_multimarcas">Aviso "milhares de opções / multimarcas"</label>
            <textarea id="aviso_multimarcas" name="aviso_multimarcas"><?php echo htmlspecialchars($settings['aviso_multimarcas']); ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Salvar textos</button>
    </form>
</div>

<!-- Lista de produtos -->
<div class="card" style="padding:0;overflow:hidden;">
    <table class="table">
        <thead>
            <tr>
                <th>Imagem</th>
                <th>Nome</th>
                <th>Categoria</th>
                <th>Ordem</th>
                <th>Status</th>
                <th style="text-align:right;">Ações</th>
            </tr>
        </thead>
        <tbody>
        <?php if (empty($produtos)): ?>
            <tr><td colspan="6" style="text-align:center;color:#64748b;padding:32px;">Nenhum produto cadastrado.</td></tr>
        <?php else: foreach ($produtos as $p): ?>
            <tr>
                <td>
                    <?php if (!empty($p['imagem'])): ?>
                        <img class="thumb" src="../<?php echo htmlspecialchars($p['imagem']); ?>" alt="">
                    <?php else: ?>
                        <span style="color:#cbd5e1;"><i class="fas fa-image fa-2x"></i></span>
                    <?php endif; ?>
                </td>
                <td>
                    <strong><?php echo htmlspecialchars($p['nome']); ?></strong>
                    <?php if (!empty($p['destaque'])): ?><br><small style="color:#dc2626;">★ Destaque</small><?php endif; ?>
                </td>
                <td><span class="badge badge-cat"><?php echo htmlspecialchars(ProductStore::$categorias[$p['categoria']] ?? $p['categoria']); ?></span></td>
                <td><?php echo (int) ($p['ordem'] ?? 0); ?></td>
                <td>
                    <?php if (!empty($p['ativo'])): ?>
                        <span class="badge badge-on">Ativo</span>
                    <?php else: ?>
                        <span class="badge badge-off">Inativo</span>
                    <?php endif; ?>
                </td>
                <td>
                    <div class="actions" style="justify-content:flex-end;">
                        <a href="produto_form.php?id=<?php echo urlencode($p['id']); ?>" class="btn btn-secondary btn-sm"><i class="fas fa-edit"></i> Editar</a>
                        <form method="POST" action="produto_excluir.php" onsubmit="return confirm('Excluir este produto?');" style="margin:0;">
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
