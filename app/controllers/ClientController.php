<?php
require_once __DIR__ . '/../../configs/config.php';

class ClientController {
    private $client_role_id;

    public function __construct() {
        global $pdo;
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $stmt = $pdo->prepare("SELECT id_roles FROM roles WHERE name = 'Client'");
        $stmt->execute();
        $this->client_role_id = $stmt->fetchColumn();

        if (!isset($_SESSION['user_id']) || $_SESSION['role'] != $this->client_role_id) {
            header('Location: /php-mvc-user-management/app/views/auth/login.php');
            exit();
        }
    }

    public function updateProfile() {
        global $pdo;
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_SESSION['user_id'];
            $username = $_POST['username'];
            $email = $_POST['email'];

            $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ? WHERE id_users = ?");
            $stmt->execute([$username, $email, $id]);

            header('Location: /php-mvc-user-management/app/views/user/client_dashboard.php');
            exit();
        }
    }

    public function editProfile() {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id_users = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch();
        
        include __DIR__ . '/../views/user/edit_profile.php';
    }
}
?>