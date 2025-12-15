<?php
require_once 'config.php';

// Vérifier si un ID est passé en paramètre et s'il est numérique
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $conseil_id = $_GET['id'];

    try {
        // Préparer la requête de suppression pour éviter les injections SQL
        $stmt = $pdo->prepare("DELETE FROM conseils WHERE id = :id");
        
        // Lier la variable :id au paramètre de la requête
        $stmt->bindParam(':id', $conseil_id, PDO::PARAM_INT);
        
        // Exécuter la requête
        $stmt->execute();

    } catch (\PDOException $e) {
        // En cas d'erreur, on loggue le message et on peut afficher une erreur
        error_log("Erreur lors de la suppression du conseil : " . $e->getMessage());
        die("Une erreur est survenue lors de la suppression. Veuillez réessayer.");
    }
}

// Rediriger l'utilisateur vers la page des conseils
header('Location: conseils.php');
exit;

?>
