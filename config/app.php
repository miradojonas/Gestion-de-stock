<?php

declare(strict_types=1);

// Configuration des sessions
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.gc_maxlifetime', '1800'); // 30 minutes
    session_start();
}

// Timeout d'inactivité (en secondes)
const SESSION_TIMEOUT = 1800; // 30 minutes

// Vérifier l'expiration de la session
if (isset($_SESSION['user'])) {
    if (!isset($_SESSION['last_activity'])) {
        $_SESSION['last_activity'] = time();
    }
    
    if (time() - $_SESSION['last_activity'] > SESSION_TIMEOUT) {
        // Session expirée
        session_destroy();
        session_start();
    } else {
        $_SESSION['last_activity'] = time();
    }
}

define('APP_NAME', 'Gestion Stock');

define('APP_DEBUG', false);

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
