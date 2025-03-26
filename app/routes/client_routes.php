<?php
session_start();
require_once __DIR__ . '/../controllers/ClientController.php';

$action = $_GET['action'] ?? '';
$client = new ClientController();

switch($action) {
    case 'editProfile':
        $client->editProfile();
        break;
    case 'updateProfile':
        $client->updateProfile();
        break;
    default:
        header('Location: /php-mvc-user-management/app/views/user/client_dashboard.php');
        break;
}