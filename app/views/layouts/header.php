<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>gestion des utilisateurs</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <header class="bg-blue-600 text-white p-4">
        <div class="container mx-auto flex justify-between items-center px-10">
            <h1 class="text-xl font-bold">Système de gestion des utilisateurs</h1>
            <nav>
                <ul class="flex space-x-4 items-center">
                    <li><a href="/php-mvc-user-management/public/index.php" class="hover:underline">Accueil</a></li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li><span class="text-gray-200">Bienvenue, <?php echo htmlspecialchars($_SESSION['username'] ?? 'Utilisateur'); ?></span></li>
                        <li><a href="/php-mvc-user-management/app/views/auth/logout.php" class="hover:underline">Déconnexion</a></li>
                    <?php else: ?>
                        <li><a href="/php-mvc-user-management/app/views/auth/login.php" class="hover:underline">Connexion</a></li>
                        <li><a href="/php-mvc-user-management/app/views/auth/register.php" class="hover:underline">Inscription</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>