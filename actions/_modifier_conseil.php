<?php
require_once '../config.php';

// Vérifier si le formulaire a été soumis en méthode POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Récupérer et nettoyer les données du formulaire
    $id = trim($_POST['id'] ?? '');
    $title = trim($_POST['title'] ?? '');
    $author = trim($_POST['author'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $anecdote = trim($_POST['anecdote'] ?? null);
    $status = trim($_POST['status'] ?? 'pending');

    // Valider les données (ID, champs requis)
    if (empty($id) || !is_numeric($id) || empty($title) || empty($author) || empty($content)) {
        header('Location: ../modifier_conseil.php?id=' . $id . '&error=champs_requis');
        exit;
    }

    try {
        // Préparer la requête de mise à jour
        $sql = "UPDATE conseils SET 
                    title = :title, 
                    author = :author, 
                    location = :location, 
                    content = :content, 
                    anecdote = :anecdote, 
                    status = :status,
                    updated_at = NOW()
                WHERE id = :id";
        
        $stmt = $pdo->prepare($sql);
        
        // Lier les paramètres
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':author', $author);
        $stmt->bindParam(':location', $location);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':anecdote', $anecdote);
        $stmt->bindParam(':status', $status);
        
        // Exécuter la requête
        $stmt->execute();
        
        // Rediriger vers la page des conseils avec un message de succès
        header('Location: ../conseils.php?success=modification');
        exit;

    } catch (\PDOException $e) {
        // En cas d'erreur, on loggue et on peut rediriger avec un message d'erreur
        error_log("Erreur lors de la modification du conseil : " . $e->getMessage());
        header('Location: ../modifier_conseil.php?id=' . $id . '&error=db_error');
        exit;
    }

} else {
    // Si le script n'est pas accédé via POST, rediriger
    header('Location: ../conseils.php');
    exit;
}
?>
