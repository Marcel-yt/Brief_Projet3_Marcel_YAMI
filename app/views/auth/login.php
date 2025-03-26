<?php
require_once __DIR__ . '/../../../configs/config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // Vérifier si le compte est actif
        if ($user['status'] !== 'active') {
            $error = "Votre compte a été désactivé. Veuillez contacter l'administrateur.";
        } else {
            $_SESSION['user_id'] = $user['id_users'];
            $_SESSION['role'] = $user['id_roles'];
            
            // Enregistrer l'heure de connexion
            $stmt = $pdo->prepare("INSERT INTO sessions (id_users) VALUES (?)");
            $stmt->execute([$user['id_users']]);

            $roleStmt = $pdo->prepare("SELECT name FROM roles WHERE id_roles = ?");
            $roleStmt->execute([$user['id_roles']]);
            $role = $roleStmt->fetchColumn();

            if ($role == 'Administrateur') {
                header('Location: /php-mvc-user-management/app/views/admin/dashboard.php');
            } else {
                header('Location: /php-mvc-user-management/app/views/user/client_dashboard.php');
            }
            exit();
        }
    } else {
        $error = "Email ou mot de passe incorrect.";
    }
}

// Gérer les messages d'erreur depuis les redirections
if (isset($_GET['error'])) {
    switch($_GET['error']) {
        case 'account_disabled':
            $error = "Votre compte a été désactivé. Veuillez contacter l'administrateur.";
            break;
        case 'session_expired':
            $error = "Votre session a expiré. Veuillez vous reconnecter.";
            break;
    }
}
?>

<?php include '../layouts/header.php'; ?>

<main class="container mx-auto p-4">
    <h2 class="text-2xl font-bold mb-4">Connexion</h2>
    
    <?php if (isset($error)): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline"><?php echo htmlspecialchars($error); ?></span>
        </div>
    <?php endif; ?>

    <form action="login.php" method="POST" class="bg-white p-6 rounded shadow-md">
        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" name="email" id="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
        </div>
        <div class="mb-4">
            <label for="password" class="block text-sm font-medium text-gray-700">Mot de passe</label>
            <input type="password" name="password" id="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
        </div>
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Se connecter</button>
    </form>
</main>

<?php include '../layouts/footer.php'; ?>