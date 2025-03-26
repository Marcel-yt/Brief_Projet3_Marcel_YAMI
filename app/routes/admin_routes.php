<?php
session_start();
require_once __DIR__ . '/../controllers/AdminController.php';

$action = $_GET['action'] ?? '';
$admin = new AdminController();

switch($action) {
    case 'showCreateUser':
        $admin->showCreateUser();
        break;
    case 'createUser':
        $admin->createUser();
        break;
    case 'edit':
        $admin->editUser();
        break;
    case 'delete':
        $admin->deleteUser();
        break;
    case 'toggle':
        $admin->toggleStatus();
        break;
    default:
        header('Location: /php-mvc-user-management/app/views/admin/dashboard.php');
        break;
}