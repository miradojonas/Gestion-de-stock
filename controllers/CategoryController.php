<?php

declare(strict_types=1);

class CategoryController
{
    public function index(): void
    {
        require_role('ADMIN');

        render('categories/index', [
            'categories' => (new Category())->all(),
        ]);
    }

    public function store(): void
    {
        require_role('ADMIN');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect_to('category/index');
        }

        $name = trim($_POST['name'] ?? '');
        if ($name === '') {
            flash('error', 'Le nom est obligatoire.');
            redirect_to('category/index');
        }

        (new Category())->create($name);
        flash('success', 'Catégorie ajoutée.');
        redirect_to('category/index');
    }

    public function delete(): void
    {
        require_role('ADMIN');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect_to('category/index');
        }

        (new Category())->delete((int) ($_POST['id'] ?? 0));
        flash('success', 'Catégorie supprimée.');
        redirect_to('category/index');
    }
}
