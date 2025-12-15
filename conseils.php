<?php
require_once 'config.php';
$settings = require ROOT_PATH . '/settings.php'; // Inclure les paramètres

// Récupérer tous les conseils
try {
    $stmt = $pdo->query('SELECT * FROM conseils ORDER BY created_at DESC');
    $conseils = $stmt->fetchAll();
} catch (\PDOException $e) {
    error_log($e->getMessage());
    $conseils = [];
}
?>
<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conseils - ConseilBox Dashboard</title>
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
                <h1 class="text-2xl font-bold text-gray-800">Gestion des Conseils</h1>
                <a href="ajouter_conseil.php" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center">
                    <i data-feather="plus" class="mr-2 w-4 h-4"></i> Ajouter un conseil
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
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-auto">Titre</th>
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-auto">Auteur</th>
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-auto">Localisation</th>
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contenu</th>
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Anecdote</th>
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-auto">Statut</th>
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-auto">Date</th>
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white divide-y divide-gray-200">
                                                    <?php if (empty($conseils)): ?>
                                                        <tr>
                                                            <td colspan="9" class="px-6 py-4 text-center text-gray-500">Aucun conseil trouvé.</td>
                                                        </tr>
                                                    <?php else: ?>
                                                        <?php foreach ($conseils as $conseil): ?>
                                                            <tr>
                                                                <td class="px-6 py-4 whitespace-nowrap">
                                                                    <input type="checkbox" class="rounded text-blue-600">
                                                                </td>
                                                                <td class="px-6 py-4 w-auto">
                                                                    <div class="text-sm font-medium text-gray-900"><?= htmlspecialchars($conseil['title']) ?></div>
                                                                </td>
                                                                <td class="px-6 py-4 w-auto">
                                                                    <div class="text-sm text-gray-900"><?= htmlspecialchars($conseil['author']) ?></div>
                                                                </td>
                                                                <td class="px-6 py-4 w-auto">
                                                                    <div class="text-sm text-gray-500"><?= htmlspecialchars($conseil['location']) ?></div>
                                                                </td>
                                                                <td class="px-6 py-4 max-w-xs truncate">
                                                                    <div class="text-sm text-gray-800" title="<?= htmlspecialchars($conseil['content']) ?>">
                                                                        <?= htmlspecialchars(strlen($conseil['content']) > 70 ? substr($conseil['content'], 0, 70) . '...' : $conseil['content']) ?>
                                                                    </div>
                                                                </td>
                                                                <td class="px-6 py-4 max-w-xs truncate">
                                                                    <div class="text-sm text-gray-800" title="<?= htmlspecialchars($conseil['anecdote'] ?? 'N/A') ?>">
                                                                        <?= htmlspecialchars(strlen($conseil['anecdote'] ?? '') > 70 ? substr($conseil['anecdote'], 0, 70) . '...' : ($conseil['anecdote'] ?? 'N/A')) ?>
                                                                    </div>
                                                                </td>
                                                                <td class="px-6 py-4 whitespace-nowrap w-auto">
                                                                    <?php
                                                                        $status_color = $conseil['status'] === 'published' ? 'green' : 'yellow';
                                                                        $status_text = $conseil['status'] === 'published' ? 'Publié' : 'En attente';
                                                                    ?>
                                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-<?= $status_color ?>-100 text-<?= $status_color ?>-800">
                                                                        <?= $status_text ?>
                                                                    </span>
                                                                </td>
                                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 w-auto">
                                                                    <?= date('d/m/Y', strtotime($conseil['created_at'])) ?>
                                                                </td>
                                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                                    <div class="flex space-x-2">
                                                                        <?php if ($conseil['status'] === 'pending'): ?>
                                                                            <a href="valider_conseil.php?id=<?= $conseil['id'] ?>" class="text-green-600 hover:text-green-900" title="Valider">
                                                                                <i data-feather="check-circle"></i>
                                                                            </a>
                                                                        <?php endif; ?>
                                                                                                                                                    <a href="#" class="text-blue-600 hover:text-blue-900 view-btn" title="Voir" data-id="<?= $conseil['id'] ?>" data-type="conseil">
                                                                                                                                                        <i data-feather="eye"></i>
                                                                                                                                                    </a>                                                                        <a href="modifier_conseil.php?id=<?= $conseil['id'] ?>" class="text-blue-600 hover:text-blue-900" title="Modifier">
                                                                            <i data-feather="edit"></i>
                                                                        </a>
                                                                        <a href="supprimer_conseil.php?id=<?= $conseil['id'] ?>" class="text-red-600 hover:text-red-900" title="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce conseil ?');">
                                                                            <i data-feather="trash-2"></i>
                                                                        </a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </tbody>                    </table>
                </div>
                <!-- Pagination -->
            </div>
        </main>
    </div>
    
    <!-- Detail Panel -->
    <div id="detailPanel" class="fixed right-0 top-0 h-full w-full md:w-1/3 bg-white shadow-lg z-[60] transform translate-x-full transition-transform duration-300 ease-in-out">
        <div class="flex justify-between items-center p-4 border-b">
            <h2 class="text-lg font-semibold">Détails du Conseil</h2>
            <button id="closeDetailPanel" class="text-gray-500 hover:text-gray-700">
                <i data-feather="x"></i>
            </button>
        </div>
        <div class="p-6 space-y-4">
            <div>
                <h3 class="font-medium text-gray-500 text-sm">Titre</h3>
                <p id="detailTitle" class="text-gray-900 text-lg"></p>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <h3 class="font-medium text-gray-500 text-sm">Auteur</h3>
                    <p id="detailAuthor" class="text-gray-900"></p>
                </div>
                <div>
                    <h3 class="font-medium text-gray-500 text-sm">Localisation</h3>
                    <p id="detailLocation" class="text-gray-900"></p>
                </div>
            </div>
             <div>
                <h3 class="font-medium text-gray-500 text-sm">Date de création</h3>
                <p id="detailDate" class="text-gray-900"></p>
            </div>
            <div>
                <h3 class="font-medium text-gray-500 text-sm">Contenu</h3>
                <p id="detailContent" class="text-gray-800 bg-gray-50 p-3 rounded-md whitespace-pre-wrap"></p>
            </div>
            <div>
                <h3 class="font-medium text-gray-500 text-sm">Anecdote</h3>
                <p id="detailAnecdote" class="text-gray-800 bg-gray-50 p-3 rounded-md whitespace-pre-wrap"></p>
            </div>
        </div>
    </div>
    

    <script src="script.js"></script>
</body>
</html>