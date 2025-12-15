<?php
require_once 'config.php';

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
<html lang="fr" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conseils - ConseilBox Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <script src="https://unpkg.com/feather-icons"></script>
</head>
<body class="h-full">
    <nav class="fixed top-0 left-0 right-0 z-50 bg-white shadow-sm h-16 flex items-center px-6 border-b">
        <!-- Navbar content -->
    </nav>
    
    <div class="flex">
        <aside class="fixed top-16 left-0 bottom-0 w-64 bg-white border-r border-gray-200 overflow-y-auto z-40 transition-all duration-300 transform -translate-x-full lg:translate-x-0" id="sidebar">
            <!-- Sidebar content -->
        </aside>
        
        <main class="flex-1 p-8 ml-64">
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
                            <!-- Table headers -->
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($conseils as $conseil): ?>
                                <tr>
                                    <!-- ... autres td ... -->
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <?php if ($conseil['status'] === 'pending'): ?>
                                                <a href="valider_conseil.php?id=<?= $conseil['id'] ?>" class="text-green-600 hover:text-green-900" title="Valider">
                                                    <i data-feather="check-circle"></i>
                                                </a>
                                            <?php endif; ?>
                                            <a href="#" class="text-blue-600 hover:text-blue-900 view-btn" title="Voir" data-id="<?= $conseil['id'] ?>">
                                                <i data-feather="eye"></i>
                                            </a>
                                            <a href="modifier_conseil.php?id=<?= $conseil['id'] ?>" class="text-blue-600 hover:text-blue-900" title="Modifier">
                                                <i data-feather="edit"></i>
                                            </a>
                                            <a href="supprimer_conseil.php?id=<?= $conseil['id'] ?>" class="text-red-600 hover:text-red-900" title="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce conseil ?');">
                                                <i data-feather="trash-2"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            feather.replace();
            
            const detailPanel = document.getElementById('detailPanel');
            const closeDetailPanelBtn = document.getElementById('closeDetailPanel');
            
            document.querySelectorAll('.view-btn').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const conseilId = this.getAttribute('data-id');
                    
                    fetch(`get_conseil_details.php?id=${conseilId}`)
                        .then(response => response.json())
                        .then(result => {
                            if (result.success) {
                                const data = result.data;
                                document.getElementById('detailTitle').textContent = data.title;
                                document.getElementById('detailAuthor').textContent = data.author;
                                document.getElementById('detailLocation').textContent = data.location || '-';
                                document.getElementById('detailDate').textContent = data.created_at_formatted;
                                document.getElementById('detailContent').textContent = data.content;
                                document.getElementById('detailAnecdote').textContent = data.anecdote || 'Aucune anecdote fournie.';
                                
                                detailPanel.classList.remove('translate-x-full');
                            } else {
                                alert(result.message);
                            }
                        })
                        .catch(error => {
                            console.error('Erreur:', error);
                            alert('Impossible de récupérer les détails.');
                        });
                });
            });
            
            closeDetailPanelBtn.addEventListener('click', function() {
                detailPanel.classList.add('translate-x-full');
            });
        });
    </script>
</body>
</html>