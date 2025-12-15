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

    // Total des utilisateurs
    $total_users_stmt = $pdo->query('SELECT COUNT(*) FROM users');
    $total_users = $total_users_stmt->fetchColumn();

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
    <nav class="fixed top-0 left-0 right-0 z-50 bg-white shadow-sm h-16 flex items-center px-6 border-b">
        <div class="flex items-center justify-between w-full">
            <div class="flex items-center">
                <button class="p-2 rounded-lg hover:bg-gray-100 mr-4 lg:hidden" id="sidebarToggle">
                    <i data-feather="menu"></i>
                </button>
                <a href="index.php" class="text-xl font-bold text-gray-800 flex items-center">
                    <i data-feather="box" class="text-blue-600 mr-2"></i> ConseilBox
                </a>
            </div>
            
            <div class="flex items-center space-x-6">
                <div class="relative">
                    <input type="text" placeholder="Rechercher..." class="bg-gray-100 border-none rounded-lg pl-10 pr-4 py-2 w-40 focus:w-64 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300">
                    <i data-feather="search" class="absolute left-3 top-2.5 text-gray-500"></i>
                </div>
                
                <button class="p-2 rounded-lg hover:bg-gray-100 relative">
                    <i data-feather="bell"></i>
                    <span class="absolute top-0 right-0 h-2 w-2 rounded-full bg-red-500"></span>
                </button>
                
                <div class="relative ml-4">
                    <div class="flex items-center space-x-2 cursor-pointer" id="profileBtn">
                        <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-medium">IK</div>
                        <span class="font-medium">IrokouKaizen</span>
                    </div>
                </div>
            </div>
        </div>
    </nav>
<div class="flex">
        <aside class="fixed top-16 left-0 bottom-0 w-64 bg-white border-r border-gray-200 overflow-y-auto z-40 transition-all duration-300 transform -translate-x-full lg:translate-x-0" id="sidebar">
            <div class="px-4 py-6">
                <div class="space-y-6">
                    <div class="space-y-1">
                        <a href="index.php" class="flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
                            <i data-feather="home" class="mr-3 text-gray-500"></i>
                            Tableau de bord
                        </a>
                        
                        <a href="conseils.php" class="flex items-center justify-between px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100 bg-blue-50 text-blue-600">
                            <div class="flex items-center">
                                <i data-feather="file-text" class="mr-3 text-blue-500"></i>
                                Conseils
                            </div>
                            <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2 py-0.5 rounded-full"><?= $pending_conseils ?></span>
                        </a>
                        
                        <a href="publicites.php" class="flex items-center justify-between px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
                            <div class="flex items-center">
                                <i data-feather="alert-circle" class="mr-3 text-gray-500"></i>
                                Publicités
                            </div>
                            <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2 py-0.5 rounded-full"><?= $active_pubs ?></span>
                        </a>
                    </div>

                    <div class="pt-4 border-t border-gray-200">
                        <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Administration</h3>
                        
                        <div class="space-y-1">
                            <div class="group">
                                <div class="flex items-center justify-between px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100 cursor-pointer" id="usersMenuBtn">
                                    <div class="flex items-center">
                                        <i data-feather="users" class="mr-3 text-gray-500"></i>
                                        Utilisateurs
                                    </div>
                                    <i data-feather="chevron-down" class="text-gray-500 w-4 h-4 transform group-hover:rotate-180 transition-transform"></i>
                                </div>
                                <div class="pl-4 mt-1 space-y-1 hidden" id="usersMenu">
                                    <a href="#" class="block px-3 py-1.5 text-sm text-gray-600 hover:bg-gray-100 rounded-lg">Tous les utilisateurs</a>
                                    <a href="#" class="block px-3 py-1.5 text-sm text-gray-600 hover:bg-gray-100 rounded-lg">Rôles</a>
                                    <a href="#" class="block px-3 py-1.5 text-sm text-gray-600 hover:bg-gray-100 rounded-lg">Permissions</a>
                                </div>
                            </div>
                            
                            <div class="group">
                                <div class="flex items-center justify-between px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100 cursor-pointer" id="settingsMenuBtn">
                                    <div class="flex items-center">
                                        <i data-feather="settings" class="mr-3 text-gray-500"></i>
                                        Paramètres
                                    </div>
                                    <i data-feather="chevron-down" class="text-gray-500 w-4 h-4 transform group-hover:rotate-180 transition-transform"></i>
                                </div>
                                <div class="pl-4 mt-1 space-y-1 hidden" id="settingsMenu">
                                    <a href="#" class="block px-3 py-1.5 text-sm text-gray-600 hover:bg-gray-100 rounded-lg">Général</a>
                                    <a href="#" class="block px-3 py-1.5 text-sm text-gray-600 hover:bg-gray-100 rounded-lg">Notifications</a>
                                    <a href="#" class="block px-3 py-1.5 text-sm text-gray-600 hover:bg-gray-100 rounded-lg">Sécurité</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </aside>
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            feather.replace();
            
            // Toggle sidebar on mobile
            document.getElementById('sidebarToggle').addEventListener('click', function() {
                document.getElementById('sidebar').classList.toggle('-translate-x-full');
            });

            // Toggle dropdown menus
            document.getElementById('usersMenuBtn').addEventListener('click', function() {
                document.getElementById('usersMenu').classList.toggle('hidden');
            });

            document.getElementById('settingsMenuBtn').addEventListener('click', function() {
                document.getElementById('settingsMenu').classList.toggle('hidden');
            });
        });
    </script>
<script src="script.js"></script>
    <script>
        feather.replace();
        
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
<script src="https://huggingface.co/deepsite/deepsite-badge.js"></script>
</body>
</html>