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

    public function editUser() {
        global $pdo;
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $username = $_POST['username'];
            $email = $_POST['email'];

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

        // Supprimer les sessions associées à l'utilisateur
        $stmt = $pdo->prepare("DELETE FROM sessions WHERE id_users = ?");
        $stmt->execute([$id]);

        // Supprimer l'utilisateur
        $stmt = $pdo->prepare("DELETE FROM users WHERE id_users = ?");
        $stmt->execute([$id]);

        header('Location: /php-mvc-user-management/app/views/admin/dashboard.php');
        exit();
    }

    public function toggleStatus() {
        global $pdo;
        $id = $_GET['id'];
        $stmt = $pdo->prepare("SELECT status FROM users WHERE id_users = ?");
        $stmt->execute([$id]);
        $status = $stmt->fetchColumn();

        $new_status = $status == 'active' ? 'inactive' : 'active';
        $stmt = $pdo->prepare("UPDATE users SET status = ? WHERE id_users = ?");
        $stmt->execute([$new_status, $id]);

        header('Location: /php-mvc-user-management/app/views/admin/dashboard.php');
        exit();
    }
}
?>