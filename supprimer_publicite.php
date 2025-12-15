<?php
require_once 'config.php';

// Vérifier si un ID est passé en paramètre et s'il est numérique
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $publicite_id = $_GET['id'];

    try {
        // Préparer la requête de suppression
        $stmt = $pdo->prepare("DELETE FROM publicites WHERE id = :id");
        
        // Lier le paramètre
        $stmt->bindParam(':id', $publicite_id, PDO::PARAM_INT);
        
        // Exécuter la requête
        $stmt->execute();

    } catch (\PDOException $e) {
        // En cas d'erreur, logguer et arrêter
        error_log("Erreur lors de la suppression de la publicité : " . $e->getMessage());
        die("Une erreur est survenue lors de la suppression. Veuillez réessayer.");
    }
}

// Rediriger vers la page des publicités
header('Location: publicites.php');
exit;

?>
