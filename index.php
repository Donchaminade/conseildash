<?php
require_once 'config.php';

// Récupérer les statistiques
try {
    // Total des conseils
    $total_conseils_stmt = $pdo->query('SELECT COUNT(*) FROM conseils');
    $total_conseils = $total_conseils_stmt->fetchColumn();

    // Conseils publiés
    $published_conseils_stmt = $pdo->query("SELECT COUNT(*) FROM conseils WHERE status = 'published'");
    $published_conseils = $published_conseils_stmt->fetchColumn();

    // Conseils en attente
    $pending_conseils_global_stmt = $pdo->query("SELECT COUNT(*) FROM conseils WHERE status = 'pending'");
    $pending_conseils_global = $pending_conseils_global_stmt->fetchColumn();

    // Total des utilisateurs
    $total_users_stmt = $pdo->query('SELECT COUNT(*) FROM users');
    $total_users = $total_users_stmt->fetchColumn();

    // Publicités actives
    $active_pubs_global_stmt = $pdo->query('SELECT COUNT(*) FROM publicites WHERE is_active = 1');
    $active_pubs_global = $active_pubs_global_stmt->fetchColumn();

    // Total des publicités
    $total_publicites_stmt = $pdo->query('SELECT COUNT(*) FROM publicites');
    $total_publicites = $total_publicites_stmt->fetchColumn();

    // Publicités inactives
    $inactive_publicites_stmt = $pdo->query('SELECT COUNT(*) FROM publicites WHERE is_active = 0');
    $inactive_publicites = $inactive_publicites_stmt->fetchColumn();

    // --- Statistiques et listes pour les conseillers ---
    // Total des conseillers uniques
    $total_conseillers_stmt = $pdo->query('SELECT COUNT(DISTINCT author) FROM conseils');
    $total_conseillers = $total_conseillers_stmt->fetchColumn();

    // Liste des conseillers uniques avec leur localisation
    $conseillers_list_stmt = $pdo->query('SELECT DISTINCT author, location FROM conseils ORDER BY author ASC LIMIT 5');
    $conseillers_list = $conseillers_list_stmt->fetchAll();

} catch (\PDOException $e) {
    error_log($e->getMessage());
    $total_conseils = $published_conseils = $pending_conseils = $total_users = $active_pubs = 0;
    $total_publicites = $inactive_publicites = 0;
    $pending_conseils_list = [];
    $inactive_publicites_list = [];
    $total_conseillers = 0;
    $conseillers_list = [];
}
?>
<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ConseilBox Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
</head>
<body class="h-full">
    <?php include 'includes/navbar.php'; ?>
<div class="flex">
    <?php include 'includes/sidebar.php'; ?>
<main class="flex-1 p-8 ml-64">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow p-6 flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                        <i data-feather="file-text"></i>
                    </div>
                    <div>
                        <h3 class="text-gray-500 text-sm font-medium">Total Conseils</h3>
                        <p class="text-2xl font-semibold"><?= $total_conseils ?></p>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow p-6 flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                        <i data-feather="check-circle"></i>
                    </div>
                    <div>
                        <h3 class="text-gray-500 text-sm font-medium">Conseils Publiés</h3>
                        <p class="text-2xl font-semibold"><?= $published_conseils ?></p>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow p-6 flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100 text-yellow-600 mr-4">
                        <i data-feather="clock"></i>
                    </div>
                    <div>
                        <h3 class="text-gray-500 text-sm font-medium">En Attente</h3>
                        <p class="text-2xl font-semibold"><?= $pending_conseils_global ?></p>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow p-6 flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 text-purple-600 mr-4">
                        <i data-feather="users"></i>
                    </div>
                    <div>
                        <h3 class="text-gray-500 text-sm font-medium">Conseillers</h3>
                        <p class="text-2xl font-semibold"><?= $total_conseillers ?></p>
                    </div>
                </div>

                <!-- Nouvelle carte pour Total Publicités -->
                <div class="bg-white rounded-lg shadow p-6 flex items-center">
                    <div class="p-3 rounded-full bg-orange-100 text-orange-600 mr-4">
                        <i data-feather="image"></i>
                    </div>
                    <div>
                        <h3 class="text-gray-500 text-sm font-medium">Total Publicités</h3>
                        <p class="text-2xl font-semibold"><?= $total_publicites ?></p>
                    </div>
                </div>

                <!-- Nouvelle carte pour Publicités Inactives -->
                <div class="bg-white rounded-lg shadow p-6 flex items-center">
                    <div class="p-3 rounded-full bg-red-100 text-red-600 mr-4">
                        <i data-feather="x-circle"></i>
                    </div>
                    <div>
                        <h3 class="text-gray-500 text-sm font-medium">Publicités Inactives</h3>
                        <p class="text-2xl font-semibold"><?= $inactive_publicites ?></p>
                    </div>
                </div>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow p-6 lg:col-span-2">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-semibold">Activité Récente</h2>
                        <div class="flex space-x-2">
                            <button class="px-3 py-1 text-xs bg-blue-100 text-blue-600 rounded">Mois</button>
                            <button class="px-3 py-1 text-xs bg-gray-100 text-gray-600 rounded">Année</button>
                        </div>
                    </div>
                    <div class="h-64">
                        <canvas id="activityChart"></canvas>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold mb-4">Statut des Conseils</h2>
                    <div class="h-64">
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow overflow-hidden mb-8">
                <div class="flex justify-between items-center p-6 border-b">
                    <h2 class="text-lg font-semibold">Suggestions de Conseils</h2>
                    <a href="conseils.php?status=pending" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Voir tout</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Titre</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Auteur</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php if (empty($pending_conseils_list)): ?>
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">Aucune suggestion de conseil.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($pending_conseils_list as $conseil): ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900"><?= htmlspecialchars($conseil['title']) ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500"><?= htmlspecialchars($conseil['author']) ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?= date('d/m/Y', strtotime($conseil['created_at'])) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <a href="#" class="text-blue-600 hover:text-blue-900 view-btn" title="Voir" data-id="<?= $conseil['id'] ?>" data-type="conseil">
                                                    <i data-feather="eye"></i>
                                                </a>
                                                <a href="valider_conseil.php?id=<?= $conseil['id'] ?>" class="text-green-600 hover:text-green-900" title="Valider">
                                                    <i data-feather="check-circle"></i>
                                                </a>
                                                <a href="supprimer_conseil.php?id=<?= $conseil['id'] ?>" class="text-red-600 hover:text-red-900" title="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce conseil ?');">
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

            <!-- Nouveau : Conseils à valider -->
            <div class="bg-white rounded-lg shadow overflow-hidden mb-8">
                <div class="flex justify-between items-center p-6 border-b">
                    <h2 class="text-lg font-semibold">Conseils à Valider</h2>
                    <a href="conseils.php?status=pending" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Voir tout</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Titre</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Auteur</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php if (empty($pending_conseils_list)): ?>
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">Aucun conseil en attente.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($pending_conseils_list as $conseil): ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900"><?= htmlspecialchars($conseil['title']) ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500"><?= htmlspecialchars($conseil['author']) ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?= date('d/m/Y', strtotime($conseil['created_at'])) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <a href="#" class="text-blue-600 hover:text-blue-900 view-btn" title="Voir" data-id="<?= $conseil['id'] ?>" data-type="conseil">
                                                    <i data-feather="eye"></i>
                                                </a>
                                                <a href="valider_conseil.php?id=<?= $conseil['id'] ?>" class="text-green-600 hover:text-green-900" title="Valider">
                                                    <i data-feather="check-circle"></i>
                                                </a>
                                                <a href="supprimer_conseil.php?id=<?= $conseil['id'] ?>" class="text-red-600 hover:text-red-900" title="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce conseil ?');">
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

            <!-- Nouveau : Publicités Inactives -->
            <div class="bg-white rounded-lg shadow overflow-hidden mb-8">
                <div class="flex justify-between items-center p-6 border-b">
                    <h2 class="text-lg font-semibold">Publicités Inactives</h2>
                    <a href="publicites.php?status=inactive" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Voir tout</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Titre</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php if (empty($inactive_publicites_list)): ?>
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">Aucune publicité inactive.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($inactive_publicites_list as $pub): ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="h-10 w-10 rounded-md overflow-hidden bg-gray-100 flex items-center justify-center">
                                                <?php if ($pub['image_url']): ?>
                                                    <img src="<?= htmlspecialchars($pub['image_url']) ?>" alt="Publicité" class="h-full w-full object-cover">
                                                <?php else: ?>
                                                    <i data-feather="image" class="text-gray-400"></i>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900"><?= htmlspecialchars($pub['title']) ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?= date('d/m/Y', strtotime($pub['created_at'])) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <a href="#" class="text-blue-600 hover:text-blue-900 view-btn" title="Voir" data-id="<?= $pub['id'] ?>" data-type="publicite">
                                                    <i data-feather="eye"></i>
                                                </a>
                                                <a href="valider_publicite.php?id=<?= $pub['id'] ?>" class="text-green-600 hover:text-green-900" title="Activer">
                                                    <i data-feather="check-circle"></i>
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
            
            <!-- Nouveau : Liste des Conseillers -->
            <div class="bg-white rounded-lg shadow overflow-hidden mb-8">
                <div class="flex justify-between items-center p-6 border-b">
                    <h2 class="text-lg font-semibold">Liste des Conseillers</h2>
                    <div class="flex space-x-2">
                        <a href="export_conseillers_excel.php" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center text-sm">
                            <i data-feather="file-text" class="mr-2 w-4 h-4"></i> Exporter Excel
                        </a>
                        <a href="export_conseillers_pdf.php" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg flex items-center text-sm">
                            <i data-feather="file" class="mr-2 w-4 h-4"></i> Exporter PDF
                        </a>
                    </div>
                </div>
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
                                                <!-- Action "Voir les conseils de ce conseiller" ou autre -->
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
    <script>
        // Activity Chart
        const activityCtx = document.getElementById('activityChart').getContext('2d');
        const activityChart = new Chart(activityCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Conseils',
                    data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 2, 1],
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 2,
                    tension: 0.1,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
        
        // Status Chart
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        const statusChart = new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Publiés', 'En attente'],
                datasets: [{
                    data: [<?= $published_conseils ?>, <?= $pending_conseils_global ?>],
                    backgroundColor: [
                        'rgba(16, 185, 129, 0.8)',
                        'rgba(245, 158, 11, 0.8)'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    </script>
</body>
</html>