<?php
require_once 'config.php';

// Vérifier si un ID est passé en paramètre et s'il est numérique
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $publicite_id = $_GET['id'];

    try {
        // Préparer la requête de mise à jour pour éviter les injections SQL
        $stmt = $pdo->prepare("UPDATE publicites SET is_active = 1 WHERE id = :id");
        
        // Lier la variable :id au paramètre de la requête
        $stmt->bindParam(':id', $publicite_id, PDO::PARAM_INT);
        
        // Exécuter la requête
        $stmt->execute();

    } catch (\PDOException $e) {
        error_log("Erreur lors de l'activation de la publicité : " . $e->getMessage());
        die("Une erreur est survenue lors de l'activation. Veuillez réessayer.");
    }
}

// Rediriger l'utilisateur vers la page d'accueil pour voir le tableau mis à jour
header('Location: index.php?success=publicite_activee');
exit;

?>
