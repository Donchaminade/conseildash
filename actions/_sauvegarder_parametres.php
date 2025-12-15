<?php
// actions/_sauvegarder_parametres.php

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Inclure la configuration de la base de données pour d'éventuels besoins futurs,
// bien que pour la sauvegarde des settings, un fichier PHP suffise.
require_once '../config.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $settings_file = ROOT_PATH . '/settings.php';
    $current_settings = require $settings_file; // Charge les paramètres actuels

    $new_settings = $current_settings; // Initialise avec les paramètres actuels

    // --- Traitement du titre du site ---
    if (isset($_POST['site_title'])) {
        $new_settings['site_title'] = trim($_POST['site_title']);
    }

    // --- Traitement du thème ---
    if (isset($_POST['theme']) && in_array($_POST['theme'], ['light', 'dark'])) {
        $new_settings['theme'] = $_POST['theme'];
    }

    // --- Traitement du code OTP ---
    if (isset($_POST['otp_code'])) {
        $otp_code = trim($_POST['otp_code']);
        if (ctype_digit($otp_code) && strlen($otp_code) === 6) {
            $new_settings['otp_code'] = $otp_code;
        } else {
            header('Location: ../parametres_general.php?error=invalid_otp_format');
            exit;
        }
    }

    // --- Traitement du logo du site (upload de fichier) ---
    if (isset($_FILES['site_logo']) && $_FILES['site_logo']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = ROOT_PATH . '/images/'; // Dossier pour les logos
        
        // Créer le dossier si nécessaire
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        // Supprimer l'ancien logo si différent et s'il existe
        if ($new_settings['site_logo'] && file_exists(ROOT_PATH . '/' . $new_settings['site_logo']) && $new_settings['site_logo'] !== 'images/logo.png') {
             unlink(ROOT_PATH . '/' . $new_settings['site_logo']);
        }
        
        $file_name = uniqid('logo_') . '_' . basename($_FILES['site_logo']['name']);
        $target_file = $upload_dir . $file_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed_types = ['jpg', 'png', 'jpeg', 'gif', 'svg']; // Autoriser les SVG
        
        if (in_array($imageFileType, $allowed_types)) {
            if (move_uploaded_file($_FILES['site_logo']['tmp_name'], $target_file)) {
                $new_settings['site_logo'] = 'images/' . $file_name; // Chemin relatif pour la BDD/fichier
            } else {
                header('Location: ../parametres_general.php?error=upload_logo_failed');
                exit;
            }
        } else {
            header('Location: ../parametres_general.php?error=invalid_logo_type');
            exit;
        }
    }

    // --- Sauvegarder les nouveaux paramètres ---
    // Le contenu du fichier settings.php sera le tableau PHP
    $settings_content = "<?php\n\nreturn " . var_export($new_settings, true) . ";\n";
    
    if (file_put_contents($settings_file, $settings_content) !== false) {
        header('Location: ../parametres_general.php?success=settings_saved');
        exit;
    } else {
        header('Location: ../parametres_general.php?error=file_write_failed');
        exit;
    }

} else {
    // Accès direct sans POST
    header('Location: ../parametres_general.php');
    exit;
}
?>
