<?php
require_once __DIR__ . '/../../../configs/config.php';
session_start();

if (isset($_SESSION['user_id'])) {
    // Enregistrer l'heure de déconnexion
    $stmt = $pdo->prepare("UPDATE sessions SET logout_time = NOW() WHERE id_users = ? AND logout_time IS NULL");
    $stmt->execute([$_SESSION['user_id']]);

    // Détruire la session
    session_destroy();
}

header('Location: /php-mvc-user-management/app/views/auth/login.php');
exit();
?>