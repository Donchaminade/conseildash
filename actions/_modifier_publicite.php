<?php
require_once '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id = trim($_POST['id'] ?? '');

    // --- Gestion de l'image ---
    $image_sql_part = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        // Supprimer l'ancienne image si elle existe
        $stmt = $pdo->prepare("SELECT image_url FROM publicites WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $old_image = $stmt->fetchColumn();
        if ($old_image && file_exists('../' . $old_image)) {
            unlink('../' . $old_image);
        }

        // Uploader la nouvelle image
        $upload_dir = '../publicites/';
        $file_name = uniqid() . '_' . basename($_FILES['image']['name']);
        $target_file = $upload_dir . $file_name;
        
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed_types = ['jpg', 'png', 'jpeg', 'gif'];
        if (in_array($imageFileType, $allowed_types) && move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $image_url = 'publicites/' . $file_name;
            $image_sql_part = ", image_url = :image_url";
        } else {
            header('Location: ../modifier_publicite.php?id=' . $id . '&error=upload_failed');
            exit;
        }
    }

    // --- Récupération des données ---
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $target_url = trim($_POST['target_url'] ?? null);
    $start_date = !empty($_POST['start_date']) ? trim($_POST['start_date']) : null;
    $end_date = !empty($_POST['end_date']) ? trim($_POST['end_date']) : null;
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    if (empty($id) || empty($title) || empty($content)) {
        header('Location: ../modifier_publicite.php?id=' . $id . '&error=champs_requis');
        exit;
    }

    try {
        // Préparer la requête
        $sql = "UPDATE publicites SET 
                    title = :title, 
                    content = :content, 
                    target_url = :target_url, 
                    start_date = :start_date, 
                    end_date = :end_date, 
                    is_active = :is_active,
                    updated_at = NOW()
                    $image_sql_part
                WHERE id = :id";
        
        $stmt = $pdo->prepare($sql);
        
        // Lier les paramètres
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':target_url', $target_url);
        $stmt->bindParam(':start_date', $start_date);
        $stmt->bindParam(':end_date', $end_date);
        $stmt->bindParam(':is_active', $is_active, PDO::PARAM_INT);
        if (!empty($image_sql_part)) {
            $stmt->bindParam(':image_url', $image_url);
        }
        
        $stmt->execute();
        
        header('Location: ../publicites.php?success=modification');
        exit;

    } catch (\PDOException $e) {
        error_log("Erreur lors de la modification de la publicité : " . $e->getMessage());
        header('Location: ../modifier_publicite.php?id=' . $id . '&error=db_error');
        exit;
    }
} else {
    header('Location: ../publicites.php');
    exit;
}
?>
