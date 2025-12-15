<?php
require_once '../config.php';

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // --- Gestion de l'upload de l'image ---
    $image_url = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $upload_dir = '../publicites/'; // Assurez-vous que ce dossier existe et est accessible en écriture
        // Crée un nom de fichier unique pour éviter les collisions
        $file_name = uniqid() . '_' . basename($_FILES['image']['name']);
        $target_file = $upload_dir . $file_name;
        
        // Vérifier le type de fichier (optionnel mais recommandé)
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed_types = ['jpg', 'png', 'jpeg', 'gif'];
        if (in_array($imageFileType, $allowed_types)) {
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                // Le chemin à stocker en BDD est relatif au dossier public
                $image_url = 'publicites/' . $file_name;
            } else {
                header('Location: ../ajouter_publicite.php?error=upload_failed');
                exit;
            }
        } else {
            header('Location: ../ajouter_publicite.php?error=invalid_file_type');
            exit;
        }
    }

    // --- Récupération des données du formulaire ---
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $target_url = trim($_POST['target_url'] ?? null);
    $start_date = !empty($_POST['start_date']) ? trim($_POST['start_date']) : null;
    $end_date = !empty($_POST['end_date']) ? trim($_POST['end_date']) : null;
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    // Valider les données
    if (empty($title) || empty($content)) {
        header('Location: ../ajouter_publicite.php?error=champs_requis');
        exit;
    }

    try {
        // Préparer la requête d'insertion
        $sql = "INSERT INTO publicites (title, content, image_url, target_url, start_date, end_date, is_active, created_at, updated_at) 
                VALUES (:title, :content, :image_url, :target_url, :start_date, :end_date, :is_active, NOW(), NOW())";
        
        $stmt = $pdo->prepare($sql);
        
        // Lier les paramètres
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':image_url', $image_url);
        $stmt->bindParam(':target_url', $target_url);
        $stmt->bindParam(':start_date', $start_date);
        $stmt->bindParam(':end_date', $end_date);
        $stmt->bindParam(':is_active', $is_active, PDO::PARAM_INT);
        
        // Exécuter la requête
        $stmt->execute();
        
        header('Location: ../publicites.php?success=ajout');
        exit;

    } catch (\PDOException $e) {
        error_log("Erreur lors de l'ajout de la publicité : " . $e->getMessage());
        header('Location: ../ajouter_publicite.php?error=db_error');
        exit;
    }
} else {
    header('Location: ../publicites.php');
    exit;
}
?>
