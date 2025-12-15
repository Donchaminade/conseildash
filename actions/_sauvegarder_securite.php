<?php
// actions/_sauvegarder_securite.php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $message = '';
    $message_type = '';

    // Récupérer les données du formulaire
    $new_email = trim($_POST['new_email'] ?? '');
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $otp_code_from_form = $_POST['otp_code'] ?? ''; // Nouveau champ OTP

    // Charger les paramètres actuels du fichier settings.php
    $settings_file = '../settings.php';
    $current_settings = require $settings_file;
    $new_settings = $current_settings; // Pour mettre à jour l'OTP dans le fichier settings.php

    // Valider les entrées du formulaire de sécurité
    if (empty($new_email) || empty($current_password) || empty($new_password) || empty($confirm_password)) {
        $message = "Tous les champs de mot de passe et email sont requis.";
        header('Location: ../parametres_securite.php?error=' . urlencode($message));
        exit;
    }

    if ($new_password !== $confirm_password) {
        $message = "Le nouveau mot de passe et la confirmation ne correspondent pas.";
        header('Location: ../parametres_securite.php?error=' . urlencode($message));
        exit;
    }
    
    // Valider le format de l'OTP si présent
    if (!empty($otp_code_from_form)) {
        if (!ctype_digit($otp_code_from_form) || strlen($otp_code_from_form) !== 6) {
            $message = "Le code OTP doit être numérique et contenir 6 chiffres.";
            header('Location: ../parametres_securite.php?error=' . urlencode($message));
            exit;
        }
        $new_settings['otp_code'] = $otp_code_from_form;
    }

    // Pour cet exemple, on suppose l'utilisateur admin a l'ID 1
    $user_id = 1;

    try {
        // 1. Vérifier le mot de passe actuel
        $stmt = $pdo->prepare("SELECT password FROM users WHERE id = :id");
        $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user || !password_verify($current_password, $user['password'])) {
            $message = "Mot de passe actuel incorrect.";
            header('Location: ../parametres_securite.php?error=' . urlencode($message));
            exit;
        }

        // 2. Hacher le nouveau mot de passe
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // 3. Mettre à jour l'email et le mot de passe dans la base de données
        $stmt = $pdo->prepare("UPDATE users SET email = :email, password = :password WHERE id = :id");
        $stmt->bindParam(':email', $new_email);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
        $stmt->execute();

        // 4. Sauvegarder les paramètres mis à jour (pour l'OTP) dans settings.php
        $settings_content = "<?php\n\nreturn " . var_export($new_settings, true) . ";\n";
        if (file_put_contents($settings_file, $settings_content) === false) {
            $message = "Erreur lors de la sauvegarde du code OTP.";
            header('Location: ../parametres_securite.php?error=' . urlencode($message));
            exit;
        }

        $message = "Paramètres de sécurité mis à jour avec succès !";
        header('Location: ../parametres_securite.php?success=' . urlencode($message));
        exit;

    } catch (\PDOException $e) {
        error_log("Erreur de BDD lors de la sauvegarde des paramètres de sécurité : " . $e->getMessage());
        $message = "Une erreur de base de données est survenue.";
        header('Location: ../parametres_securite.php?error=' . urlencode($message));
        exit;
    }

} else {
    // Si le script n'est pas accédé via POST, rediriger
    header('Location: ../parametres_securite.php');
    exit;
}
?>
