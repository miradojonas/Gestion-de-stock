CREATE DATABASE IF NOT EXISTS gestion_stock CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE gestion_stock;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS stock_movements;
DROP TABLE IF EXISTS products;
DROP TABLE IF EXISTS types;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS users;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('ADMIN', 'VENDEUR') NOT NULL DEFAULT 'VENDEUR',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE categories (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE types (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    category_id INT UNSIGNED NOT NULL,
    CONSTRAINT fk_types_category
        FOREIGN KEY (category_id) REFERENCES categories(id)
        ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE products (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(200) NOT NULL,
    prix_achat DECIMAL(10,2) NOT NULL DEFAULT 0,
    prix_vente DECIMAL(10,2) NOT NULL DEFAULT 0,
    quantite INT NOT NULL DEFAULT 0,
    stock_min INT NOT NULL DEFAULT 0,
    image_path VARCHAR(255) DEFAULT NULL,
    category_id INT UNSIGNED DEFAULT NULL,
    type_id INT UNSIGNED DEFAULT NULL,
    actif TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_products_category
        FOREIGN KEY (category_id) REFERENCES categories(id)
        ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT fk_products_type
        FOREIGN KEY (type_id) REFERENCES types(id)
        ON DELETE SET NULL ON UPDATE CASCADE,
    INDEX idx_products_libelle (libelle),
    INDEX idx_products_stock (quantite, stock_min)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE stock_movements (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_id INT UNSIGNED NOT NULL,
    movement_type ENUM('IN', 'OUT') NOT NULL,
    quantity INT NOT NULL,
    date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    user_id INT UNSIGNED NOT NULL,
    CONSTRAINT fk_movements_product
        FOREIGN KEY (product_id) REFERENCES products(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_movements_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    INDEX idx_movements_date (date),
    INDEX idx_movements_type (movement_type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO users (username, email, password_hash, role) VALUES
('admin', 'admin@example.com', '$2y$10$BsNvcBxx8l2XViTw5TqYOO/sZrskk9bnkqahEOsoic8Ht6RAbpg.K', 'ADMIN'),
('vendeur', 'vendeur@example.com', '$2y$10$vyG2mRv8MXokItFvEno1UeAGN8I7Z1WUVDC9Phg3a40QLI3RTVeg.', 'VENDEUR');

-- Catégorie et types par défaut
INSERT INTO categories (name) VALUES
('Boisson');

INSERT INTO types (name, category_id) VALUES
('Alcools forts', 1),
('Vins', 1),
('Bières', 1),
('Apéritifs/Digestifs', 1),
('Cocktails/Mélanges', 1),
('Soft/Sans alcool', 1),
('Chaud', 1);
