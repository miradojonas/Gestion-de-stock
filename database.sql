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
    motif VARCHAR(255) DEFAULT NULL,
    CONSTRAINT fk_movements_product
        FOREIGN KEY (product_id) REFERENCES products(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_movements_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    INDEX idx_movements_date (date),
    INDEX idx_movements_type (movement_type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO users (username, password_hash, role) VALUES
('admin', '$2y$10$BsNvcBxx8l2XViTw5TqYOO/sZrskk9bnkqahEOsoic8Ht6RAbpg.K', 'ADMIN'),
('vendeur', '$2y$10$vyG2mRv8MXokItFvEno1UeAGN8I7Z1WUVDC9Phg3a40QLI3RTVeg.', 'VENDEUR');

INSERT INTO categories (name) VALUES
('Fournitures'),
('Electronique'),
('Bureautique');

INSERT INTO types (name, category_id) VALUES
('Papeterie', 1),
('Accessoires', 1),
('Accessoires PC', 2),
('Imprimantes', 2),
('Mobilier', 3);

INSERT INTO products (libelle, prix_achat, prix_vente, quantite, stock_min, image_path, category_id, type_id, actif) VALUES
('Cahier A4', 1.50, 2.50, 40, 10, NULL, 1, 1, 1),
('Souris sans fil', 8.00, 15.00, 12, 5, NULL, 2, 3, 1),
('Chaise bureau', 35.00, 55.00, 6, 3, NULL, 3, 5, 1);
