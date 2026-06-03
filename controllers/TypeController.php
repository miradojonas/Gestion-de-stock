<?php

declare(strict_types=1);

class TypeController
{
    public function index(): void
    {
        require_role('ADMIN');

        render('types/index', [
            'types' => (new Type())->all(),
            'categories' => (new Category())->all(),
        ]);
    }

    public function store(): void
    {
        require_role('ADMIN');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect_to('type/index');
        }

        $name = trim($_POST['name'] ?? '');
        $categoryId = (int) ($_POST['category_id'] ?? 0);

        if ($name === '' || $categoryId <= 0) {
            flash('error', 'Nom et catégorie sont obligatoires.');
            redirect_to('type/index');
        }

        (new Type())->create($name, $categoryId);
        flash('success', 'Type ajouté.');
        redirect_to('type/index');
    }

    public function delete(): void
    {
        require_role('ADMIN');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect_to('type/index');
        }

        (new Type())->delete((int) ($_POST['id'] ?? 0));
        flash('success', 'Type supprimé.');
        redirect_to('type/index');
    }
}
