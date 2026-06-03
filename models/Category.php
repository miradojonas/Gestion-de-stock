<?php

declare(strict_types=1);

require_once __DIR__ . '/BaseModel.php';

class Category extends BaseModel
{
    public function all(): array
    {
        return $this->fetchAll('SELECT * FROM categories ORDER BY name ASC');
    }

    public function find(int $id): ?array
    {
        return $this->fetchOne('SELECT * FROM categories WHERE id = :id LIMIT 1', ['id' => $id]);
    }

    public function create(string $name): bool
    {
        return $this->execute('INSERT INTO categories (name) VALUES (:name)', ['name' => $name]);
    }

    public function delete(int $id): bool
    {
        return $this->execute('DELETE FROM categories WHERE id = :id', ['id' => $id]);
    }
}
