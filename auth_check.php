<?php
// auth_check.php
session_start();

if (!isset($_SESSION['user_id'])) {
    // L'utilisateur n'est pas connecté, rediriger vers la page de connexion
    $_SESSION['error_message'] = "Veuillez vous connecter pour accéder à cette page.";
    header('Location: login.php');
    exit;
}
?>