<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card card-shadow">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h4 mb-0">Sortie de stock</h1>
                    <a class="btn btn-outline-secondary btn-sm" href="<?= e(base_route('stock/history')) ?>">Historique</a>
                </div>
                <form method="post" action="<?= e(base_route('stock/storeOut')) ?>">
                    <div class="mb-3">
                        <label class="form-label">Produit</label>
                        <select name="product_id" class="form-select" required>
                            <option value="">-- Sélectionner --</option>
                            <?php foreach ($products as $product): ?>
                                <option value="<?= e($product['id']) ?>"><?= e($product['libelle']) ?> (stock: <?= e($product['quantite']) ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Quantité</label>
                        <input type="number" name="quantity" class="form-control" min="1" required>
                    </div>
                    <button class="btn btn-danger">Enregistrer la sortie</button>
                </form>
            </div>
        </div>
    </div>
</div>