<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Produits</h1>
        <p class="text-muted mb-0">Liste des produits et état du stock.</p>
    </div>
    <?php if (current_user()['role'] === 'ADMIN'): ?>
        <a class="btn btn-dark" href="<?= e(base_route('product/create')) ?>">Nouveau produit</a>
    <?php endif; ?>
</div>

<div class="card card-shadow">
    <div class="card-body">
        <div class="mb-3">
            <label for="productSearch" class="form-label">Rechercher un produit</label>
            <input type="search" id="productSearch" class="form-control" placeholder="Recherche par nom, catégorie ou type...">
        </div>
    </div>
    <div class="table-responsive">
        <table class="table mb-0 align-middle" id="productTable">
            <thead>
            <tr>
                <th>Image</th>
                <th>Libellé</th>
                <th>Catégorie</th>
                <th>Type</th>
                <th>PV</th>
                <th>Qté</th>
                <th>Min</th>
                <th>Statut</th>
                <?php if (current_user()['role'] === 'ADMIN'): ?>
                    <th class="text-end">Actions</th>
                <?php endif; ?>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td>
                        <?php if (!empty($product['image_path'])): ?>
                            <img src="<?= e($product['image_path']) ?>" alt="<?= e($product['libelle']) ?>" style="max-width: 60px; max-height: 60px; object-fit: cover; border-radius: 4px;">
                        <?php else: ?>
                            <span class="text-muted small">-</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if (current_user()['role'] === 'VENDEUR'): ?>
                            <a href="#" class="product-sale-link text-decoration-none" data-bs-toggle="modal" data-bs-target="#saleModal"
                               data-product-id="<?= e($product['id']) ?>"
                               data-product-name="<?= e($product['libelle']) ?>"
                               data-product-stock="<?= e($product['quantite']) ?>">
                                <?= e($product['libelle']) ?>
                            </a>
                        <?php else: ?>
                            <?= e($product['libelle']) ?>
                        <?php endif; ?>
                    </td>
                    <td><?= e($product['category_name'] ?? '-') ?></td>
                    <td><?= e($product['type_name'] ?? '-') ?></td>
                    <td><?= e(number_format((float) $product['prix_vente'], 2, ',', ' ')) ?></td>
                    <td>
                        <span class="badge <?= ((int) $product['quantite'] <= (int) $product['stock_min']) ? 'text-bg-warning' : 'text-bg-success' ?>">
                            <?= e($product['quantite']) ?>
                        </span>
                    </td>
                    <td><?= e($product['stock_min']) ?></td>
                    <td>
                        <span class="badge <?= (int) $product['actif'] === 1 ? 'text-bg-primary' : 'text-bg-secondary' ?>">
                            <?= (int) $product['actif'] === 1 ? 'Actif' : 'Inactif' ?>
                        </span>
                    </td>
                    <?php if (current_user()['role'] === 'ADMIN'): ?>
                        <td class="text-end">
                            <a class="btn btn-sm btn-outline-secondary" href="<?= e(base_route('product/edit', ['id' => $product['id']])) ?>">Modifier</a>
                            <form class="d-inline" method="post" action="<?= e(base_route('product/delete')) ?>" onsubmit="return confirm('Désactiver ce produit ?');">
                                <input type="hidden" name="id" value="<?= e($product['id']) ?>">
                                <button class="btn btn-sm btn-outline-danger">Désactiver</button>
                            </form>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('productSearch');
        const table = document.getElementById('productTable');

        if (!searchInput || !table) {
            return;
        }

        searchInput.addEventListener('input', function () {
            const filter = searchInput.value.trim().toLowerCase();
            const rows = table.querySelectorAll('tbody tr');

            rows.forEach(function (row) {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(filter) ? '' : 'none';
            });
        });
    });
</script>

<div class="modal fade" id="saleModal" tabindex="-1" aria-labelledby="saleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="saleModalLabel">Vendre le produit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <form id="saleForm" method="post" action="<?= e(base_route('stock/storeOut')) ?>">
                <input type="hidden" name="product_id" id="saleProductId" value="">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Produit</label>
                        <div class="form-control-plaintext fw-semibold" id="saleProductName"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Stock disponible</label>
                        <div class="form-control-plaintext text-success" id="saleProductStock"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Quantité</label>
                        <div class="input-group">
                            <button type="button" class="btn btn-outline-secondary" id="saleMinus">-</button>
                            <input type="number" name="quantity" id="saleQuantity" class="form-control text-center" value="1" min="1" step="1" required>
                            <button type="button" class="btn btn-outline-secondary" id="salePlus">+</button>
                        </div>
                    </div>
                    <div class="alert alert-warning d-none" id="saleAlert"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-danger" id="saleSubmit">Vendre</button>
                </div>
            </form>
        </div>
    </div>
</div>