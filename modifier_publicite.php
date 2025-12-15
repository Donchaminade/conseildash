<?php
require_once 'config.php';

$pub = null;
$error_message = '';

// 1. Valider l'ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $error_message = "ID de publicité non valide.";
} else {
    $publicite_id = $_GET['id'];

    // 2. Récupérer les données de la BDD
    try {
        $stmt = $pdo->prepare("SELECT * FROM publicites WHERE id = :id");
        $stmt->bindParam(':id', $publicite_id, PDO::PARAM_INT);
        $stmt->execute();
        $pub = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$pub) {
            $error_message = "Aucune publicité trouvée avec cet ID.";
        }
    } catch (\PDOException $e) {
        error_log($e->getMessage());
        $error_message = "Erreur lors de la récupération des données.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier une Publicité - ConseilBox Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="h-full bg-gray-100">
    <main class="flex-1 p-4 sm:p-8 ml-0 lg:ml-64">
        <div class="max-w-3xl mx-auto">
             <div class="flex items-center mb-6">
                <a href="publicites.php" class="text-gray-500 hover:text-gray-700 transition-colors">
                    <i data-feather="arrow-left-circle" class="w-6 h-6"></i>
                </a>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 ml-4">Modifier une Publicité</h1>
            </div>

            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <?php if ($error_message): ?>
                    <div class="p-8">
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md animate-fadeIn" role="alert">
                            <p><strong class="font-bold">Erreur !</strong> <?= htmlspecialchars($error_message) ?></p>
                        </div>
                    </div>
                <?php elseif ($pub): ?>
                    <form action="actions/_modifier_publicite.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($pub['id']) ?>">
                        
                        <div class="p-6 sm:p-8 space-y-8">
                            <div>
                                <label for="title" class="block text-sm font-semibold text-gray-600 mb-1">Titre</label>
                                <input type="text" name="title" id="title" value="<?= htmlspecialchars($pub['title']) ?>" required
                                       class="mt-1 block w-full px-4 py-2 bg-gray-50 rounded-lg border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-150 ease-in-out">
                            </div>

                            <div>
                                <label for="content" class="block text-sm font-semibold text-gray-600 mb-1">Contenu</label>
                                <textarea name="content" id="content" rows="4" required
                                          class="mt-1 block w-full px-4 py-2 bg-gray-50 rounded-lg border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-150 ease-in-out"><?= htmlspecialchars($pub['content']) ?></textarea>
                            </div>

                            <div>
                                <label for="target_url" class="block text-sm font-semibold text-gray-600 mb-1">URL Cible</label>
                                <input type="url" name="target_url" id="target_url" value="<?= htmlspecialchars($pub['target_url']) ?>"
                                       class="mt-1 block w-full px-4 py-2 bg-gray-50 rounded-lg border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-150 ease-in-out">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-600 mb-1">Image</label>
                                <?php if ($pub['image_url']): ?>
                                    <div class="mt-2 mb-4">
                                        <img src="<?= htmlspecialchars($pub['image_url']) ?>" alt="Image actuelle" class="h-24 w-auto rounded-lg shadow-md">
                                        <p class="text-xs text-gray-500 mt-2">Image actuelle. Choisissez un nouveau fichier pour la remplacer.</p>
                                    </div>
                                <?php endif; ?>
                                <label for="image" class="cursor-pointer mt-2 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition duration-150 ease-in-out">
                                    <input type="file" name="image" id="image" class="sr-only"/>
                                    <span>Choisir une nouvelle image...</span>
                                </label>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="start_date" class="block text-sm font-semibold text-gray-600 mb-1">Date de début</label>
                                    <input type="date" name="start_date" id="start_date" value="<?= $pub['start_date'] ? date('Y-m-d', strtotime($pub['start_date'])) : '' ?>" class="mt-1 block w-full px-4 py-2 bg-gray-50 rounded-lg border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-150 ease-in-out">
                                </div>
                                <div>
                                    <label for="end_date" class="block text-sm font-semibold text-gray-600 mb-1">Date de fin</label>
                                    <input type="date" name="end_date" id="end_date" value="<?= $pub['end_date'] ? date('Y-m-d', strtotime($pub['end_date'])) : '' ?>" class="mt-1 block w-full px-4 py-2 bg-gray-50 rounded-lg border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-150 ease-in-out">
                                </div>
                            </div>

                            <div class="flex items-center">
                                <input id="is_active" name="is_active" type="checkbox" value="1" class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" <?= $pub['is_active'] ? 'checked' : '' ?>>
                                <label for="is_active" class="ml-3 block text-sm font-semibold text-gray-700">Activer cette publicité</label>
                            </div>
                        </div>

                        <div class="bg-gray-50 px-6 py-4 flex items-center justify-end gap-x-3">
                            <a href="publicites.php" class="bg-white py-2 px-5 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-150 ease-in-out">Annuler</a>
                            <button type="submit" class="inline-flex justify-center py-2 px-5 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transform hover:scale-105 transition-all duration-150 ease-in-out">
                                <i data-feather="check" class="w-4 h-4 mr-2"></i>
                                Enregistrer
                            </button>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </main>
     <script>
        feather.replace();
    </script>
</body>
</html>
