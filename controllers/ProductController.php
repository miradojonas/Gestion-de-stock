<?php

declare(strict_types=1);

class ProductController
{
    public function index(): void
    {
        require_login();

        $productModel = new Product();
        render('products/index', [
            'products' => $productModel->all(),
        ]);
    }

    public function create(): void
    {
        require_role('ADMIN');

        render('products/form', [
            'product' => null,
            'types' => (new Type())->all(),
            'formAction' => base_route('product/store'),
        ]);
    }

    public function store(): void
    {
        require_role('ADMIN');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect_to('product/create');
        }

        try {
            $productModel = new Product();
            $payload = $this->payloadFromRequest();
            $productModel->create($payload);

            $createdProductId = (int) db()->lastInsertId();
            if ($createdProductId > 0 && $payload['quantite'] > 0) {
                (new StockMovement())->create([
                    'product_id' => $createdProductId,
                    'movement_type' => 'IN',
                    'quantity' => $payload['quantite'],
                    'user_id' => current_user()['id'],
                ]);
            }

            flash('success', 'Produit ajouté.');
            redirect_to('product/index');
        } catch (Throwable $e) {
            flash('error', $e->getMessage());
            redirect_to('product/create');
        }
    }

    public function edit(): void
    {
        require_role('ADMIN');

        $id = (int) ($_GET['id'] ?? 0);
        $product = (new Product())->find($id);

        if (!$product) {
            flash('error', 'Produit introuvable.');
            redirect_to('product/index');
        }

        render('products/form', [
            'product' => $product,
            'types' => (new Type())->all(),
            'formAction' => base_route('product/update', ['id' => $id]),
        ]);
    }

    public function update(): void
    {
        require_role('ADMIN');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect_to('product/index');
        }

        $id = (int) ($_GET['id'] ?? 0);
        $payload = $this->payloadFromRequest();

        $pdo = db();
        $pdo->beginTransaction();

        try {
            $productModel = new Product();
            $existingProduct = $productModel->find($id);

            if (!$existingProduct) {
                throw new RuntimeException('Produit introuvable.');
            }

            $productModel->update($id, $payload);

            $quantityDiff = $payload['quantite'] - (int) $existingProduct['quantite'];
            if ($quantityDiff !== 0) {
                (new StockMovement())->create([
                    'product_id' => $id,
                    'movement_type' => $quantityDiff > 0 ? 'IN' : 'OUT',
                    'quantity' => abs($quantityDiff),
                    'user_id' => current_user()['id'],
                ]);
            }

            $pdo->commit();
            flash('success', 'Produit mis à jour.');
            redirect_to('product/index');
        } catch (Throwable $e) {
            $pdo->rollBack();
            flash('error', $e->getMessage());
            redirect_to('product/edit', ['id' => $id]);
        }
    }

    public function delete(): void
    {
        require_role('ADMIN');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect_to('product/index');
        }

        $id = (int) ($_POST['id'] ?? 0);
        (new Product())->deactivate($id);

        flash('success', 'Produit désactivé.');
        redirect_to('product/index');
    }

    private function payloadFromRequest(): array
    {
        $imagePath = $this->processImageUpload();
        $existingImagePath = trim($_POST['existing_image_path'] ?? '');

        return [
            'libelle' => trim($_POST['libelle'] ?? ''),
            'prix_achat' => (float) ($_POST['prix_achat'] ?? 0),
            'prix_vente' => (float) ($_POST['prix_vente'] ?? 0),
            'quantite' => (int) ($_POST['quantite'] ?? 0),
            'stock_min' => (int) ($_POST['stock_min'] ?? 0),
            'image_path' => !empty($imagePath) ? $imagePath : (!empty($existingImagePath) ? $existingImagePath : null),
            'type_id' => !empty($_POST['type_id']) ? (int) $_POST['type_id'] : null,
            'actif' => isset($_POST['actif']) ? 1 : 0,
        ];
    }

    private function processImageUpload(): string
    {
        if (empty($_FILES['image_file']['name'])) {
            return '';
        }

        if (!isset($_FILES['image_file']) || $_FILES['image_file']['error'] !== UPLOAD_ERR_OK) {
            throw new RuntimeException('Erreur lors de l’upload de l’image.');
        }

        $allowedMimeTypes = [
            'image/jpeg',
            'image/png',
            'image/gif',
            'image/webp',
        ];

        $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($fileInfo, $_FILES['image_file']['tmp_name']);
        finfo_close($fileInfo);

        if (!in_array($mimeType, $allowedMimeTypes, true)) {
            throw new RuntimeException('Le format d’image n’est pas autorisé.');
        }

        $extension = pathinfo($_FILES['image_file']['name'], PATHINFO_EXTENSION);
        $extension = strtolower($extension);
        $filename = bin2hex(random_bytes(10)) . '.' . $extension;
        $uploadDir = __DIR__ . '/../uploads';

        if (!is_dir($uploadDir) && !mkdir($uploadDir, 0755, true) && !is_dir($uploadDir)) {
            throw new RuntimeException('Impossible de créer le dossier d’upload.');
        }

        if (!is_writable($uploadDir)) {
            throw new RuntimeException('Le dossier d’upload n’est pas accessible en écriture.');
        }

        $destination = $uploadDir . '/' . $filename;

        if (!is_uploaded_file($_FILES['image_file']['tmp_name']) || !move_uploaded_file($_FILES['image_file']['tmp_name'], $destination)) {
            throw new RuntimeException('Impossible de déplacer le fichier uploadé. Vérifie les permissions du dossier uploads.');
        }

        return 'uploads/' . $filename;
    }
}
