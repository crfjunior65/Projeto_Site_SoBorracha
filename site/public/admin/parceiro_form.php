<?php
require_once __DIR__ . '/../includes/AdminAuth.php';
require_once __DIR__ . '/../includes/PartnerStore.php';

$auth = new AdminAuth();
$auth->requireLogin();

$store = new PartnerStore();
$csrf = $auth->csrfToken();

$editId = $_GET['id'] ?? '';
$p = $editId ? $store->find($editId) : null;
$isEdit = $p !== null;

if (!$p) {
    $p = [
        'id' => '', 'name' => '', 'company_name' => '', 'tipo' => 'revendedor', 'email' => '',
        'phone' => '', 'whatsapp' => '', 'website' => '', 'city' => '', 'state' => '',
        'description' => '', 'logo_image' => '', 'status' => 'active', 'featured' => false,
    ];
}

$pageTitle = $isEdit ? 'Editar Parceiro' : 'Novo Parceiro';
include __DIR__ . '/_header.php';
?>
<div class="page-head">
    <div>
        <h1><i class="fas fa-handshake"></i> <?php echo $isEdit ? 'Editar Parceiro' : 'Novo Parceiro'; ?></h1>
        <p><?php echo $isEdit ? 'Altere as informações e salve.' : 'Cadastre um novo parceiro da rede.'; ?></p>
    </div>
    <a href="parceiros.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Voltar</a>
</div>

<div class="card">
    <form method="POST" action="parceiro_salvar.php" enctype="multipart/form-data">
        <input type="hidden" name="csrf" value="<?php echo htmlspecialchars($csrf); ?>">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($p['id']); ?>">
        <input type="hidden" name="logo_atual" value="<?php echo htmlspecialchars($p['logo_image']); ?>">

        <div class="form-row">
            <div class="form-group">
                <label for="name">Nome do parceiro *</label>
                <input type="text" id="name" name="name" required value="<?php echo htmlspecialchars($p['name']); ?>">
            </div>
            <div class="form-group">
                <label for="tipo">Tipo de parceiro</label>
                <select id="tipo" name="tipo">
                    <?php foreach (PartnerStore::$tipos as $val => $label): ?>
                        <option value="<?php echo $val; ?>" <?php echo ($p['tipo'] ?? '') === $val ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($label); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="company_name">Razão social / Empresa</label>
            <input type="text" id="company_name" name="company_name" value="<?php echo htmlspecialchars($p['company_name']); ?>">
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="text" id="email" name="email" value="<?php echo htmlspecialchars($p['email']); ?>">
            </div>
            <div class="form-group">
                <label for="phone">Telefone</label>
                <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($p['phone']); ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="whatsapp">WhatsApp (somente números)</label>
                <input type="text" id="whatsapp" name="whatsapp" value="<?php echo htmlspecialchars($p['whatsapp']); ?>" placeholder="5567999999999">
            </div>
            <div class="form-group">
                <label for="website">Site (URL)</label>
                <input type="text" id="website" name="website" value="<?php echo htmlspecialchars($p['website']); ?>" placeholder="https://...">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="city">Cidade</label>
                <input type="text" id="city" name="city" value="<?php echo htmlspecialchars($p['city']); ?>">
            </div>
            <div class="form-group">
                <label for="state">UF</label>
                <input type="text" id="state" name="state" maxlength="2" value="<?php echo htmlspecialchars($p['state']); ?>" placeholder="MS">
            </div>
        </div>

        <div class="form-group">
            <label for="description">Descrição</label>
            <textarea id="description" name="description"><?php echo htmlspecialchars($p['description']); ?></textarea>
        </div>

        <div class="form-group">
            <label for="logo">Logo do parceiro</label>
            <input type="file" id="logo" name="logo" accept="image/jpeg,image/png,image/webp">
            <div class="form-hint">JPG, PNG ou WEBP (máx. 3 MB). Deixe em branco para manter o atual.</div>
        </div>

        <?php if (!empty($p['logo_image'])): ?>
            <div class="form-group">
                <label>Logo atual</label><br>
                <img src="../uploads/parceiros/<?php echo htmlspecialchars($p['logo_image']); ?>" alt="" style="max-width:160px;border-radius:8px;border:1px solid #e2e8f0;">
            </div>
        <?php endif; ?>

        <div class="form-group">
            <label class="checkbox-inline"><input type="checkbox" name="featured" value="1" <?php echo !empty($p['featured']) ? 'checked' : ''; ?>> Parceiro destaque</label>
        </div>
        <div class="form-group">
            <label class="checkbox-inline"><input type="checkbox" name="status" value="active" <?php echo ($p['status'] ?? 'active') === 'active' ? 'checked' : ''; ?>> Ativo (visível no site)</label>
        </div>

        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Salvar parceiro</button>
    </form>
</div>

</div><!-- /admin-wrap -->
</body>
</html>
