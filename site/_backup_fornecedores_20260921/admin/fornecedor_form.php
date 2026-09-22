<?php
require_once __DIR__ . '/../includes/AdminAuth.php';
require_once __DIR__ . '/../includes/SupplierStore.php';

$auth = new AdminAuth();
$auth->requireLogin();

$store = new SupplierStore();
$csrf = $auth->csrfToken();

$editId = $_GET['id'] ?? '';
$f = $editId ? $store->find($editId) : null;
$isEdit = $f !== null;

if (!$f) {
    $f = [
        'id' => '', 'name' => '', 'company_name' => '', 'email' => '', 'phone' => '',
        'whatsapp' => '', 'website' => '', 'city' => '', 'state' => '', 'description' => '',
        'logo_image' => '', 'specialties' => [], 'status' => 'active', 'featured' => false,
    ];
}

$pageTitle = $isEdit ? 'Editar Fornecedor' : 'Novo Fornecedor';
include __DIR__ . '/_header.php';
?>
<div class="page-head">
    <div>
        <h1><i class="fas fa-truck"></i> <?php echo $isEdit ? 'Editar Fornecedor' : 'Novo Fornecedor'; ?></h1>
        <p><?php echo $isEdit ? 'Altere as informações e salve.' : 'Preencha as informações do novo fornecedor.'; ?></p>
    </div>
    <a href="fornecedores.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Voltar</a>
</div>

<div class="card">
    <form method="POST" action="fornecedor_salvar.php" enctype="multipart/form-data">
        <input type="hidden" name="csrf" value="<?php echo htmlspecialchars($csrf); ?>">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($f['id']); ?>">
        <input type="hidden" name="logo_atual" value="<?php echo htmlspecialchars($f['logo_image']); ?>">

        <div class="form-row">
            <div class="form-group">
                <label for="name">Nome do fornecedor *</label>
                <input type="text" id="name" name="name" required value="<?php echo htmlspecialchars($f['name']); ?>">
            </div>
            <div class="form-group">
                <label for="company_name">Razão social / Empresa</label>
                <input type="text" id="company_name" name="company_name" value="<?php echo htmlspecialchars($f['company_name']); ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="text" id="email" name="email" value="<?php echo htmlspecialchars($f['email']); ?>">
            </div>
            <div class="form-group">
                <label for="phone">Telefone</label>
                <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($f['phone']); ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="whatsapp">WhatsApp (somente números)</label>
                <input type="text" id="whatsapp" name="whatsapp" value="<?php echo htmlspecialchars($f['whatsapp']); ?>" placeholder="5567999999999">
            </div>
            <div class="form-group">
                <label for="website">Site (URL)</label>
                <input type="text" id="website" name="website" value="<?php echo htmlspecialchars($f['website']); ?>" placeholder="https://...">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="city">Cidade</label>
                <input type="text" id="city" name="city" value="<?php echo htmlspecialchars($f['city']); ?>">
            </div>
            <div class="form-group">
                <label for="state">UF</label>
                <input type="text" id="state" name="state" maxlength="2" value="<?php echo htmlspecialchars($f['state']); ?>" placeholder="MS">
            </div>
        </div>

        <div class="form-group">
            <label for="description">Descrição</label>
            <textarea id="description" name="description"><?php echo htmlspecialchars($f['description']); ?></textarea>
        </div>

        <div class="form-group">
            <label for="specialties">Especialidades (uma por linha ou separadas por vírgula)</label>
            <textarea id="specialties" name="specialties"><?php echo htmlspecialchars(implode("\n", $f['specialties'] ?? [])); ?></textarea>
            <div class="form-hint">Ex.: Borrachas de Porta, Perfis Especiais</div>
        </div>

        <div class="form-group">
            <label for="logo">Logo do fornecedor</label>
            <input type="file" id="logo" name="logo" accept="image/jpeg,image/png,image/webp">
            <div class="form-hint">JPG, PNG ou WEBP (máx. 3 MB). Deixe em branco para manter o atual.</div>
        </div>

        <?php if (!empty($f['logo_image'])): ?>
            <div class="form-group">
                <label>Logo atual</label><br>
                <img src="../uploads/fornecedores/<?php echo htmlspecialchars($f['logo_image']); ?>" alt="" style="max-width:160px;border-radius:8px;border:1px solid #e2e8f0;">
            </div>
        <?php endif; ?>

        <div class="form-group">
            <label class="checkbox-inline"><input type="checkbox" name="featured" value="1" <?php echo !empty($f['featured']) ? 'checked' : ''; ?>> Parceiro destaque</label>
        </div>
        <div class="form-group">
            <label class="checkbox-inline"><input type="checkbox" name="status" value="active" <?php echo ($f['status'] ?? 'active') === 'active' ? 'checked' : ''; ?>> Ativo (visível no site)</label>
        </div>

        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Salvar fornecedor</button>
    </form>
</div>

</div><!-- /admin-wrap -->
</body>
</html>
