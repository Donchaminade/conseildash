<?php
require_once 'config.php';

try {
    // Récupérer la liste complète des conseillers uniques avec leur localisation
    $stmt = $pdo->query('SELECT DISTINCT author, location FROM conseils ORDER BY author ASC');
    $conseillers = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (\PDOException $e) {
    error_log("Erreur lors de la récupération des conseillers pour PDF : " . $e->getMessage());
    $conseillers = [];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Export PDF - Liste des Conseillers</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <style>
        body { margin: 20px; font-family: sans-serif; }
        .container { max-width: 800px; margin: auto; padding: 20px; border: 1px solid #eee; }
        h1 { color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <div class="container" id="pdfContent">
        <h1 class="text-2xl font-bold mb-4">Liste des Conseillers</h1>
        
        <?php if (empty($conseillers)): ?>
            <p>Aucun conseiller à exporter.</p>
        <?php else: ?>
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Conseiller</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Localisation</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($conseillers as $conseiller): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= htmlspecialchars($conseiller['author']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($conseiller['location'] ?? 'Non spécifié') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <div class="text-center mt-6 no-print">
        <button id="downloadPdf" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center mx-auto">
            <i data-feather="download" class="mr-2 w-4 h-4"></i> Télécharger le PDF
        </button>
    </div>

    <script>
        feather.replace();
        document.getElementById('downloadPdf').addEventListener('click', function() {
            const element = document.getElementById('pdfContent');
            html2pdf(element, {
                margin: 10,
                filename: 'liste_conseillers.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2 },
                jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
            });
        });
    </script>
</body>
</html>
