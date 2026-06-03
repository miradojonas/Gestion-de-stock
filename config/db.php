<?php

declare(strict_types=1);

require_once __DIR__ . '/app.php';

function db(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $socket = '/opt/lampp/var/mysql/mysql.sock';
    $database = 'gestion_stock';
    $username = 'root';
    $password = '';
    $charset = 'utf8mb4';

    $dsn = "mysql:unix_socket={$socket};dbname={$database};charset={$charset}";

    try {
        $pdo = new PDO($dsn, $username, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    } catch (PDOException $exception) {
        throw new RuntimeException(
            'Connexion MySQL impossible. Vérifie le host, l’utilisateur, le mot de passe et l’import de la base.',
            0,
            $exception
        );
    }

    return $pdo;
}
