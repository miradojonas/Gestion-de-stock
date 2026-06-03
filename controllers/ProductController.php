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
            'categories' => (new Category())->all(),
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

        $productModel = new Product();
        $productModel->create($this->payloadFromRequest());

        flash('success', 'Produit ajouté.');
        redirect_to('product/index');
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
            'categories' => (new Category())->all(),
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
        (new Product())->update($id, $this->payloadFromRequest());

        flash('success', 'Produit mis à jour.');
        redirect_to('product/index');
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
            'image_path' => $imagePath !== '' ? $imagePath : $existingImagePath,
            'category_id' => !empty($_POST['category_id']) ? (int) $_POST['category_id'] : null,
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

        $destination = $uploadDir . '/' . $filename;

        if (!move_uploaded_file($_FILES['image_file']['tmp_name'], $destination)) {
            throw new RuntimeException('Impossible de déplacer le fichier uploadé.');
        }

        return 'uploads/' . $filename;
    }
}
