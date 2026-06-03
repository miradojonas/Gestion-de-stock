<?php $isEdit = !empty($product); ?>
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card card-shadow">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h4 mb-0"><?= $isEdit ? 'Modifier le produit' : 'Nouveau produit' ?></h1>
                    <a href="<?= e(base_route('product/index')) ?>" class="btn btn-outline-secondary btn-sm">Retour</a>
                </div>
                <form method="post" action="<?= e($formAction) ?>" enctype="multipart/form-data">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Libellé</label>
                            <input type="text" name="libelle" class="form-control" required value="<?= e($product['libelle'] ?? '') ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Prix de vente</label>
                            <input type="number" step="0.01" name="prix_vente" class="form-control" required value="<?= e($product['prix_vente'] ?? '0') ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Quantité</label>
                            <input type="number" name="quantite" class="form-control" required value="<?= e($product['quantite'] ?? '0') ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Stock minimum</label>
                            <input type="number" name="stock_min" class="form-control" required value="<?= e($product['stock_min'] ?? '0') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Image</label>
                            <input type="file" name="image_file" class="form-control" accept="image/*">
                            <?php if (!empty($product['image_path'])): ?>
                                <input type="hidden" name="existing_image_path" value="<?= e($product['image_path']) ?>">
                                <small class="text-muted">Image actuelle : <?= e(basename($product['image_path'])) ?></small>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Type</label>
                            <select name="type_id" class="form-select">
                                <option value="">-- Sélectionner --</option>
                                <?php foreach ($types as $type): ?>
                                    <option value="<?= e($type['id']) ?>" <?= (int)($product['type_id'] ?? 0) === (int) $type['id'] ? 'selected' : '' ?>>
                                        <?= e($type['category_name'] . ' - ' . $type['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12">
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox" name="actif" id="actif" <?= (int)($product['actif'] ?? 1) === 1 ? 'checked' : '' ?>>
                                <label class="form-check-label" for="actif">Produit actif</label>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex gap-2 mt-4">
                        <button class="btn btn-dark"><?= $isEdit ? 'Mettre à jour' : 'Enregistrer' ?></button>
                        <a href="<?= e(base_route('product/index')) ?>" class="btn btn-outline-secondary">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>