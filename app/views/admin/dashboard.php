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

// Fetch all users
$stmt = $pdo->prepare("SELECT * FROM users");
$stmt->execute();
$users = $stmt->fetchAll();

// Fetch all sessions with user information
$stmt = $pdo->prepare("SELECT sessions.*, users.username FROM sessions JOIN users ON sessions.id_users = users.id_users");
$stmt->execute();
$sessions = $stmt->fetchAll();
?>

<?php include '../layouts/header.php'; ?>

<main class="container mx-auto p-4">
    <h2 class="text-2xl font-bold mb-4">Dashboard Administrateur</h2>
    <h3 class="text-xl font-bold mt-4">Utilisateurs enregistrés</h3>
    <table class="min-w-full bg-white">
        <thead>
            <tr>
                <th class="py-2">Nom d'utilisateur</th>
                <th class="py-2">Email</th>
                <th class="py-2">Rôle</th>
                <th class="py-2">Statut</th>
                <th class="py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td class="border px-4 py-2"><?php echo $user['username']; ?></td>
                    <td class="border px-4 py-2"><?php echo $user['email']; ?></td>
                    <td class="border px-4 py-2"><?php echo $user['id_roles'] == $admin_role_id ? 'Administrateur' : 'Client'; ?></td>
                    <td class="border px-4 py-2"><?php echo $user['status']; ?></td>
                    <td class="border px-4 py-2">
                        <a href="/php-mvc-user-management/app/routes/admin_routes.php?action=edit&id=<?php echo $user['id_users']; ?>" 
                        class="text-blue-600 hover:text-blue-800 mr-2">Modifier</a>
                        <a href="/php-mvc-user-management/app/routes/admin_routes.php?action=delete&id=<?php echo $user['id_users']; ?>" 
                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');"
                        class="text-red-600 hover:text-red-800 mr-2">Supprimer</a>
                        <a href="/php-mvc-user-management/app/routes/admin_routes.php?action=toggle&id=<?php echo $user['id_users']; ?>"
                        class="text-green-600 hover:text-green-800">
                        <?php echo $user['status'] === 'active' ? 'Désactiver' : 'Activer'; ?>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h3 class="text-xl font-bold mt-4">Logs de connexion</h3>
    <table class="min-w-full bg-white">
        <thead>
            <tr>
                <th class="py-2">Utilisateur</th>
                <th class="py-2">Date de connexion</th>
                <th class="py-2">Date de déconnexion</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($sessions as $session): ?>
                <tr>
                    <td class="border px-4 py-2"><?php echo $session['username']; ?></td>
                    <td class="border px-4 py-2"><?php echo $session['login_time']; ?></td>
                    <td class="border px-4 py-2"><?php echo $session['logout_time']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</main>

<?php include '../layouts/footer.php'; ?>