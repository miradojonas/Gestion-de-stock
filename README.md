# Gestion Stock — Guide d'utilisation rapide

docker compose logs db
Petit guide complet pour installer, configurer et utiliser l'application de gestion de stock (PHP + MySQL).

## Table des matières

- Prérequis
- Installation (XAMPP)
- Installation (Docker)
- Comptes par défaut
- Connexion et rôles
- Vue d'ensemble de l'interface
- Gestion des produits
- Catégories et types
- Gestion du stock (Entrées / Sorties)
- Ventes rapides depuis la liste produits
- Historique des mouvements
- Gestion des utilisateurs
- Uploads et permissions
- Base de données et encodage
- Sauvegarde & restauration
- Dépannage

---

## Prérequis

- PHP 8.0+ (recommandé PHP 8.1+). Le projet est compatible avec `php:8.2-apache`.
- MySQL 5.7+ ou 8.0+.
- Serveur web local (XAMPP) ou Docker.

## Installation (XAMPP)

1. Copier le projet dans le dossier web (ex: `/opt/lampp/htdocs/gestion_stock`).
2. Importer la base de données :

```bash
mysql -u root -p gestion_stock < database.sql
```

3. Si XAMPP utilise le socket Unix (typique sur Linux), adapte `config/db.php` :

```php
// Exemple DSN via socket XAMPP
$dsn = "mysql:unix_socket=/opt/lampp/var/mysql/mysql.sock;dbname={$database};charset={$charset}";
```

4. Ouvrir l'application depuis le navigateur : `http://localhost/gestion_stock`.

## Installation (Docker)

1. Construire et démarrer les services :

```bash
docker compose up -d --build
```

2. Vérifier que les services sont opérationnels :

```bash
docker compose ps
docker compose logs db
```

3. Accéder à l'application via le port exposé par `docker-compose.yml`.

---

## Comptes par défaut

Le fichier `database.sql` inclut deux comptes d'exemple :

- `admin` / `admin@example.com` — rôle `ADMIN`
- `vendeur` / `vendeur@example.com` — rôle `VENDEUR`

Utilise ces comptes pour te connecter la première fois.

## Connexion et rôles

- `ADMIN` : accès complet (produits, catégories, types, utilisateurs, stock).
- `VENDEUR` : accès limité (vendre, voir stock, historique).

## Vue d'ensemble de l'interface

- Barre de navigation : Dashboard, Produits, Historique, Catégories, Types, Utilisateurs (ADMIN uniquement).
- Dashboard : métriques rapides (total produits, entrées/sorties du jour, stock faible, mouvements récents).

## Gestion des produits

- Créer un produit : `Produits` → `Nouveau produit` → remplir le formulaire (libellé, prix achat/vente, quantité initiale, stock min, image, type).
- Modifier un produit : bouton `Modifier` dans la liste produits. Si la quantité change, un mouvement de stock est automatiquement enregistré (IN/OUT selon la différence).
- Désactiver un produit : bouton `Désactiver` (ne supprime pas, marque `actif = 0`).

### Images

- Les images sont uploadées vers le dossier `uploads/`. Assure-toi que le dossier est accessible et inscriptible par le serveur web (permissions du système de fichiers).

## Catégories et Types

- Gérer via `Catégories` et `Types` dans le menu (ADMIN seulement).
- Les `Types` référencent `categories` et sont utilisées lors de la création de produits.

## Gestion du stock (Entrées / Sorties)

- `Stock` → `Entrée` (ADMIN) : choisir produit et quantité. Cela met à jour la quantité produit et enregistre un `StockMovement` de type `IN`.
- `Stock` → `Sortie` (VENDEUR) : idem, enregistre un `StockMovement` `OUT` et décrémente la quantité.

## Ventes rapides depuis la liste produits

- Depuis la liste produits, il y a une action de vente rapide (AJAX) qui appelle l'endpoint `stock/apiSell` — utile pour points de vente rapides.

## Historique des mouvements

- `Historique` montre les mouvements récents et détaillés : date, produit, type (Ajout/Sortie), quantité, utilisateur, email et montant total pour les sorties.

## Gestion des utilisateurs

- Accessible via `Utilisateurs` (ADMIN).
- Créer un utilisateur : renseigner `username`, `email`, `password`, sélectionner le rôle `ADMIN` ou `VENDEUR`, puis cliquer `Créer`.
- Supprimer un utilisateur : bouton `Supprimer` dans la liste. L'application empêche la suppression du compte courant. Avant suppression, tous les `stock_movements` liés sont réassignés à l'admin connecté pour préserver l'intégrité référentielle.

Remarque : si tu préfères un autre comportement (par ex. créer un utilisateur système "Supprimé" ou utiliser `ON DELETE SET NULL`), dis-le et je proposerai la migration SQL.

## Uploads et permissions

- Dossier `uploads/` doit être inscriptible par l'utilisateur exécutant PHP/Apache. Sous Linux, un réglage habituel :

```bash
chown -R www-data:www-data uploads/
chmod -R 775 uploads/
```

(adapte `www-data` à ton utilisateur Apache, ex: `daemon`, `wwwrun`, `www-data` ou `nobody` selon l'environnement).

## Base de données et encodage

- La base doit être en `utf8mb4` / `utf8mb4_unicode_ci` pour bien gérer les accents et emojis.
- Si tu observes des chaînes corrompues (ex: `ApÃ©ritifs`), réimporte la base avec les bonnes options et `SET NAMES utf8mb4` avant l'import.

## Sauvegarde & restauration

- Exporter la base :

```bash
mysqldump -u root -p gestion_stock > gestion_stock_backup.sql
```

- Restaurer :

```bash
mysql -u root -p gestion_stock < gestion_stock_backup.sql
```

## Dépannage

- Erreur "Connexion MySQL impossible" : vérifie que le serveur MySQL est démarré et que `config/db.php` contient les bons identifiants ou le bon socket.
- Contrainte FK lors de suppression d'utilisateur : le code réassigne les mouvements au moment de la suppression. Si l'opération échoue, vérifie les permissions DB et logs.
- Problèmes d'upload/permissions : ajuste propriétaire et permissions du répertoire `uploads`.

## Outils utiles

- Adminer ou phpMyAdmin pour inspecter la base localement.
- Docker + Adminer : pratique pour développement.
