<div class="row justify-content-center mt-5">
    <div class="col-12 col-md-6 col-lg-4">
        <div class="card card-shadow">
            <div class="card-body p-4">
                <h1 class="h4 mb-3">Connexion</h1>
                <p class="text-muted small mb-4">Accédez à l’application de gestion de stock.</p>
                <form method="post" action="<?= e(base_route('auth/authenticate')) ?>">
                    <div class="mb-3">
                        <label class="form-label">Nom d'utilisateur</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mot de passe</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button class="btn btn-dark w-100">Se connecter</button>
                </form>
                <div class="mt-3 small text-muted">
                    Démo: admin / admin123 ou vendeur / seller123
                </div>
            </div>
        </div>
    </div>
</div>