<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include __DIR__ . '/../layouts/header.php';
?>

<main class="container mx-auto p-4">
    <h2 class="text-2xl font-bold mb-4">Créer un nouvel utilisateur</h2>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
            <?php 
            echo htmlspecialchars($_SESSION['error']);
            unset($_SESSION['error']);
            ?>
        </div>
    <?php endif; ?>
    
    <form action="/php-mvc-user-management/app/routes/admin_routes.php?action=createUser" method="POST" class="bg-white p-6 rounded shadow-md">
        <div class="mb-4">
            <label for="username" class="block text-sm font-medium text-gray-700">Nom d'utilisateur</label>
            <input type="text" name="username" id="username" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
        </div>
        
        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" name="email" id="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
        </div>
        
        <div class="mb-4">
            <label for="password" class="block text-sm font-medium text-gray-700">Mot de passe</label>
            <input type="password" name="password" id="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
        </div>
        
        <div class="mb-4">
            <label for="role" class="block text-sm font-medium text-gray-700">Rôle</label>
            <select name="role" id="role" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                <?php foreach ($roles as $role): ?>
                    <option value="<?php echo $role['id_roles']; ?>">
                        <?php echo htmlspecialchars($role['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="flex justify-between">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Créer</button>
            <a href="/php-mvc-user-management/app/views/admin/dashboard.php" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">Annuler</a>
        </div>
    </form>
</main>

<?php include __DIR__ . '/../layouts/footer.php'; ?>