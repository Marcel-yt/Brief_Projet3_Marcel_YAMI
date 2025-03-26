<?php
require_once __DIR__ . '/../../configs/config.php';

class AdminController {
    private $admin_role_id;

    public function __construct() {
        global $pdo;
        $stmt = $pdo->prepare("SELECT id_roles FROM roles WHERE name = 'Administrateur'");
        $stmt->execute();
        $this->admin_role_id = $stmt->fetchColumn();

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id']) || $_SESSION['role'] != $this->admin_role_id) {
            header('Location: /php-mvc-user-management/app/views/auth/login.php');
            exit();
        }
    }

    public function showCreateUser() {
        global $pdo;
        // Récupérer la liste des rôles pour le formulaire
        $stmt = $pdo->prepare("SELECT * FROM roles");
        $stmt->execute();
        $roles = $stmt->fetchAll();
        
        include __DIR__ . '/../views/admin/create_user.php';
    }

    public function createUser() {
        global $pdo;
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = trim($_POST['username']);
            $email = trim($_POST['email']);
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $role = $_POST['role'];
            
            try {
                // Vérifier si l'email existe déjà
                $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
                $stmt->execute([$email]);
                if ($stmt->fetchColumn() > 0) {
                    $_SESSION['error'] = "Cet email est déjà utilisé";
                    header('Location: /php-mvc-user-management/app/routes/admin_routes.php?action=showCreateUser');
                    exit();
                }
                
                // Créer l'utilisateur
                $stmt = $pdo->prepare("INSERT INTO users (username, email, password, id_roles, status) VALUES (?, ?, ?, ?, 'active')");
                $stmt->execute([$username, $email, $password, $role]);
                
                $_SESSION['success'] = "Utilisateur créé avec succès";
                header('Location: /php-mvc-user-management/app/views/admin/dashboard.php');
                exit();
            } catch (PDOException $e) {
                $_SESSION['error'] = "Erreur lors de la création de l'utilisateur";
                header('Location: /php-mvc-user-management/app/routes/admin_routes.php?action=showCreateUser');
                exit();
            }
        }
    }

    public function editUser() {
        global $pdo;
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $username = trim($_POST['username']);
            $email = trim($_POST['email']);

            $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ? WHERE id_users = ?");
            $stmt->execute([$username, $email, $id]);

            header('Location: /php-mvc-user-management/app/views/admin/dashboard.php');
            exit();
        } else {
            $id = $_GET['id'];
            $stmt = $pdo->prepare("SELECT * FROM users WHERE id_users = ?");
            $stmt->execute([$id]);
            $user = $stmt->fetch();

            include __DIR__ . '/../views/admin/edit_user.php';
        }
    }

    public function deleteUser() {
        global $pdo;
        $id = $_GET['id'];

        try {
            $pdo->beginTransaction();

            // Supprimer les sessions associées à l'utilisateur
            $stmt = $pdo->prepare("DELETE FROM sessions WHERE id_users = ?");
            $stmt->execute([$id]);

            // Supprimer l'utilisateur
            $stmt = $pdo->prepare("DELETE FROM users WHERE id_users = ?");
            $stmt->execute([$id]);

            $pdo->commit();
            $_SESSION['success'] = "Utilisateur supprimé avec succès";
        } catch (PDOException $e) {
            $pdo->rollBack();
            $_SESSION['error'] = "Erreur lors de la suppression de l'utilisateur";
        }

        header('Location: /php-mvc-user-management/app/views/admin/dashboard.php');
        exit();
    }

    public function toggleStatus() {
        global $pdo;
        $id = $_GET['id'];
        
        try {
            $stmt = $pdo->prepare("SELECT status FROM users WHERE id_users = ?");
            $stmt->execute([$id]);
            $status = $stmt->fetchColumn();

            $new_status = $status == 'active' ? 'inactive' : 'active';
            $stmt = $pdo->prepare("UPDATE users SET status = ? WHERE id_users = ?");
            $stmt->execute([$new_status, $id]);

            $_SESSION['success'] = "Statut de l'utilisateur mis à jour";
        } catch (PDOException $e) {
            $_SESSION['error'] = "Erreur lors de la mise à jour du statut";
        }

        header('Location: /php-mvc-user-management/app/views/admin/dashboard.php');
        exit();
    }
}
?>