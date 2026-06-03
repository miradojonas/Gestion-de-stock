<div class="row justify-content-center mt-5">
    <div class="col-lg-8">
        <div class="card card-shadow border-0">
            <div class="card-body p-4 p-md-5">
                <h1 class="h4 mb-3 text-danger">Connexion à la base impossible</h1>
                <p class="mb-3">
                    L’application ne peut pas démarrer tant que MySQL n’est pas accessible avec les bons identifiants.
                </p>
                <div class="alert alert-warning mb-4">
                    <?= e($errorMessage ?? 'Erreur de connexion.') ?>
                </div>
                <ol class="mb-0">
                    <li>Importe <a href="database.sql">database.sql</a> dans MySQL.</li>
                    <li>Adapte les identifiants dans <a href="config/db.php">config/db.php</a> si ton serveur n’autorise pas root sans mot de passe.</li>
                    <li>Recharge ensuite la page de connexion.</li>
                </ol>
            </div>
        </div>
    </div>
</div>