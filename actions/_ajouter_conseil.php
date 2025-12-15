<?php
require_once '../config.php';

// Vérifier si le formulaire a été soumis en méthode POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Récupérer et nettoyer les données du formulaire
    $title = trim($_POST['title'] ?? '');
    $author = trim($_POST['author'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $anecdote = trim($_POST['anecdote'] ?? null);
    $status = trim($_POST['status'] ?? 'pending');

    // Valider les données (vérifier que les champs requis ne sont pas vides)
    if (empty($title) || empty($author) || empty($content)) {
        // Gérer l'erreur, par exemple en redirigeant avec un message
        header('Location: ajouter_conseil.php?error=champs_requis');
        exit;
    }

    try {
        // Préparer la requête d'insertion
        $sql = "INSERT INTO conseils (title, author, location, content, anecdote, status, created_at, updated_at) 
                VALUES (:title, :author, :location, :content, :anecdote, :status, NOW(), NOW())";
        
        $stmt = $pdo->prepare($sql);
        
        // Lier les paramètres
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':author', $author);
        $stmt->bindParam(':location', $location);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':anecdote', $anecdote);
        $stmt->bindParam(':status', $status);
        
        // Exécuter la requête
        $stmt->execute();
        
        // Rediriger vers la page des conseils avec un message de succès
        header('Location: conseils.php?success=ajout');
        exit;

    } catch (\PDOException $e) {
        // En cas d'erreur, on loggue et on peut rediriger avec un message d'erreur
        error_log("Erreur lors de l'ajout du conseil : " . $e->getMessage());
        header('Location: ajouter_conseil.php?error=db_error');
        exit;
    }

} else {
    // Si le script n'est pas accédé via POST, rediriger
    header('Location: ajouter_conseil.php');
    exit;
}
?>
