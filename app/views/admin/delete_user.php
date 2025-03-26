<?php
require_once __DIR__ . '/../../../configs/config.php';
session_start();

// Récupérer l'ID du rôle "Administrateur"
$stmt = $pdo->prepare("SELECT id_roles FROM roles WHERE name = 'Administrateur'");
$stmt->execute();
$admin_role_id = $stmt->fetchColumn();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != $admin_role_id) {
    header('Location: /php-mvc-user-management/app/views/auth/login.php');
    exit();
}

$id = $_GET['id'];
$stmt = $pdo->prepare("DELETE FROM users WHERE id_users = ?");
$stmt->execute([$id]);

header('Location: admin_dashboard.php');
exit();
?>