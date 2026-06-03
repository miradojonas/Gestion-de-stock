<?php

declare(strict_types=1);

require_once __DIR__ . '/BaseModel.php';

class StockMovement extends BaseModel
{
    public function recent(int $limit = 5): array
    {
        $limit = max(1, $limit);

        return $this->fetchAll(
            "SELECT m.*, p.libelle AS product_name, u.username, u.email,
                    IF(m.movement_type = 'OUT', p.prix_vente * m.quantity, NULL) AS total_amount
             FROM stock_movements m
             INNER JOIN products p ON p.id = m.product_id
             INNER JOIN users u ON u.id = m.user_id
             ORDER BY m.date DESC, m.id DESC
             LIMIT {$limit}"
        );
    }

    public function history(int $limit = 200): array
    {
        $limit = max(1, $limit);

        return $this->fetchAll(
            "SELECT m.*, p.libelle AS product_name, u.username, u.email,
                    IF(m.movement_type = 'OUT', p.prix_vente * m.quantity, NULL) AS total_amount
             FROM stock_movements m
             INNER JOIN products p ON p.id = m.product_id
             INNER JOIN users u ON u.id = m.user_id
             ORDER BY m.date DESC, m.id DESC
             LIMIT {$limit}"
        );
    }

    public function countTodayByType(string $movementType): int
    {
        return (int) $this->fetchOne(
            'SELECT COUNT(*) AS total
             FROM stock_movements
             WHERE movement_type = :movement_type AND DATE(date) = CURDATE()',
            ['movement_type' => $movementType]
        )['total'];
    }

    public function create(array $data): bool
    {
        return $this->execute(
            'INSERT INTO stock_movements (product_id, movement_type, quantity, date, user_id)
             VALUES (:product_id, :movement_type, :quantity, NOW(), :user_id)',
            $data
        );
    }
}
