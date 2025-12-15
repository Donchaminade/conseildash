<?php
// get_publicite_details.php

require_once 'config.php';

header('Content-Type: application/json');

$response = ['success' => false, 'message' => 'ID non fourni ou invalide.'];

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $publicite_id = $_GET['id'];

    try {
        $stmt = $pdo->prepare("SELECT * FROM publicites WHERE id = :id");
        $stmt->bindParam(':id', $publicite_id, PDO::PARAM_INT);
        $stmt->execute();
        $publicite = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($publicite) {
            $publicite['start_date_formatted'] = $publicite['start_date'] ? date('d/m/Y', strtotime($publicite['start_date'])) : 'Non définie';
            $publicite['end_date_formatted'] = $publicite['end_date'] ? date('d/m/Y', strtotime($publicite['end_date'])) : 'Non définie';
            $publicite['status_text'] = $publicite['is_active'] ? 'Actif' : 'Inactif';
            $response = ['success' => true, 'data' => $publicite];
        } else {
            $response['message'] = 'Aucune publicité trouvée avec cet ID.';
        }
    } catch (\PDOException $e) {
        error_log($e->getMessage());
        $response['message'] = 'Erreur de base de données.';
    }
}

echo json_encode($response);
exit;
?>
