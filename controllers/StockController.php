<?php

declare(strict_types=1);

class StockController
{
    public function inForm(): void
    {
        require_role('ADMIN');

        render('stock/in', [
            'products' => (new Product())->allActive(),
        ]);
    }

    public function storeIn(): void
    {
        require_role('ADMIN');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect_to('stock/inForm');
        }

        $productId = (int) ($_POST['product_id'] ?? 0);
        $quantity = (int) ($_POST['quantity'] ?? 0);
        $motif = trim($_POST['motif'] ?? '');

        if ($productId <= 0 || $quantity <= 0) {
            flash('error', 'Quantité ou produit invalide.');
            redirect_to('stock/inForm');
        }

        $pdo = db();
        $pdo->beginTransaction();

        try {
            $productModel = new Product();
            $product = $productModel->find($productId);

            if (!$product) {
                throw new RuntimeException('Produit introuvable.');
            }

            $productModel->adjustQuantity($productId, $quantity);

            (new StockMovement())->create([
                'product_id' => $productId,
                'movement_type' => 'IN',
                'quantity' => $quantity,
                'user_id' => current_user()['id'],
                'motif' => $motif !== '' ? $motif : 'Entrée de stock',
            ]);

            $pdo->commit();
            flash('success', 'Entrée de stock enregistrée.');
            redirect_to('stock/history');
        } catch (Throwable $throwable) {
            $pdo->rollBack();
            flash('error', $throwable->getMessage());
            redirect_to('stock/inForm');
        }
    }

    public function outForm(): void
    {
        require_role('VENDEUR');

        render('stock/out', [
            'products' => (new Product())->allActive(),
        ]);
    }

    public function storeOut(): void
    {
        require_role('VENDEUR');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect_to('stock/outForm');
        }

        $productId = (int) ($_POST['product_id'] ?? 0);
        $quantity = (int) ($_POST['quantity'] ?? 0);
        $motif = trim($_POST['motif'] ?? '');

        if ($productId <= 0 || $quantity <= 0) {
            flash('error', 'Quantité ou produit invalide.');
            redirect_to('stock/outForm');
        }

        $pdo = db();
        $pdo->beginTransaction();

        try {
            $productModel = new Product();
            $product = $productModel->find($productId);

            if (!$product) {
                throw new RuntimeException('Produit introuvable.');
            }

            if ((int) $product['quantite'] < $quantity) {
                throw new RuntimeException('Stock insuffisant pour cette sortie.');
            }

            $productModel->adjustQuantity($productId, -$quantity);

            (new StockMovement())->create([
                'product_id' => $productId,
                'movement_type' => 'OUT',
                'quantity' => $quantity,
                'user_id' => current_user()['id'],
                'motif' => $motif !== '' ? $motif : 'Sortie de stock',
            ]);

            $pdo->commit();
            flash('success', 'Sortie de stock enregistrée.');
            redirect_to('stock/history');
        } catch (Throwable $throwable) {
            $pdo->rollBack();
            flash('error', $throwable->getMessage());
            redirect_to('stock/outForm');
        }
    }

    public function history(): void
    {
        require_login();

        render('stock/history', [
            'movements' => (new StockMovement())->history(200),
        ]);
    }

    // API endpoint for AJAX sales from product list
    public function apiSell(): void
    {
        // Return JSON responses for JS
        header('Content-Type: application/json; charset=utf-8');

        try {
            require_role('VENDEUR');

            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                http_response_code(405);
                echo json_encode(['error' => 'Méthode non autorisée']);
                return;
            }

            $productId = (int) ($_POST['product_id'] ?? 0);
            $quantity = (int) ($_POST['quantity'] ?? 0);
            $motif = trim($_POST['motif'] ?? 'Vente');

            if ($productId <= 0 || $quantity <= 0) {
                http_response_code(400);
                echo json_encode(['error' => 'Produit ou quantité invalide']);
                return;
            }

            $pdo = db();
            $pdo->beginTransaction();

            $productModel = new Product();
            $product = $productModel->find($productId);

            if (!$product) {
                $pdo->rollBack();
                http_response_code(404);
                echo json_encode(['error' => 'Produit introuvable']);
                return;
            }

            if ((int) $product['quantite'] < $quantity) {
                $pdo->rollBack();
                http_response_code(400);
                echo json_encode(['error' => 'Stock insuffisant']);
                return;
            }

            $productModel->adjustQuantity($productId, -$quantity);

            (new StockMovement())->create([
                'product_id' => $productId,
                'movement_type' => 'OUT',
                'quantity' => $quantity,
                'user_id' => current_user()['id'],
                'motif' => $motif,
            ]);

            $pdo->commit();

            echo json_encode([
                'success' => true,
                'message' => 'Vente enregistrée',
                'product_id' => $productId,
                'quantity_sold' => $quantity,
                'new_quantity' => $productModel->find($productId)['quantite'],
            ]);
            return;
        } catch (Throwable $e) {
            if (isset($pdo) && $pdo->inTransaction()) {
                $pdo->rollBack();
            }
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
            return;
        }
    }
}
