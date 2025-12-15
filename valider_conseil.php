<?php
require_once 'config.php';

// Vérifier si un ID est passé en paramètre et s'il est numérique
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $conseil_id = $_GET['id'];

    try {
        // Préparer la requête de mise à jour pour éviter les injections SQL
        $stmt = $pdo->prepare("UPDATE conseils SET status = 'published' WHERE id = :id");
        
        // Lier la variable :id au paramètre de la requête
        $stmt->bindParam(':id', $conseil_id, PDO::PARAM_INT);
        
        // Exécuter la requête
        $stmt->execute();

        // On pourrait ajouter une vérification pour voir si une ligne a bien été affectée
        // $affected_rows = $stmt->rowCount();
        // if ($affected_rows > 0) { ... }

    } catch (\PDOException $e) {
        // En cas d'erreur, on loggue le message et on peut afficher une erreur
        error_log("Erreur lors de la validation du conseil : " . $e->getMessage());
        // Pour l'utilisateur, on pourrait afficher un message d'erreur plus générique
        die("Une erreur est survenue lors de la mise à jour. Veuillez réessayer.");
    }
}

// Rediriger l'utilisateur vers la page des conseils
// header() doit être appelé avant toute sortie HTML
header('Location: conseils.php');
exit; // Toujours appeler exit() après une redirection pour s'assurer que le script s'arrête

?>
