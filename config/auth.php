<?php

declare(strict_types=1);

require_once __DIR__ . '/app.php';

function current_user(): ?array
{
    return $_SESSION['user'] ?? null;
}

function is_logged_in(): bool
{
    return current_user() !== null;
}

function login_user(array $user): void
{
    $_SESSION['user'] = [
        'id' => (int) $user['id'],
        'username' => $user['username'],
        'role' => $user['role'],
    ];
}

function logout_user(): void
{
    unset($_SESSION['user']);
}

function require_login(): void
{
    if (!is_logged_in()) {
        redirect_to('auth/loginForm');
    }
}

function require_role(array|string $roles): void
{
    require_login();

    $roles = is_array($roles) ? $roles : [$roles];
    $user = current_user();

    if ($user === null || !in_array($user['role'], $roles, true)) {
        flash('error', 'Accès refusé.');
        redirect_to('dashboard/index');
    }
}
