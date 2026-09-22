<?php
require_once __DIR__ . '/../includes/AdminAuth.php';
require_once __DIR__ . '/../includes/ProductStore.php';

$auth = new AdminAuth();
$auth->requireLogin();

$store = new ProductStore();
$csrf = $auth->csrfToken();

$editId = $_GET['id'] ?? '';
$produto = $editId ? $store->find($editId) : null;
$isEdit = $produto !== null;

// Valores padrão para novo produto
if (!$produto) {
    $produto = [
        'id' => '', 'nome' => '', 'categoria' => 'porta', 'descricao' => '',
        'imagem' => '', 'features' => [], 'compatibilidade' => '',
        'destaque' => false, 'badge' => '', 'ordem' => 999, 'ativo' => true,
    ];
}

$pageTitle = $isEdit ? 'Editar Produto' : 'Novo Produto';
include __DIR__ . '/_header.php';
?>
<div class="page-head">
    <div>
        <h1><i class="fas fa-box"></i> <?php echo $isEdit ? 'Editar Produto' : 'Novo Produto'; ?></h1>
        <p><?php echo $isEdit ? 'Altere as informações e salve.' : 'Preencha as informações do novo produto.'; ?></p>
    </div>
    <a href="produtos.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Voltar</a>
</div>

<div class="card">
    <form method="POST" action="produto_salvar.php" enctype="multipart/form-data">
        <input type="hidden" name="csrf" value="<?php echo htmlspecialchars($csrf); ?>">
        <input type="hidden" name="acao" value="produto">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($produto['id']); ?>">
        <input type="hidden" name="imagem_atual" value="<?php echo htmlspecialchars($produto['imagem']); ?>">

        <div class="form-group">
            <label for="nome">Nome do produto *</label>
            <input type="text" id="nome" name="nome" required value="<?php echo htmlspecialchars($produto['nome']); ?>">
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="categoria">Categoria</label>
                <select id="categoria" name="categoria">
                    <?php foreach (ProductStore::$categorias as $val => $label): ?>
                        <option value="<?php echo $val; ?>" <?php echo $produto['categoria'] === $val ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($label); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="ordem">Ordem de exibição</label>
                <input type="number" id="ordem" name="ordem" value="<?php echo (int) $produto['ordem']; ?>">
                <div class="form-hint">Menor número aparece primeiro.</div>
            </div>
        </div>

        <div class="form-group">
            <label for="descricao">Descrição</label>
            <textarea id="descricao" name="descricao"><?php echo htmlspecialchars($produto['descricao']); ?></textarea>
        </div>

        <div class="form-group">
            <label for="features">Características (uma por linha ou separadas por vírgula)</label>
            <textarea id="features" name="features"><?php echo htmlspecialchars(implode("\n", $produto['features'] ?? [])); ?></textarea>
            <div class="form-hint">Ex.: Universal, Resistente, Fácil Instalação</div>
        </div>

        <div class="form-group">
            <label for="compatibilidade">Compatibilidade</label>
            <input type="text" id="compatibilidade" name="compatibilidade" value="<?php echo htmlspecialchars($produto['compatibilidade']); ?>">
            <div class="form-hint">Ex.: Volkswagen, Fiat, Chevrolet, Ford, Toyota</div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="badge">Selo / Badge (opcional)</label>
                <input type="text" id="badge" name="badge" value="<?php echo htmlspecialchars($produto['badge']); ?>">
                <div class="form-hint">Ex.: Mais Vendido</div>
            </div>
            <div class="form-group">
                <label for="imagem">Imagem do produto</label>
                <input type="file" id="imagem" name="imagem" accept="image/jpeg,image/png,image/webp">
                <div class="form-hint">JPG, PNG ou WEBP (máx. 4 MB). Deixe em branco para manter a atual.</div>
            </div>
        </div>

        <?php if (!empty($produto['imagem'])): ?>
            <div class="form-group">
                <label>Imagem atual</label><br>
                <img src="../<?php echo htmlspecialchars($produto['imagem']); ?>" alt="" style="max-width:200px;border-radius:8px;border:1px solid #e2e8f0;">
            </div>
        <?php endif; ?>

        <div class="form-group">
            <label class="checkbox-inline"><input type="checkbox" name="destaque" value="1" <?php echo !empty($produto['destaque']) ? 'checked' : ''; ?>> Marcar como destaque</label>
        </div>
        <div class="form-group">
            <label class="checkbox-inline"><input type="checkbox" name="ativo" value="1" <?php echo !empty($produto['ativo']) ? 'checked' : ''; ?>> Produto ativo (visível no site)</label>
        </div>

        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Salvar produto</button>
    </form>
</div>

</div><!-- /admin-wrap -->
</body>
</html>
