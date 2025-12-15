<?php require_once 'config.php'; ?>
<!DOCTYPE html>
<html lang="fr" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Conseil - ConseilBox Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <script src="https://unpkg.com/feather-icons"></script>
</head>
<body class="h-full bg-gray-100">
    <?php include 'includes/navbar.php'; ?>
    <div class="flex">
        <?php include 'includes/sidebar.php'; ?>
        
    <main class="flex-1 p-4 sm:p-8 ml-0 lg:ml-64">
        <div class="max-w-3xl mx-auto">
            <div class="flex items-center mb-6">
                <a href="conseils.php" class="text-gray-500 hover:text-gray-700 transition-colors">
                    <i data-feather="arrow-left-circle" class="w-6 h-6"></i>
                </a>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 ml-4">Ajouter un Conseil</h1>
            </div>

            <?php if (isset($_GET['error'])): ?>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        showAlert("Erreur : <?= htmlspecialchars($_GET['error']) ?>", 'error');
                    });
                </script>
            <?php elseif (isset($_GET['success'])): ?>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        showAlert("Succès : <?= htmlspecialchars($_GET['success']) ?>", 'success');
                    });
                </script>
            <?php endif; ?>
            
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="p-6 sm:p-8">
                    <form action="actions/_ajouter_conseil.php" method="POST" class="space-y-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="title" class="block text-sm font-semibold text-gray-600 mb-1">Titre du conseil</label>
                                <input type="text" name="title" id="title" required 
                                       class="mt-1 block w-full px-4 py-2 bg-gray-50 rounded-lg border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-150 ease-in-out">
                            </div>
                            <div>
                                <label for="author" class="block text-sm font-semibold text-gray-600 mb-1">Auteur</label>
                                <input type="text" name="author" id="author" required 
                                       class="mt-1 block w-full px-4 py-2 bg-gray-50 rounded-lg border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-150 ease-in-out">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="location" class="block text-sm font-semibold text-gray-600 mb-1">Localisation</label>
                                <input type="text" name="location" id="location" 
                                       class="mt-1 block w-full px-4 py-2 bg-gray-50 rounded-lg border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-150 ease-in-out">
                            </div>
                            <div>
                                <label for="status" class="block text-sm font-semibold text-gray-600 mb-1">Statut</label>
                                <select name="status" id="status" class="mt-1 block w-full px-4 py-2 bg-gray-50 rounded-lg border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-150 ease-in-out">
                                    <option value="pending" selected>En attente</option>
                                    <option value="published">Publié</option>
                                </select>
                            </div>
                        </div>
                        
                        <div>
                            <label for="content" class="block text-sm font-semibold text-gray-600 mb-1">Contenu</label>
                            <textarea name="content" id="content" rows="6" required 
                                      class="mt-1 block w-full px-4 py-2 bg-gray-50 rounded-lg border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-150 ease-in-out"></textarea>
                        </div>

                        <div>
                            <label for="anecdote" class="block text-sm font-semibold text-gray-600 mb-1">Anecdote</label>
                            <textarea name="anecdote" id="anecdote" rows="3" 
                                      class="mt-1 block w-full px-4 py-2 bg-gray-50 rounded-lg border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-150 ease-in-out"></textarea>
                        </div>

                        <div class="pt-4 flex items-center justify-end gap-x-3">
                            <a href="conseils.php" class="bg-white py-2 px-5 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-150 ease-in-out">Annuler</a>
                            <button type="submit" class="inline-flex justify-center py-2 px-5 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transform hover:scale-105 transition-all duration-150 ease-in-out">
                                <i data-feather="check" class="w-4 h-4 mr-2"></i>
                                Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
    <script src="script.js"></script>
</body>
</html>
