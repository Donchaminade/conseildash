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
    $pending_conseils_stmt = $pdo->query("SELECT COUNT(*) FROM conseils WHERE status = 'pending'");
    $pending_conseils = $pending_conseils_stmt->fetchColumn();

    // // Total des utilisateurs
    // $total_users_stmt = $pdo->query('SELECT COUNT(*) FROM users');
    // $total_users = $total_users_stmt->fetchColumn();

    // Publicités actives
    $active_pubs_stmt = $pdo->query('SELECT COUNT(*) FROM publicites WHERE is_active = 1');
    $active_pubs = $active_pubs_stmt->fetchColumn();

} catch (\PDOException $e) {
    // En cas d'erreur, on peut afficher un message ou initialiser les variables à 0
    // Pour la production, il serait préférable de logger l'erreur.
    error_log($e->getMessage());
    $total_conseils = $published_conseils = $pending_conseils = $total_users = $active_pubs = 0;
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
                        <p class="text-2xl font-semibold"><?= $pending_conseils ?></p>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow p-6 flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 text-purple-600 mr-4">
                        <i data-feather="users"></i>
                    </div>
                    <div>
                        <h3 class="text-gray-500 text-sm font-medium">Utilisateurs</h3>
                        <p class="text-2xl font-semibold"><?= $total_users ?></p>
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
                    <h2 class="text-lg font-semibold">Derniers Conseils</h2>
                    <a href="conseils.php" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Voir tout</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Titre</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Auteur</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Localisation</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">Optimiser la visibilité de vos projets en ligne</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500">adc</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500">lome</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Publié</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">04/12/2025</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="#" class="text-blue-600 hover:text-blue-900 mr-3">Modifier</a>
                                    <a href="#" class="text-red-600 hover:text-red-900">Supprimer</a>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">je vinseil</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500">chaussures</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500">Niger</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Publié</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">04/12/2025</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="#" class="text-blue-600 hover:text-blue-900 mr-3">Modifier</a>
                                    <a href="#" class="text-red-600 hover:text-red-900">Supprimer</a>
                                </td>
                            </tr>
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
                    data: [<?= $published_conseils ?>, <?= $pending_conseils ?>],
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