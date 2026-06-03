<div class="row g-4">
    <div class="col-lg-4">
        <div class="card card-shadow">
            <div class="card-body p-4">
                <h1 class="h4 mb-3">Catégories</h1>
                <form method="post" action="<?= e(base_route('category/store')) ?>" class="mb-4">
                    <label class="form-label">Nouvelle catégorie</label>
                    <input type="text" name="name" class="form-control mb-3" required>
                    <button class="btn btn-dark w-100">Ajouter</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card card-shadow">
            <div class="table-responsive">
                <table class="table mb-0 align-middle">
                    <thead>
                    <tr>
                        <th>Nom</th>
                        <th class="text-end">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($categories as $category): ?>
                        <tr>
                            <td><?= e($category['name']) ?></td>
                            <td class="text-end">
                                <form class="d-inline" method="post" action="<?= e(base_route('category/delete')) ?>" onsubmit="return confirm('Supprimer cette catégorie ?');">
                                    <input type="hidden" name="id" value="<?= e($category['id']) ?>">
                                    <button class="btn btn-sm btn-outline-danger">Supprimer</button>
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