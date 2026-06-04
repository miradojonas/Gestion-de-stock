<?php

declare(strict_types=1);

class UserController
{
    public function index(): void
    {
        require_role('ADMIN');

        render('users/index', [
            'users' => (new User())->all(),
        ]);
    }

    public function store(): void
    {
        require_role('ADMIN');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect_to('user/index');
        }

        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($username === '' || $email === '' || $password === '') {
            flash('error', 'Veuillez remplir tous les champs du formulaire.');
            redirect_to('user/index');
        }

        $userModel = new User();

        if ($userModel->findByUsername($username)) {
            flash('error', 'Ce nom d’utilisateur est déjà utilisé.');
            redirect_to('user/index');
        }

        if ($userModel->findByEmail($email)) {
            flash('error', 'Cette adresse e-mail est déjà utilisée.');
            redirect_to('user/index');
        }

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        if ($passwordHash === false) {
            flash('error', 'Impossible de générer le mot de passe.');
            redirect_to('user/index');
        }

        $userModel->create([
            'username' => $username,
            'email' => $email,
            'password_hash' => $passwordHash,
            'role' => 'VENDEUR',
        ]);

        flash('success', 'Compte vendeur créé avec succès.');
        redirect_to('user/index');
    }
}
