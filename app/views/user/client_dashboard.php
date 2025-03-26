<?php
require_once __DIR__ . '/../../../configs/config.php';
session_start();

// Récupérer l'ID du rôle "Client"
$stmt = $pdo->prepare("SELECT id_roles FROM roles WHERE name = 'Client'");
$stmt->execute();
$client_role_id = $stmt->fetchColumn();

// Vérifier si l'utilisateur est connecté et est un client
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != $client_role_id) {
    header('Location: /php-mvc-user-management/app/views/auth/login.php');
    exit();
}

// Fetch user information and check status
$stmt = $pdo->prepare("SELECT * FROM users WHERE id_users = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

// Rediriger si l'utilisateur est désactivé
if ($user['status'] !== 'active') {
    session_destroy();
    header('Location: /php-mvc-user-management/app/views/auth/login.php?error=account_disabled');
    exit();
}

// Récupérer l'historique des sessions avec gestion du statut "active"
$stmt = $pdo->prepare("
    SELECT s.*, 
    CASE 
        WHEN s.logout_time IS NULL THEN 'active'
        ELSE DATE_FORMAT(s.logout_time, '%d-%m-%Y %H:%i:%s')
    END as logout_display,
    DATE_FORMAT(s.login_time, '%d-%m-%Y %H:%i:%s') as formatted_login_time
    FROM sessions s 
    WHERE s.id_users = ?
    ORDER BY s.login_time DESC
");
$stmt->execute([$_SESSION['user_id']]);
$sessions = $stmt->fetchAll();
?>

<?php include '../layouts/header.php'; ?>

<main class="container mx-auto p-4">
    <h2 class="text-2xl font-bold mb-4">Dashboard Client</h2>
    <p class="mb-6">Bienvenue, <?php echo htmlspecialchars($user['username']); ?>!</p>

    <!-- Section Profil -->
    <div class="bg-white p-6 rounded shadow-md mb-8">
        <h3 class="text-xl font-bold mb-4">Informations du profil</h3>
        <div class="mb-4">
            <p class="text-gray-600">Email: <?php echo htmlspecialchars($user['email']); ?></p>
            <p class="text-gray-600">Statut: <?php echo htmlspecialchars($user['status']); ?></p>
        </div>
        <a href="/php-mvc-user-management/app/routes/client_routes.php?action=editProfile" 
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
           Modifier mes informations
        </a>
    </div>

    <!-- Section Historique des connexions -->
    <div class="bg-white p-6 rounded shadow-md">
        <h3 class="text-xl font-bold mb-4">Historique des connexions</h3>
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Date de connexion
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Date de déconnexion
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php foreach ($sessions as $session): ?>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <?php echo $session['formatted_login_time']; ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="<?php echo $session['logout_display'] === 'active' ? 'text-green-600 font-semibold' : ''; ?>">
                            <?php echo $session['logout_display']; ?>
                        </span>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>

<?php include '../layouts/footer.php'; ?>