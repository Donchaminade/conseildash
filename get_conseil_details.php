<?php
// get_conseil_details.php

// Inclure la configuration de la base de données
require_once 'config.php';

// Définir le header de la réponse en JSON
header('Content-Type: application/json');

// Initialiser la réponse par défaut (erreur)
$response = ['success' => false, 'message' => 'ID non fourni ou invalide.'];

// Vérifier si un ID est passé en paramètre et s'il est numérique
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $conseil_id = $_GET['id'];

    try {
        // Préparer et exécuter la requête pour récupérer le conseil
        $stmt = $pdo->prepare("SELECT * FROM conseils WHERE id = :id");
        $stmt->bindParam(':id', $conseil_id, PDO::PARAM_INT);
        $stmt->execute();
        $conseil = $stmt->fetch(PDO::FETCH_ASSOC);

        // Si un conseil est trouvé, préparer une réponse positive
        if ($conseil) {
            // Formatter la date pour un affichage plus propre
            $conseil['created_at_formatted'] = date('d/m/Y à H:i', strtotime($conseil['created_at']));
            $response = ['success' => true, 'data' => $conseil];
        } else {
            $response['message'] = 'Aucun conseil trouvé avec cet ID.';
        }
    } catch (\PDOException $e) {
        // En cas d'erreur de base de données
        error_log($e->getMessage());
        $response['message'] = 'Erreur de base de données.';
    }
}

// Envoyer la réponse JSON
echo json_encode($response);
exit;
?>
