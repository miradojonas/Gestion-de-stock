<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Dashboard</h1>
        <p class="text-muted mb-0">Vue d’ensemble du stock et des mouvements récents.</p>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card card-shadow metric-card h-100">
            <div class="card-body">
                <div class="text-muted small">Total produits</div>
                <div class="display-6 fw-semibold"><?= e($totalProducts) ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-shadow metric-card h-100">
            <div class="card-body">
                <div class="text-muted small">Entrées du jour</div>
                <div class="display-6 fw-semibold text-success"><?= e($todayIn) ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-shadow metric-card h-100">
            <div class="card-body">
                <div class="text-muted small">Sorties du jour</div>
                <div class="display-6 fw-semibold text-danger"><?= e($todayOut) ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-shadow metric-card h-100">
            <div class="card-body">
                <div class="text-muted small">Stock faible</div>
                <div class="display-6 fw-semibold text-warning"><?= e(count($lowStockProducts)) ?></div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-5">
        <div class="card card-shadow h-100">
            <div class="card-header bg-white">
                <strong>Produits en stock faible</strong>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0 align-middle">
                        <thead>
                        <tr>
                            <th>Produit</th>
                            <th>Qté</th>
                            <th>Min</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (!$lowStockProducts): ?>
                            <tr><td colspan="3" class="text-center text-muted py-4">Aucun produit en stock faible.</td></tr>
                        <?php else: ?>
                            <?php foreach ($lowStockProducts as $product): ?>
                                <tr>
                                    <td><?= e($product['libelle']) ?></td>
                                    <td><?= e($product['quantite']) ?></td>
                                    <td><?= e($product['stock_min']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card card-shadow h-100">
            <div class="card-header bg-white">
                <strong>Mouvements récents</strong>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0 align-middle">
                        <thead>
                        <tr>
                            <th>Date</th>
                            <th>Produit</th>
                            <th>Type</th>
                            <th>Qté</th>
                            <th>Utilisateur</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($recentMovements as $movement): ?>
                            <tr>
                                <td><?= e($movement['date']) ?></td>
                                <td><?= e($movement['product_name']) ?></td>
                                <td>
                                    <span class="badge <?= $movement['movement_type'] === 'IN' ? 'text-bg-success' : 'text-bg-danger' ?>">
                                        <?= e($movement['movement_type']) ?>
                                    </span>
                                </td>
                                <td><?= e($movement['quantity']) ?></td>
                                <td><?= e($movement['username']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>