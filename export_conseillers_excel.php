<?php
require_once 'config.php';

try {
    // Récupérer la liste complète des conseillers uniques avec leur localisation
    $stmt = $pdo->query('SELECT DISTINCT author, location FROM conseils ORDER BY author ASC');
    $conseillers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Nom du fichier CSV
    $filename = "liste_conseillers_" . date('Ymd') . ".csv";

    // Définir les en-têtes HTTP pour le téléchargement CSV
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');

    // Ouvrir le flux de sortie
    $output = fopen('php://output', 'w');

    // Écrire l'en-tête du CSV
    fputcsv($output, ['Conseiller', 'Localisation']);

    // Écrire les données
    foreach ($conseillers as $conseiller) {
        fputcsv($output, [$conseiller['author'], $conseiller['location'] ?? 'Non spécifié']);
    }

    fclose($output);
    exit;

} catch (\PDOException $e) {
    error_log("Erreur lors de l'exportation Excel des conseillers : " . $e->getMessage());
    die("Une erreur est survenue lors de l'exportation des données.");
}
?>
