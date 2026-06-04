<div class="row g-4">
    <div class="col-lg-4">
        <div class="card card-shadow">
            <div class="card-body p-4">
                <h1 class="h4 mb-3">Créer un compte vendeur</h1>
                <form method="post" action="<?= e(base_route('user/store')) ?>" class="mb-4">
                    <label class="form-label">Nom d’utilisateur</label>
                    <input type="text" name="username" class="form-control mb-3" required>

                    <label class="form-label">Adresse e-mail</label>
                    <input type="email" name="email" class="form-control mb-3" required>

                    <label class="form-label">Mot de passe</label>
                    <input type="password" name="password" class="form-control mb-3" required>

                    <label class="form-label">Rôle</label>
                    <select name="role" class="form-select mb-3">
                        <option value="VENDEUR">Vendeur</option>
                        <option value="ADMIN">Admin</option>
                    </select>

                    <button class="btn btn-dark w-100">Créer le vendeur</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card card-shadow">
            <div class="card-body p-4">
                <h1 class="h4 mb-3">Utilisateurs</h1>
                <div class="table-responsive">
                    <table class="table mb-0 align-middle">
                        <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Rôle</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= e($user['username']) ?></td>
                                <td><?= e($user['email']) ?></td>
                                <td>
                                    <span class="badge <?= $user['role'] === 'ADMIN' ? 'text-bg-primary' : 'text-bg-secondary' ?>">
                                        <?= e($user['role']) ?>
                                    </span>
                                </td>
                                <td>
                                    <form method="post" action="<?= e(base_route('user/destroy')) ?>" onsubmit="return confirm('Supprimer cet utilisateur ?');">
                                        <input type="hidden" name="id" value="<?= e($user['id']) ?>">
                                        <button class="btn btn-sm btn-danger">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
