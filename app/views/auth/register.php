<?php
require_once __DIR__ . '/../../../configs/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    
    // Récupérer l'ID du rôle "Client"
    $stmt = $pdo->prepare("SELECT id_roles FROM roles WHERE name = 'Client'");
    $stmt->execute();
    $role = $stmt->fetchColumn();

    $stmt = $pdo->prepare("INSERT INTO users (username, email, password, id_roles) VALUES (?, ?, ?, ?)");
    $stmt->execute([$username, $email, $password, $role]);

    header('Location: login.php');
    exit();
}
?>

<?php include '../layouts/header.php'; ?>

<main class="container mx-auto p-4 ">
    <h2 class="text-2xl font-bold mb-4 text-center ">Inscription</h2>
    <form action="register.php" method="POST" class="bg-white p-6 rounded shadow-md max-w-md mx-auto">
        <div class="mb-4">
            <label for="username" class="block text-sm font-medium text-gray-700">Nom d'utilisateur</label>
            <input type="text" name="username" id="username" class="mt-1 block w-full border" required>
        </div>
        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" name="email" id="email" class="mt-1 block w-full border" required>
        </div>
        <div class="mb-4">
            <label for="password" class="block text-sm font-medium text-gray-700">Mot de passe</label>
            <input type="password" name="password" id="password" class="mt-1 block w-full border" required>
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">S'inscrire</button>
    </form>
</main>

<?php include '../layouts/footer.php'; ?>