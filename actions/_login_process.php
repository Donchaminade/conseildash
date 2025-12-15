<?php
// actions/_login_process.php

session_start(); // Démarre une session PHP pour gérer la connexion

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../config.php'; // Inclure la connexion à la base de données

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $auth_method = $_POST['authMethod'] ?? 'password'; // Méthode d'authentification choisie

    // Pour l'instant, nous ne gérons que l'authentification par mot de passe.
    // La méthode OTP statique sera supprimée ou nécessitera une implémentation complète.
    if ($auth_method === 'password') {
        $email_or_code = $_POST['email'] ?? ''; // Supposons que l'email est l'identifiant pour le mode mot de passe
        $password = $_POST['password'] ?? '';

        if (empty($email_or_code) || empty($password)) {
            $_SESSION['error_message'] = "Veuillez saisir votre email/code et votre mot de passe.";
            header('Location: ../login.php');
            exit;
        }

        try {
            // Récupérer l'utilisateur par email
            $stmt = $pdo->prepare("SELECT id, name, email, password FROM users WHERE email = :email");
            $stmt->bindParam(':email', $email_or_code);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Vérifier si l'utilisateur existe et si le mot de passe est correct
            if ($user && password_verify($password, $user['password'])) {
                // Authentification réussie
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['success_message'] = "Connexion réussie !";
                header('Location: ../index.php');
                exit;
            } else {
                $_SESSION['error_message'] = "Email ou mot de passe incorrect.";
                header('Location: ../login.php');
                exit;
            }
        } catch (\PDOException $e) {
            error_log("Erreur de BDD lors de l'authentification : " . $e->getMessage());
            $_SESSION['error_message'] = "Une erreur est survenue lors de la connexion.";
            header('Location: ../login.php');
            exit;
        }

    } else {
        // Méthode d'authentification non valide ou non implémentée (OTP par exemple)
        $_SESSION['error_message'] = "Méthode d'authentification non valide ou non implémentée.";
        header('Location: ../login.php');
        exit;
    }
} else {
    // Si la page est accédée directement sans POST
    header('Location: ../login.php');
    exit;
}
?>
