<?php
// actions/_sauvegarder_securite.php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../config.php'; // Inclure la configuration de la base de données
$settings_file = ROOT_PATH . '/settings.php'; // Chemin du fichier settings.php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $form_type = $_POST['form_type'] ?? ''; // Type de formulaire soumis
    $user_id = 1; // On suppose l'utilisateur admin a l'ID 1

    try {
        switch ($form_type) {
            case 'email_change':
                $new_email = trim($_POST['new_email'] ?? '');
                $password_confirm_email = $_POST['password_confirm_email'] ?? '';

                if (empty($new_email) || empty($password_confirm_email)) {
                    header('Location: ../parametres_securite.php?error_email=' . urlencode("Tous les champs sont requis."));
                    exit;
                }

                // Vérifier le mot de passe actuel
                $stmt = $pdo->prepare("SELECT password FROM users WHERE id = :id");
                $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
                $stmt->execute();
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$user || !password_verify($password_confirm_email, $user['password'])) {
                    header('Location: ../parametres_securite.php?error_email=' . urlencode("Mot de passe actuel incorrect."));
                    exit;
                }

                // Mettre à jour l'email
                $stmt = $pdo->prepare("UPDATE users SET email = :email WHERE id = :id");
                $stmt->bindParam(':email', $new_email);
                $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
                $stmt->execute();

                header('Location: ../parametres_securite.php?success_email=' . urlencode("Email mis à jour avec succès !"));
                exit;

            case 'password_change':
                $current_password = $_POST['current_password'] ?? '';
                $new_password = $_POST['new_password'] ?? '';
                $confirm_password = $_POST['confirm_password'] ?? '';

                if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
                    header('Location: ../parametres_securite.php?error_password=' . urlencode("Tous les champs sont requis."));
                    exit;
                }

                if ($new_password !== $confirm_password) {
                    header('Location: ../parametres_securite.php?error_password=' . urlencode("Le nouveau mot de passe et la confirmation ne correspondent pas."));
                    exit;
                }

                // Vérifier le mot de passe actuel
                $stmt = $pdo->prepare("SELECT password FROM users WHERE id = :id");
                $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
                $stmt->execute();
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$user || !password_verify($current_password, $user['password'])) {
                    header('Location: ../parametres_securite.php?error_password=' . urlencode("Mot de passe actuel incorrect."));
                    exit;
                }

                // Mettre à jour le mot de passe
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE users SET password = :password WHERE id = :id");
                $stmt->bindParam(':password', $hashed_password);
                $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
                $stmt->execute();

                header('Location: ../parametres_securite.php?success_password=' . urlencode("Mot de passe mis à jour avec succès !"));
                exit;

            case 'otp_change':
                $otp_code_from_form = $_POST['otp_code'] ?? '';
                $password_confirm_otp = $_POST['password_confirm_otp'] ?? '';

                if (empty($otp_code_from_form) || empty($password_confirm_otp)) {
                    header('Location: ../parametres_securite.php?error_otp=' . urlencode("Tous les champs sont requis."));
                    exit;
                }
                
                // Valider le format de l'OTP
                if (!ctype_digit($otp_code_from_form) || strlen($otp_code_from_form) !== 6) {
                    header('Location: ../parametres_securite.php?error_otp=' . urlencode("Le code OTP doit être numérique et contenir 6 chiffres."));
                    exit;
                }

                // Vérifier le mot de passe actuel
                $stmt = $pdo->prepare("SELECT password FROM users WHERE id = :id");
                $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
                $stmt->execute();
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$user || !password_verify($password_confirm_otp, $user['password'])) {
                    header('Location: ../parametres_securite.php?error_otp=' . urlencode("Mot de passe incorrect pour confirmer l'OTP."));
                    exit;
                }

                // Charger les paramètres actuels, mettre à jour l'OTP, et sauvegarder
                $current_settings = require $settings_file;
                $new_settings = $current_settings;
                $new_settings['otp_code'] = $otp_code_from_form;
                
                $settings_content = "<?php\n\nreturn " . var_export($new_settings, true) . ";\n";
                if (file_put_contents($settings_file, $settings_content) === false) {
                    header('Location: ../parametres_securite.php?error_otp=' . urlencode("Erreur lors de la sauvegarde du code OTP."));
                    exit;
                }

                header('Location: ../parametres_securite.php?success_otp=' . urlencode("Code OTP mis à jour avec succès !"));
                exit;

            default:
                header('Location: ../parametres_securite.php?error=' . urlencode("Type de formulaire non reconnu."));
                exit;
        }

    } catch (\PDOException $e) {
        error_log("Erreur de BDD lors de la sauvegarde des paramètres de sécurité : " . $e->getMessage());
        header('Location: ../parametres_securite.php?error=' . urlencode("Une erreur de base de données est survenue."));
        exit;
    }

} else {
    // Si la page est accédée directement sans POST, rediriger
    header('Location: ../parametres_securite.php');
    exit;
}
?>
