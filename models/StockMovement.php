<?php

declare(strict_types=1);

require_once __DIR__ . '/BaseModel.php';

class StockMovement extends BaseModel
{
    public function recent(int $limit = 5): array
    {
        $limit = max(1, $limit);

        return $this->fetchAll(
            "SELECT m.*, p.libelle AS product_name, u.username
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
            "SELECT m.*, p.libelle AS product_name, u.username
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

    /**
     * Total ventes (montant) pour toutes les sorties (OUT) - tous vendeurs.
     * Retourne un float (0.00 si aucun mouvement).
     */
    public function totalSalesAll(): float
    {
        $row = $this->fetchOne(
            "SELECT COALESCE(SUM(m.quantity * p.prix_vente), 0) AS total
             FROM stock_movements m
             INNER JOIN products p ON p.id = m.product_id
             WHERE m.movement_type = 'OUT'"
        );

        return isset($row['total']) ? (float) $row['total'] : 0.0;
    }

    /**
     * Total ventes (montant) pour un utilisateur précis (vendeur).
     */
    public function totalSalesByUser(int $userId): float
    {
        $row = $this->fetchOne(
            "SELECT COALESCE(SUM(m.quantity * p.prix_vente), 0) AS total
             FROM stock_movements m
             INNER JOIN products p ON p.id = m.product_id
             WHERE m.movement_type = 'OUT' AND m.user_id = :user_id",
            ['user_id' => $userId]
        );

        return isset($row['total']) ? (float) $row['total'] : 0.0;
    }

    /**
     * Nombre d'opérations de vente (OUT) — tous vendeurs.
     */
    public function countSalesAll(): int
    {
        $row = $this->fetchOne(
            "SELECT COUNT(*) AS total_count
             FROM stock_movements m
             WHERE m.movement_type = 'OUT'"
        );

        return isset($row['total_count']) ? (int) $row['total_count'] : 0;
    }

    /**
     * Nombre d'opérations de vente (OUT) pour un utilisateur.
     */
    public function countSalesByUser(int $userId): int
    {
        $row = $this->fetchOne(
            "SELECT COUNT(*) AS total_count
             FROM stock_movements m
             WHERE m.movement_type = 'OUT' AND m.user_id = :user_id",
            ['user_id' => $userId]
        );

        return isset($row['total_count']) ? (int) $row['total_count'] : 0;
    }

    public function create(array $data): bool
    {
        return $this->execute(
            'INSERT INTO stock_movements (product_id, movement_type, quantity, date, user_id, motif)
             VALUES (:product_id, :movement_type, :quantity, NOW(), :user_id, :motif)',
            $data
        );
    }
}
