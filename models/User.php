<?php

declare(strict_types=1);

require_once __DIR__ . '/BaseModel.php';

class User extends BaseModel
{
    public function findByUsername(string $username): ?array
    {
        return $this->fetchOne('SELECT * FROM users WHERE username = :username LIMIT 1', ['username' => $username]);
    }

    public function findById(int $id): ?array
    {
        return $this->fetchOne('SELECT * FROM users WHERE id = :id LIMIT 1', ['id' => $id]);
    }

    public function all(): array
    {
        return $this->fetchAll('SELECT id, username, role FROM users ORDER BY username ASC');
    }
}
