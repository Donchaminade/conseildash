<?php
require_once 'auth_check.php'; // Vérifie l'authentification avant tout

require_once 'config.php';
$settings = require ROOT_PATH . '/settings.php'; // Inclure les paramètres

// Récupérer la liste complète des conseillers uniques avec leur localisation
try {
    $stmt = $pdo->query('SELECT DISTINCT author, location FROM conseils ORDER BY author ASC');
    $conseillers_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (\PDOException $e) {
    error_log("Erreur lors de la récupération des conseillers : " . $e->getMessage());
    $conseillers_list = [];
}
?>
<!DOCTYPE html>
<html lang="fr" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Conseillers - ConseilBox Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <script src="https://unpkg.com/feather-icons"></script>
</head>
<body class="h-full">
    <?php include 'includes/navbar.php'; ?>
    <div class="flex">
        <?php include 'includes/sidebar.php'; ?>
        
        <main class="flex-1 p-8 ml-64">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Liste des Conseillers</h1>
                <div class="flex space-x-2">
                    <a href="export_conseillers_excel.php" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center text-sm">
                        <i data-feather="file-text" class="mr-2 w-4 h-4"></i> Exporter Excel
                    </a>
                    <a href="export_conseillers_pdf.php" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg flex items-center text-sm">
                        <i data-feather="file" class="mr-2 w-4 h-4"></i> Exporter PDF
                    </a>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow overflow-hidden mb-8">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Conseiller</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Localisation</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php if (empty($conseillers_list)): ?>
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-center text-gray-500">Aucun conseiller trouvé.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($conseillers_list as $conseiller): ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900"><?= htmlspecialchars($conseiller['author']) ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500"><?= htmlspecialchars($conseiller['location'] ?? 'Non spécifié') ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <a href="conseils.php?author=<?= urlencode($conseiller['author']) ?>" class="text-blue-600 hover:text-blue-900" title="Voir les conseils">
                                                    <i data-feather="eye"></i>
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
    <script src="script.js"></script>
</body>
</html>
