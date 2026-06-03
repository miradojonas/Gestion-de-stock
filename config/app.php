<?php

declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('APP_NAME', 'Gestion Stock');

define('APP_DEBUG', true);

function base_route(string $route, array $params = []): string
{
    $query = ['route' => $route] + $params;
    return '?' . http_build_query($query);
}

function redirect_to(string $route, array $params = []): void
{
    header('Location: ' . base_route($route, $params));
    exit;
}

function e(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function flash(string $key, ?string $message = null): ?string
{
    if ($message !== null) {
        $_SESSION['flash'][$key] = $message;
        return null;
    }

    if (!isset($_SESSION['flash'][$key])) {
        return null;
    }

    $value = $_SESSION['flash'][$key];
    unset($_SESSION['flash'][$key]);
    return $value;
}

function render(string $view, array $data = []): void
{
    extract($data, EXTR_SKIP);
    require __DIR__ . '/../views/partials/header.php';
    require __DIR__ . '/../views/partials/navbar.php';
    require __DIR__ . '/../views/' . $view . '.php';
    require __DIR__ . '/../views/partials/footer.php';
}
