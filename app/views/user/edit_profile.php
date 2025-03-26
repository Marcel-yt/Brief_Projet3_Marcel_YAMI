<?php 
if (!isset($user)) {
    header('Location: /php-mvc-user-management/app/views/user/client_dashboard.php');
    exit();
}
include __DIR__ . '/../layouts/header.php'; 
?>

<main class="container mx-auto p-4">
    <h2 class="text-2xl font-bold mb-4">Modifier vos informations</h2>
    <form action="/php-mvc-user-management/app/routes/client_routes.php?action=updateProfile" method="POST" class="bg-white p-6 rounded shadow-md">
        <div class="mb-4">
            <label for="username" class="block text-sm font-medium text-gray-700">Nom d'utilisateur</label>
            <input type="text" name="username" id="username" class="mt-1 block w-full" value="<?php echo htmlspecialchars($user['username']); ?>" required>
        </div>
        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" name="email" id="email" class="mt-1 block w-full" value="<?php echo htmlspecialchars($user['email']); ?>" required>
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Mettre à jour</button>
        <a href="/php-mvc-user-management/app/views/user/client_dashboard.php" class="ml-2 text-gray-600">Annuler</a>
    </form>
</main>

<?php include __DIR__ . '/../layouts/footer.php'; ?>