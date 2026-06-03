<?php

declare(strict_types=1);

require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/config/auth.php';

spl_autoload_register(static function (string $class): void {
    $paths = [
        __DIR__ . '/controllers/' . $class . '.php',
        __DIR__ . '/models/' . $class . '.php',
    ];

    foreach ($paths as $path) {
        if (is_file($path)) {
            require_once $path;
            return;
        }
    }
});

$route = $_GET['route'] ?? (is_logged_in() ? 'dashboard/index' : 'auth/loginForm');
[$controllerName, $actionName] = array_pad(explode('/', $route, 2), 2, 'index');

$controllerClass = ucfirst($controllerName) . 'Controller';
$actionMethod = $actionName;

if (!class_exists($controllerClass)) {
    http_response_code(404);
    echo 'Page introuvable';
    exit;
}

$controller = new $controllerClass();

try {
    if (!method_exists($controller, $actionMethod)) {
        http_response_code(404);
        echo 'Action introuvable';
        exit;
    }

    $controller->$actionMethod();
} catch (Throwable $throwable) {
    http_response_code(500);
    $errorMessage = 'Impossible de charger l’application. Vérifie la configuration MySQL dans config/db.php et l’import de database.sql.';

    if (APP_DEBUG) {
        $errorMessage .= ' Détail: ' . $throwable->getMessage();
    }

    render('errors/database', [
        'errorMessage' => $errorMessage,
    ]);
}
