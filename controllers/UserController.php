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

        $role = strtoupper(trim($_POST['role'] ?? 'VENDEUR'));

        if (!in_array($role, ['ADMIN', 'VENDEUR'], true)) {
            $role = 'VENDEUR';
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
            'role' => $role,
        ]);

        flash('success', 'Compte créé avec succès.');
        redirect_to('user/index');
    }

    public function destroy(): void
    {
        require_role('ADMIN');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect_to('user/index');
        }

        $id = (int) ($_POST['id'] ?? 0);
        if ($id <= 0) {
            flash('error', 'Utilisateur invalide.');
            redirect_to('user/index');
        }

        $userModel = new User();
        $existing = $userModel->findById($id);
        if (!$existing) {
            flash('error', 'Utilisateur introuvable.');
            redirect_to('user/index');
        }

        // Prevent deleting the currently logged user
        if (current_user()['id'] === $existing['id']) {
            flash('error', 'Vous ne pouvez pas supprimer votre propre compte.');
            redirect_to('user/index');
        }

        $userModel->delete($id);
        flash('success', 'Utilisateur supprimé.');
        redirect_to('user/index');
    }
}
