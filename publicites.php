<?php
require_once 'auth_check.php'; // Vérifie l'authentification avant tout

require_once 'config.php';
$settings = require ROOT_PATH . '/settings.php'; // Inclure les paramètres

// Récupérer toutes les publicités
try {
    $stmt = $pdo->query('SELECT * FROM publicites ORDER BY created_at DESC');
    $publicites = $stmt->fetchAll();
} catch (\PDOException $e) {
    error_log($e->getMessage());
    $publicites = [];
}
?>
<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Publicités - ConseilBox Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <script src="https://unpkg.com/feather-icons"></script>
</head>
<body class="h-full <?= $settings['theme'] === 'dark' ? 'dark' : '' ?>">
    <?php include 'includes/navbar.php'; ?>
    
    <div class="flex">
    <?php include 'includes/sidebar.php'; ?>
        
        <main class="flex-1 p-8 ml-64">
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
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Gestion des Publicités</h1>
                <a href="ajouter_publicite.php" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center">
                    <i data-feather="plus" class="mr-2 w-4 h-4"></i> Nouvelle publicité
                </a>
            </div>
            
            <div class="bg-white rounded-lg shadow overflow-hidden mb-8">
                <!-- Filtres et recherche -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <input type="checkbox" class="rounded text-blue-600">
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-auto">Image</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-auto">Titre</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-auto">Contenu</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-auto">URL Cible</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-auto">Début</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-auto">Fin</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-auto">Statut</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php if (empty($publicites)): ?>
                                <tr>
                                    <td colspan="9" class="px-6 py-4 text-center text-gray-500">Aucune publicité trouvée.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($publicites as $pub): ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="checkbox" class="rounded text-blue-600">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="h-10 w-10 rounded-md overflow-hidden bg-gray-100 flex items-center justify-center">
                                                <?php if ($pub['image_url']): ?>
                                                    <img src="<?= htmlspecialchars($pub['image_url']) ?>" alt="Publicité" class="h-full w-full object-cover">
                                                <?php else: ?>
                                                    <i data-feather="image" class="text-gray-400"></i>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 w-auto">
                                            <div class="text-sm font-medium text-gray-900"><?= htmlspecialchars($pub['title']) ?></div>
                                        </td>
                                        <td class="px-6 py-4 max-w-xs truncate">
                                            <div class="text-sm text-gray-800" title="<?= htmlspecialchars($pub['content']) ?>">
                                                <?= htmlspecialchars(strlen($pub['content']) > 70 ? substr($pub['content'], 0, 70) . '...' : $pub['content']) ?>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 w-auto">
                                            <div class="text-sm text-blue-600 hover:underline">
                                                <a href="<?= htmlspecialchars($pub['target_url']) ?>" target="_blank"><?= htmlspecialchars($pub['target_url']) ?></a>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 w-auto">
                                            <?= $pub['start_date'] ? date('d/m/Y', strtotime($pub['start_date'])) : '-' ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 w-auto">
                                            <?= $pub['end_date'] ? date('d/m/Y', strtotime($pub['end_date'])) : '-' ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap w-auto">
                                            <?php
                                                $status_color = $pub['is_active'] ? 'green' : 'red';
                                                $status_text = $pub['is_active'] ? 'Actif' : 'Inactif';
                                            ?>
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-<?= $status_color ?>-100 text-<?= $status_color ?>-800">
                                                <?= $status_text ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <a href="#" class="text-blue-600 hover:text-blue-900 view-btn" title="Voir" data-id="<?= $pub['id'] ?>" data-type="publicite">
                                                    <i data-feather="eye"></i>
                                                </a>
                                                <a href="modifier_publicite.php?id=<?= $pub['id'] ?>" class="text-blue-600 hover:text-blue-900" title="Modifier">
                                                    <i data-feather="edit"></i>
                                                </a>
                                                <a href="supprimer_publicite.php?id=<?= $pub['id'] ?>" class="text-red-600 hover:text-red-900" title="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette publicité ?');">
                                                    <i data-feather="trash-2"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
    
    <!-- Detail Panel for Publicités -->
    <div id="detailPanel" class="fixed right-0 top-0 h-full w-full md:w-1/3 bg-white shadow-lg z-[60] transform translate-x-full transition-transform duration-300 ease-in-out">
        <div class="flex justify-between items-center p-4 border-b">
            <h2 class="text-lg font-semibold">Détails de la Publicité</h2>
            <button id="closeDetailPanel" class="text-gray-500 hover:text-gray-700">
                <i data-feather="x"></i>
            </button>
        </div>
        <div class="p-6 space-y-4">
            <div class="w-full h-40 bg-gray-200 rounded-lg overflow-hidden">
                <img id="detailImage" src="" alt="Image de la publicité" class="w-full h-full object-cover">
            </div>
            <div>
                <h3 class="font-medium text-gray-500 text-sm">Titre</h3>
                <p id="detailTitle" class="text-gray-900 text-lg"></p>
            </div>
             <div>
                <h3 class="font-medium text-gray-500 text-sm">Statut</h3>
                <p id="detailStatus" class="text-gray-900"></p>
            </div>
            <div>
                <h3 class="font-medium text-gray-500 text-sm">URL Cible</h3>
                <a id="detailTargetUrl" href="#" target="_blank" class="text-blue-600 hover:underline"></a>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <h3 class="font-medium text-gray-500 text-sm">Date de début</h3>
                    <p id="detailStartDate" class="text-gray-900"></p>
                </div>
                <div>
                    <h3 class="font-medium text-gray-500 text-sm">Date de fin</h3>
                    <p id="detailEndDate" class="text-gray-900"></p>
                </div>
            </div>
            <div>
                <h3 class="font-medium text-gray-500 text-sm">Contenu</h3>
                <p id="detailContent" class="text-gray-800 bg-gray-50 p-3 rounded-md whitespace-pre-wrap"></p>
            </div>
        </div>
    </div>
    


    <script src="script.js"></script>
</body>
</html>