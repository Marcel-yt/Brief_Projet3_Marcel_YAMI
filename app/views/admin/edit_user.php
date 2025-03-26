<?php
if (!isset($user)) {
    header('Location: /php-mvc-user-management/app/views/admin/dashboard.php');
    exit();
}
include __DIR__ . '/../layouts/header.php';
?>

<main class="container mx-auto p-4">
    <h2 class="text-2xl font-bold mb-4">Modifier l'utilisateur</h2>
    <form action="/php-mvc-user-management/app/routes/admin_routes.php?action=edit" method="POST" class="bg-white p-6 rounded shadow-md">
        <input type="hidden" name="id" value="<?php echo $user['id_users']; ?>">
        <div class="mb-4">
            <label for="username" class="block text-sm font-medium text-gray-700">Nom d'utilisateur</label>
            <input type="text" name="username" id="username" class="mt-1 block w-full" value="<?php echo htmlspecialchars($user['username']); ?>" required>
        </div>
        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" name="email" id="email" class="mt-1 block w-full" value="<?php echo htmlspecialchars($user['email']); ?>" required>
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Mettre à jour</button>
    </form>
</main>

<?php include __DIR__ . '/../layouts/footer.php'; ?>