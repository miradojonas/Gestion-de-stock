<?php

declare(strict_types=1);

class AuthController
{
    public function loginForm(): void
    {
        if (is_logged_in()) {
            redirect_to('dashboard/index');
        }

        render('auth/login');
    }

    public function authenticate(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect_to('auth/loginForm');
        }

        $username = trim($_POST['username'] ?? '');
        $password = (string) ($_POST['password'] ?? '');

        if ($username === '' || $password === '') {
            flash('error', 'Veuillez remplir tous les champs.');
            redirect_to('auth/loginForm');
        }

        $userModel = new User();
        $user = $userModel->findByUsername($username);

        if (!$user || !password_verify($password, $user['password_hash'])) {
            flash('error', 'Identifiants invalides.');
            redirect_to('auth/loginForm');
        }

        login_user($user);
        flash('success', 'Connexion réussie.');
        redirect_to('dashboard/index');
    }

    public function logout(): void
    {
        logout_user();
        flash('success', 'Déconnexion effectuée.');
        redirect_to('auth/loginForm');
    }
}
