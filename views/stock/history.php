<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Historique des mouvements</h1>
        <p class="text-muted mb-0">Entrées et sorties enregistrées avec utilisateur et motif.</p>
    </div>
</div>

<div class="card card-shadow">
    <div class="table-responsive">
        <table class="table mb-0 align-middle">
            <thead>
            <tr>
                <th>Date</th>
                <th>Produit</th>
                <th>Type</th>
                <th>Quantité</th>
                <th>Utilisateur</th>
                <th>Motif</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($movements as $movement): ?>
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
                    <td><?= e($movement['motif'] ?? '-') ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>