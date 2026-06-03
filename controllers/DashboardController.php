<?php

declare(strict_types=1);

class DashboardController
{
    public function index(): void
    {
        require_login();

        $productModel = new Product();
        $movementModel = new StockMovement();

        $data = [
            'totalProducts' => $productModel->countAll(),
            'lowStockProducts' => $productModel->lowStock(),
            'todayIn' => $movementModel->countTodayByType('IN'),
            'todayOut' => $movementModel->countTodayByType('OUT'),
            'recentMovements' => $movementModel->recent(8),
        ];

        render('dashboard/index', $data);
    }
}
