<?php

declare(strict_types=1);

class UserController
{
    public function index(): void
    {
        require_role('ADMIN');

        try {
            $users = (new User())->all();
        } catch (Throwable $e) {
            flash('error', 'Erreur base de données: ' . $e->getMessage());
            $users = [];
        }

        render('users/index', [
            'users' => $users,
        ]);
    }

    public function store(): void
    {
        require_role('ADMIN');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect_to('user/index');
        }

        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($username === '' || $email === '' || $password === '') {
            flash('error', 'Veuillez remplir tous les champs du formulaire.');
            redirect_to('user/index');
        }

        try {
            $userModel = new User();

            if ($userModel->findByUsername($username)) {
                flash('error', 'Ce nom d’utilisateur est déjà utilisé.');
                redirect_to('user/index');
            }

            if ($userModel->findByEmail($email)) {
                flash('error', 'Cette adresse e-mail est déjà utilisée.');
                redirect_to('user/index');
            }

            $role = strtoupper(trim($_POST['role'] ?? 'VENDEUR'));
            if (!in_array($role, ['ADMIN', 'VENDEUR'], true)) {
                $role = 'VENDEUR';
            }

            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            if ($passwordHash === false) {
                flash('error', 'Impossible de générer le mot de passe.');
                redirect_to('user/index');
            }

            $userModel->create([
                'username' => $username,
                'email' => $email,
                'password_hash' => $passwordHash,
                'role' => $role,
            ]);

            flash('success', 'Compte créé avec succès.');
            redirect_to('user/index');
        } catch (Throwable $e) {
            flash('error', 'Erreur base de données: ' . $e->getMessage());
            redirect_to('user/index');
        }
    }

    public function destroy(): void
    {
        require_role('ADMIN');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect_to('user/index');
        }

        $id = (int) ($_POST['id'] ?? 0);
        if ($id <= 0) {
            flash('error', 'Utilisateur invalide.');
            redirect_to('user/index');
        }

        try {
            $pdo = db();
            $pdo->beginTransaction();

            $userModel = new User();
            $existing = $userModel->findById($id);
            if (!$existing) {
                $pdo->rollBack();
                flash('error', 'Utilisateur introuvable.');
                redirect_to('user/index');
            }

            // Prevent deleting the currently logged user
            if (current_user()['id'] === $existing['id']) {
                $pdo->rollBack();
                flash('error', 'Vous ne pouvez pas supprimer votre propre compte.');
                redirect_to('user/index');
            }

            // Réassigner les mouvements référencant cet utilisateur vers l'admin courant
            $adminId = (int) current_user()['id'];
            $stmt = $pdo->prepare('UPDATE stock_movements SET user_id = :new_user WHERE user_id = :old_user');
            $stmt->execute(['new_user' => $adminId, 'old_user' => $id]);

            $userModel->delete($id);

            $pdo->commit();

            flash('success', 'Utilisateur supprimé. Les mouvements le concernant ont été réassignés.');
            redirect_to('user/index');
        } catch (Throwable $e) {
            if (isset($pdo) && $pdo->inTransaction()) {
                $pdo->rollBack();
            }
            $msg = $e instanceof PDOException ? $e->getMessage() : $e->getMessage();
            flash('error', 'Impossible de supprimer l’utilisateur: ' . $msg);
            redirect_to('user/index');
        }
    }
}
