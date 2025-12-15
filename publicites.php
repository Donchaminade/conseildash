<?php
require_once 'config.php';

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
<html lang="fr" class="h-full bg-gray-50">
<head>
    <!-- Head content -->
</head>
<body class="h-full">
    <nav class="fixed top-0 left-0 right-0 z-50 bg-white shadow-sm h-16 flex items-center px-6 border-b">
        <!-- Navbar -->
    </nav>
    
    <div class="flex">
        <aside class="fixed top-16 left-0 bottom-0 w-64 bg-white border-r border-gray-200 overflow-y-auto z-40 ...">
            <!-- Sidebar -->
        </aside>
        
        <main class="flex-1 p-8 ml-64">
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
                        <!-- ... thead ... -->
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($publicites as $pub): ?>
                                <tr>
                                    <!-- ... autres td ... -->
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="#" class="text-blue-600 hover:text-blue-900 view-btn" title="Voir" data-id="<?= $pub['id'] ?>">
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            feather.replace();
            
            const detailPanel = document.getElementById('detailPanel');
            const closeDetailPanelBtn = document.getElementById('closeDetailPanel');
            
            document.querySelectorAll('.view-btn').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const pubId = this.getAttribute('data-id');
                    
                    fetch(`get_publicite_details.php?id=${pubId}`)
                        .then(response => response.json())
                        .then(result => {
                            if (result.success) {
                                const data = result.data;
                                document.getElementById('detailImage').src = data.image_url || 'https://via.placeholder.com/300x150?text=Pas+d\'image';
                                document.getElementById('detailTitle').textContent = data.title;
                                document.getElementById('detailStatus').textContent = data.status_text;
                                const targetUrlLink = document.getElementById('detailTargetUrl');
                                targetUrlLink.href = data.target_url;
                                targetUrlLink.textContent = data.target_url;
                                document.getElementById('detailStartDate').textContent = data.start_date_formatted;
                                document.getElementById('detailEndDate').textContent = data.end_date_formatted;
                                document.getElementById('detailContent').textContent = data.content;
                                
                                detailPanel.classList.remove('translate-x-full');
                            } else {
                                alert(result.message);
                            }
                        })
                        .catch(error => console.error('Erreur:', error));
                });
            });
            
            closeDetailPanelBtn.addEventListener('click', () => {
                detailPanel.classList.add('translate-x-full');
            });
        });
    </script>
</body>
</html>