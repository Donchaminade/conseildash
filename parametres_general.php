<?php
require_once 'auth_check.php'; // Vérifie l'authentification avant tout

require_once 'config.php';
$settings = require ROOT_PATH . '/settings.php'; // Charge les paramètres actuels

// Gérer l'affichage des messages de succès/erreur
$message = '';
$message_type = '';

if (isset($_GET['success'])) {
    $message = "Paramètres sauvegardés avec succès !";
    $message_type = 'success';
} elseif (isset($_GET['error'])) {
    $message = "Erreur lors de la sauvegarde : " . htmlspecialchars($_GET['error']);
    $message_type = 'error';
}
?>
<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paramètres Généraux - ConseilBox Dashboard</title>
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
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 dark:text-gray-100 ml-4">Paramètres Généraux</h1>
                </div>

                <?php if ($message): ?>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            showAlert("<?= $message ?>", "<?= $message_type ?>");
                        });
                    </script>
                <?php endif; ?>
                
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                    <form action="actions/_sauvegarder_parametres.php" method="POST" enctype="multipart/form-data" class="space-y-8">
                        <div class="p-6 sm:p-8">
                            <div>
                                <label for="site_title" class="block text-sm font-semibold text-gray-600 dark:text-gray-300 mb-1">Titre du site</label>
                                <input type="text" name="site_title" id="site_title" value="<?= htmlspecialchars($settings['site_title']) ?>" required
                                       class="mt-1 block w-full px-4 py-2 bg-gray-50 dark:bg-gray-700 dark:text-gray-100 rounded-lg border-gray-200 dark:border-gray-600 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-150 ease-in-out">
                            </div>

                            <div>
                                <label for="site_logo" class="block text-sm font-semibold text-gray-600 dark:text-gray-300 mb-1">Logo du site</label>
                                <?php if ($settings['site_logo']): ?>
                                    <div class="mt-2 mb-4">
                                        <img src="<?= htmlspecialchars($settings['site_logo']) ?>" alt="Logo actuel" class="h-12 w-auto rounded-lg shadow-md dark:shadow-none">
                                        <p class="text-xs text-gray-500 mt-2">Logo actuel. Choisissez un nouveau fichier pour le remplacer.</p>
                                    </div>
                                <?php endif; ?>
                                <input type="file" name="site_logo" id="site_logo" class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-300 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 dark:file:bg-blue-800 file:text-blue-700 dark:file:text-blue-100 hover:file:bg-blue-100 dark:hover:file:bg-blue-700 cursor-pointer transition duration-150 ease-in-out"/>
                            </div>

                            <div>
                                <label for="theme" class="block text-sm font-semibold text-gray-600 dark:text-gray-300 mb-1">Thème du Dashboard</label>
                                <select name="theme" id="theme" class="mt-1 block w-full px-4 py-2 bg-gray-50 dark:bg-gray-700 dark:text-gray-100 rounded-lg border-gray-200 dark:border-gray-600 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-150 ease-in-out">
                                    <option value="light" <?= $settings['theme'] == 'light' ? 'selected' : '' ?>>Clair</option>
                                    <option value="dark" <?= $settings['theme'] == 'dark' ? 'selected' : '' ?>>Sombre</option>
                                </select>
                            </div>
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
