<?php
require_once 'config.php';
$settings = require_once 'settings.php'; // Charge les paramètres actuels

// Gérer l'affichage des messages de succès/erreur
$message = '';
$message_type = '';

if (isset($_GET['success'])) {
    $message = "Paramètres de sécurité mis à jour avec succès !";
    $message_type = 'success';
} elseif (isset($_GET['error'])) {
    $message = "Erreur lors de la mise à jour des paramètres de sécurité : " . htmlspecialchars($_GET['error']);
    $message_type = 'error';
}

// Récupérer les informations de l'utilisateur (admin, id=1)
$user = null;
try {
    $stmt = $pdo->prepare("SELECT id, name, email FROM users WHERE id = 1");
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (\PDOException $e) {
    error_log("Erreur lors de la récupération de l'utilisateur admin : " . $e->getMessage());
    $message = "Erreur : Impossible de charger les informations de l'administrateur.";
    $message_type = 'error';
}

?>
<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paramètres de Sécurité - ConseilBox Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <script src="https://unpkg.com/feather-icons"></script>
</head>
<body class="h-full <?= $settings['theme'] === 'dark' ? 'dark' : '' ?>">
    <?php include 'includes/navbar.php'; ?>
    <div class="flex">
        <?php include 'includes/sidebar.php'; ?>
        
        <main class="flex-1 p-4 sm:p-8 ml-0 lg:ml-64">
            <div class="max-w-3xl mx-auto">
                <div class="flex items-center mb-6">
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 dark:text-gray-100 ml-4">Paramètres de Sécurité</h1>
                </div>

                <?php if ($message): ?>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            showAlert("<?= $message ?>", "<?= $message_type ?>");
                        });
                    </script>
                <?php endif; ?>
                
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                    <form action="actions/_sauvegarder_securite.php" method="POST" class="space-y-8">
                        <div class="p-6 sm:p-8">
                            <?php if ($user): ?>
                                <div>
                                    <label for="current_email" class="block text-sm font-semibold text-gray-600 dark:text-gray-300 mb-1">Email / Code de Login Actuel</label>
                                    <input type="email" name="current_email" id="current_email" value="<?= htmlspecialchars($user['email']) ?>" readonly
                                           class="mt-1 block w-full px-4 py-2 bg-gray-100 dark:bg-gray-700 dark:text-gray-100 rounded-lg border-gray-200 dark:border-gray-600 cursor-not-allowed">
                                </div>
                                <div>
                                    <label for="new_email" class="block text-sm font-semibold text-gray-600 dark:text-gray-300 mb-1">Nouvel Email / Code de Login</label>
                                    <input type="email" name="new_email" id="new_email" placeholder="Entrez le nouvel email" required
                                           class="mt-1 block w-full px-4 py-2 bg-gray-50 dark:bg-gray-700 dark:text-gray-100 rounded-lg border-gray-200 dark:border-gray-600 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-150 ease-in-out">
                                </div>
                                <hr class="border-gray-200 dark:border-gray-700 my-8">
                                <div>
                                    <label for="current_password" class="block text-sm font-semibold text-gray-600 dark:text-gray-300 mb-1">Mot de passe Actuel</label>
                                    <input type="password" name="current_password" id="current_password" placeholder="Entrez votre mot de passe actuel" required
                                           class="mt-1 block w-full px-4 py-2 bg-gray-50 dark:bg-gray-700 dark:text-gray-100 rounded-lg border-gray-200 dark:border-gray-600 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-150 ease-in-out">
                                </div>
                                <div>
                                    <label for="new_password" class="block text-sm font-semibold text-gray-600 dark:text-gray-300 mb-1">Nouveau Mot de passe</label>
                                    <input type="password" name="new_password" id="new_password" placeholder="Entrez le nouveau mot de passe" required
                                           class="mt-1 block w-full px-4 py-2 bg-gray-50 dark:bg-gray-700 dark:text-gray-100 rounded-lg border-gray-200 dark:border-gray-600 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-150 ease-in-out">
                                </div>
                                <div>
                                    <label for="confirm_password" class="block text-sm font-semibold text-gray-600 dark:text-gray-300 mb-1">Confirmer le Nouveau Mot de passe</label>
                                    <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirmez le nouveau mot de passe" required
                                           class="mt-1 block w-full px-4 py-2 bg-gray-50 dark:bg-gray-700 dark:text-gray-100 rounded-lg border-gray-200 dark:border-gray-600 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-150 ease-in-out">
                                </div>
                            <?php else: ?>
                                <p class="text-red-500">Erreur : Impossible de charger les paramètres de sécurité. L'utilisateur administrateur n'a pas été trouvé.</p>
                            <?php endif; ?>
                        </div>

                        <div class="bg-gray-50 dark:bg-gray-700 px-6 py-4 flex items-center justify-end gap-x-3">
                            <a href="index.php" class="bg-white dark:bg-gray-600 py-2 px-5 border border-gray-300 dark:border-gray-500 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-150 ease-in-out">Annuler</a>
                            <button type="submit" class="inline-flex justify-center py-2 px-5 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transform hover:scale-105 transition-all duration-150 ease-in-out">
                                <i data-feather="save" class="w-4 h-4 mr-2"></i>
                                Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
    <script src="script.js"></script>
</body>
</html>
