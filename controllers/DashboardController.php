<?php

declare(strict_types=1);

require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/StockMovement.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../config/auth.php';

class DashboardController
{
    public function index(): void
    {
        require_login();

        $productModel = new Product();
        $movementModel = new StockMovement();

        $user = current_user();

        $data = [
            'totalProducts' => $productModel->countAll(),
            'lowStockProducts' => $productModel->lowStock(),
            'todayIn' => $movementModel->countTodayByType('IN'),
            'todayOut' => $movementModel->countTodayByType('OUT'),
            'recentMovements' => $movementModel->recent(8),
            // Totaux des ventes
            'totalSalesAll' => $movementModel->totalSalesAll(),
            'totalSalesUser' => $user ? $movementModel->totalSalesByUser((int) $user['id']) : 0.0,
            // Nombre d'opérations de ventes
            'totalSalesCountAll' => $movementModel->countSalesAll(),
            'totalSalesCountUser' => $user ? $movementModel->countSalesByUser((int) $user['id']) : 0,
        ];

        render('dashboard/index', $data);
    }
}
