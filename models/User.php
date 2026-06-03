<?php

declare(strict_types=1);

require_once __DIR__ . '/BaseModel.php';

class User extends BaseModel
{
    public function findByUsername(string $username): ?array
    {
        return $this->fetchOne('SELECT * FROM users WHERE username = :username LIMIT 1', ['username' => $username]);
    }

    public function findByUsernameAndEmail(string $username, string $email): ?array
    {
        return $this->fetchOne(
            'SELECT * FROM users WHERE username = :username AND email = :email LIMIT 1',
            ['username' => $username, 'email' => $email]
        );
    }

    public function findByEmail(string $email): ?array
    {
        return $this->fetchOne('SELECT * FROM users WHERE email = :email LIMIT 1', ['email' => $email]);
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
