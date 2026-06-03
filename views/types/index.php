<div class="row g-4">
    <div class="col-lg-4">
        <div class="card card-shadow">
            <div class="card-body p-4">
                <h1 class="h4 mb-3">Types</h1>
                <form method="post" action="<?= e(base_route('type/store')) ?>" class="mb-4">
                    <div class="mb-3">
                        <label class="form-label">Nom du type</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Catégorie</label>
                        <select name="category_id" class="form-select" required>
                            <option value="">-- Sélectionner --</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= e($category['id']) ?>"><?= e($category['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
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
                        <th>Catégorie</th>
                        <th>Type</th>
                        <th class="text-end">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($types as $type): ?>
                        <tr>
                            <td><?= e($type['category_name']) ?></td>
                            <td><?= e($type['name']) ?></td>
                            <td class="text-end">
                                <form class="d-inline" method="post" action="<?= e(base_route('type/delete')) ?>" onsubmit="return confirm('Supprimer ce type ?');">
                                    <input type="hidden" name="id" value="<?= e($type['id']) ?>">
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