<div class="row justify-content-center mt-5">
    <div class="col-12 col-md-6 col-lg-4">
        <div class="card card-shadow">
            <div class="card-body p-4">
                <h1 class="h4 mb-3">Connexion</h1>
                <p class="text-muted small mb-4">Accédez à l’application de gestion de stock.</p>
                <form method="post" action="<?= e(base_route('auth/authenticate')) ?>">
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mot de passe</label>
                        <div class="input-group">
                            <input type="password" name="password" id="loginPassword" class="form-control" required>
                            <button class="btn btn-outline-secondary" type="button" id="togglePassword" aria-label="Afficher le mot de passe">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    <button class="btn btn-dark w-100">Se connecter</button>
                </form>
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        const toggle = document.getElementById('togglePassword');
                        const password = document.getElementById('loginPassword');

                        if (toggle && password) {
                            toggle.addEventListener('click', function () {
                                if (password.type === 'password') {
                                    password.type = 'text';
                                    toggle.innerHTML = '<i class="fas fa-eye-slash"></i>';
                                    toggle.setAttribute('aria-label', 'Masquer le mot de passe');
                                } else {
                                    password.type = 'password';
                                    toggle.innerHTML = '<i class="fas fa-eye"></i>';
                                    toggle.setAttribute('aria-label', 'Afficher le mot de passe');
                                }
                            });
                        }
                    });
                </script>
            </div>
        </div>
    </div>
</div>