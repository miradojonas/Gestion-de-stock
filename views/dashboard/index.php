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

<?php $dashboardUser = current_user(); ?>
<div class="row g-3 mb-4">
    <?php if ($dashboardUser && $dashboardUser['role'] === 'ADMIN'): ?>
        <div class="col-md-6">
            <div class="card card-shadow metric-card h-100">
                <div class="card-body">
                    <div class="text-muted small">Total ventes (tous vendeurs)</div>
                    <?php $countAll = $totalSalesCountAll ?? 0; ?>
                    <?php $labelAll = $countAll === 1 ? 'vente' : 'ventes'; ?>
                    <div class="d-flex flex-column">
                        <div class="display-6 fw-semibold text-primary">
                            <?= e(number_format($totalSalesAll ?? 0, 2, ',', ' ')) ?> <small class="text-primary">Ar</small>
                        </div>
                        <div class="mt-2">
                            <span class="badge bg-light text-primary border"><?= e($countAll) ?> <?= e($labelAll) ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($dashboardUser && $dashboardUser['role'] === 'VENDEUR'): ?>
        <div class="col-md-6">
            <div class="card card-shadow metric-card h-100">
                <div class="card-body">
                    <div class="text-muted small">Mes ventes</div>
                    <?php $countUser = $totalSalesCountUser ?? 0; ?>
                    <?php $labelUser = $countUser === 1 ? 'vente' : 'ventes'; ?>
                    <div class="d-flex flex-column">
                        <div class="display-6 fw-semibold text-primary">
                            <?= e(number_format($totalSalesUser ?? 0, 2, ',', ' ')) ?> <small class="text-primary">Ar</small>
                        </div>
                        <div class="mt-2">
                            <span class="badge bg-light text-muted small border"><?= e($countUser) ?> <?= e($labelUser) ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
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