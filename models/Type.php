<?php

declare(strict_types=1);

require_once __DIR__ . '/BaseModel.php';

class Type extends BaseModel
{
    public function all(): array
    {
        return $this->fetchAll(
            'SELECT t.*, c.name AS category_name
             FROM types t
             INNER JOIN categories c ON c.id = t.category_id
             ORDER BY c.name ASC, t.name ASC'
        );
    }

    public function find(int $id): ?array
    {
        return $this->fetchOne('SELECT * FROM types WHERE id = :id LIMIT 1', ['id' => $id]);
    }

    public function create(string $name, int $categoryId): bool
    {
        return $this->execute(
            'INSERT INTO types (name, category_id) VALUES (:name, :category_id)',
            ['name' => $name, 'category_id' => $categoryId]
        );
    }

    public function delete(int $id): bool
    {
        return $this->execute('DELETE FROM types WHERE id = :id', ['id' => $id]);
    }
}
