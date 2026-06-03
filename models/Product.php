<?php

declare(strict_types=1);

require_once __DIR__ . '/BaseModel.php';

class Product extends BaseModel
{
    public function all(): array
    {
        return $this->fetchAll(
            'SELECT p.*, c.name AS category_name, t.name AS type_name
             FROM products p
             LEFT JOIN categories c ON c.id = p.category_id
             LEFT JOIN types t ON t.id = p.type_id
             ORDER BY p.libelle ASC'
        );
    }

    public function allActive(): array
    {
        return $this->fetchAll(
            'SELECT p.*, c.name AS category_name, t.name AS type_name
             FROM products p
             LEFT JOIN categories c ON c.id = p.category_id
             LEFT JOIN types t ON t.id = p.type_id
             WHERE p.actif = 1
             ORDER BY p.libelle ASC'
        );
    }

    public function find(int $id): ?array
    {
        return $this->fetchOne(
            'SELECT p.*, c.name AS category_name, t.name AS type_name
             FROM products p
             LEFT JOIN categories c ON c.id = p.category_id
             LEFT JOIN types t ON t.id = p.type_id
             WHERE p.id = :id
             LIMIT 1',
            ['id' => $id]
        );
    }

    public function countAll(): int
    {
        return (int) $this->pdo->query('SELECT COUNT(*) FROM products')->fetchColumn();
    }

    public function lowStock(): array
    {
        return $this->fetchAll(
            'SELECT p.*, c.name AS category_name, t.name AS type_name
             FROM products p
             LEFT JOIN categories c ON c.id = p.category_id
             LEFT JOIN types t ON t.id = p.type_id
             WHERE p.actif = 1 AND p.quantite <= p.stock_min
             ORDER BY p.quantite ASC, p.libelle ASC'
        );
    }

    public function create(array $data): bool
    {
        return $this->execute(
            'INSERT INTO products
            (libelle, prix_achat, prix_vente, quantite, stock_min, image_path, category_id, type_id, actif)
            VALUES
            (:libelle, :prix_achat, :prix_vente, :quantite, :stock_min, :image_path, :category_id, :type_id, :actif)',
            $data
        );
    }

    public function update(int $id, array $data): bool
    {
        $data['id'] = $id;

        return $this->execute(
            'UPDATE products SET
                libelle = :libelle,
                prix_achat = :prix_achat,
                prix_vente = :prix_vente,
                quantite = :quantite,
                stock_min = :stock_min,
                image_path = :image_path,
                category_id = :category_id,
                type_id = :type_id,
                actif = :actif
             WHERE id = :id',
            $data
        );
    }

    public function deactivate(int $id): bool
    {
        return $this->execute('UPDATE products SET actif = 0 WHERE id = :id', ['id' => $id]);
    }

    public function adjustQuantity(int $id, int $quantity): bool
    {
        return $this->execute('UPDATE products SET quantite = quantite + :quantity WHERE id = :id', [
            'quantity' => $quantity,
            'id' => $id,
        ]);
    }

    public function setQuantity(int $id, int $quantity): bool
    {
        return $this->execute('UPDATE products SET quantite = :quantity WHERE id = :id', [
            'quantity' => $quantity,
            'id' => $id,
        ]);
    }
}
