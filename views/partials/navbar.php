<?php $user = current_user(); ?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark rounded-3 shadow-sm mb-4 px-3 py-3">
    <a class="navbar-brand text-white text-decoration-none" href="<?= e(base_route('dashboard/index')) ?>"><?= e(APP_NAME) ?></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="mainNav">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <?php if ($user): ?>
                <li class="nav-item"><a class="nav-link" href="<?= e(base_route('dashboard/index')) ?>">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= e(base_route('product/index')) ?>">Produits</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= e(base_route('stock/history')) ?>">Historique</a></li>
                <?php if ($user['role'] === 'ADMIN'): ?>
                    <li class="nav-item"><a class="nav-link" href="<?= e(base_route('category/index')) ?>">Catégories</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= e(base_route('type/index')) ?>">Types</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= e(base_route('user/index')) ?>">Utilisateurs</a></li>
                <?php endif; ?>
            <?php endif; ?>
        </ul>
        <div class="d-flex align-items-center gap-2">
            <?php if ($user): ?>
                <span class="badge text-bg-light badge-role"><?= e($user['role']) ?></span>
                <span class="text-white small"><?= e($user['username']) ?></span>
                <a class="btn btn-outline-light btn-sm" href="<?= e(base_route('auth/logout')) ?>">Déconnexion</a>
            <?php else: ?>
                <a class="btn btn-outline-light btn-sm" href="<?= e(base_route('auth/loginForm')) ?>">Connexion</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<?php if ($success = flash('success')): ?>
    <div class="alert alert-success"> <?= e($success) ?> </div>
<?php endif; ?>
<?php if ($error = flash('error')): ?>
    <div class="alert alert-danger"> <?= e($error) ?> </div>
<?php endif; ?>