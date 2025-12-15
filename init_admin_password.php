<?php
// init_admin_password.php

require_once 'config.php';

// --- Configuration ---
$admin_user_id = 1; // ID de l'utilisateur administrateur à initialiser
$default_email = 'donchaminade@gmail.com'; // Email par défaut pour l'admin
$initial_password = 'Donchaminade@'; // Mot de passe initial (à changer IMMÉDIATEMENT après connexion)

// --- Exécution ---
try {
    // Vérifier si l'utilisateur existe
    $stmt = $pdo->prepare("SELECT id FROM users WHERE id = :id");
    $stmt->bindParam(':id', $admin_user_id, PDO::PARAM_INT);
    $stmt->execute();
    $user_exists = $stmt->fetchColumn();

    if (!$user_exists) {
        // Si l'utilisateur n'existe pas, on le crée (optionnel, selon l'initialisation de la BDD)
        echo "L'utilisateur administrateur (ID: {$admin_user_id}) n'existe pas encore. Création de l'utilisateur.<br>";
        $hashed_password = password_hash($initial_password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (id, name, email, password, created_at, updated_at) VALUES (:id, :name, :email, :password, NOW(), NOW())");
        $stmt->bindParam(':id', $admin_user_id, PDO::PARAM_INT);
        $stmt->bindValue(':name', 'Administrateur');
        $stmt->bindParam(':email', $default_email);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->execute();
        echo "Utilisateur 'Administrateur' créé avec l'email '{$default_email}' et le mot de passe initial '{$initial_password}'.<br>";
    } else {
        // Mettre à jour le mot de passe de l'utilisateur existant
        $hashed_password = password_hash($initial_password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET password = :password, email = :email WHERE id = :id");
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':email', $default_email);
        $stmt->bindParam(':id', $admin_user_id, PDO::PARAM_INT);
        $stmt->execute();
        echo "Mot de passe de l'administrateur (ID: {$admin_user_id}) mis à jour avec le mot de passe initial '{$initial_password}' et l'email '{$default_email}'.<br>";
    }

    echo "Initialisation réussie ! Connectez-vous avec l'email '{$default_email}' et le mot de passe '{$initial_password}'.<br>";
    echo "<p style='color: red; font-weight: bold;'>!!! TRÈS IMPORTANT : Supprimez ce fichier (init_admin_password.php) IMMÉDIATEMENT après l'avoir exécuté, ou déplacez-le hors de la portée publique de votre serveur. !!!</p>";

} catch (\PDOException $e) {
    echo "Erreur lors de l'initialisation : " . $e->getMessage();
}
?>
