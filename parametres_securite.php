<?php
require_once 'config.php';
$settings = require ROOT_PATH . '/settings.php'; // Charge les paramètres actuels

// Gérer l'affichage des messages de succès/erreur spécifiques à chaque formulaire
$email_message = ''; $email_message_type = '';
$password_message = ''; $password_message_type = '';
$otp_message = ''; $otp_message_type = '';

if (isset($_GET['success_email'])) { $email_message = "Email mis à jour !"; $email_message_type = 'success'; }
if (isset($_GET['error_email'])) { $email_message = "Erreur email: " . htmlspecialchars($_GET['error_email']); $email_message_type = 'error'; }

if (isset($_GET['success_password'])) { $password_message = "Mot de passe mis à jour !"; $password_message_type = 'success'; }
if (isset($_GET['error_password'])) { $password_message = "Erreur mot de passe: " . htmlspecialchars($_GET['error_password']); $password_message_type = 'error'; }

if (isset($_GET['success_otp'])) { $otp_message = "Code OTP mis à jour !"; $otp_message_type = 'success'; }
if (isset($_GET['error_otp'])) { $otp_message = "Erreur OTP: " . htmlspecialchars($_GET['error_otp']); $otp_message_type = 'error'; }


// Récupérer les informations de l'utilisateur (admin, id=1)
$user = null;
try {
    $stmt = $pdo->prepare("SELECT id, name, email FROM users WHERE id = 1");
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (\PDOException $e) {
    error_log("Erreur lors de la récupération de l'utilisateur admin : " . $e->getMessage());
    $email_message = "Erreur : Impossible de charger les informations de l'administrateur.";
    $email_message_type = 'error';
}

?>
<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paramètres de Sécurité - ConseilBox Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <script src="https://unpkg.com/feather-icons"></script>
</head>
<body class="h-full <?= $settings['theme'] === 'dark' ? 'dark' : '' ?>">
    <?php include 'includes/navbar.php'; ?>
    <div class="flex">
        <?php include 'includes/sidebar.php'; ?>
        
        <main class="flex-1 p-4 sm:p-8 ml-0 lg:ml-64">
            <div class="max-w-3xl mx-auto">
                <div class="flex items-center mb-6">
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 dark:text-gray-100 ml-4">Paramètres de Sécurité</h1>
                </div>

                <?php if ($email_message): ?>
                    <script>document.addEventListener('DOMContentLoaded', function() { showAlert("<?= $email_message ?>", "<?= $email_message_type ?>"); });</script>
                <?php endif; ?>
                <?php if ($password_message): ?>
                    <script>document.addEventListener('DOMContentLoaded', function() { showAlert("<?= $password_message ?>", "<?= $password_message_type ?>"); });</script>
                <?php endif; ?>
                <?php if ($otp_message): ?>
                    <script>document.addEventListener('DOMContentLoaded', function() { showAlert("<?= $otp_message ?>", "<?= $otp_message_type ?>"); });</script>
                <?php endif; ?>
                
                <?php if ($user): ?>
                    <!-- Formulaire de changement d'Email / Code de Login -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden mb-8">
                        <div class="p-6 sm:p-8">
                            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100 mb-4">Changer l'Email / Code de Login</h2>
                            <form action="actions/_sauvegarder_securite.php" method="POST" class="space-y-6">
                                <input type="hidden" name="form_type" value="email_change">
                                <div>
                                    <label for="current_email" class="block text-sm font-semibold text-gray-600 dark:text-gray-300 mb-1">Email Actuel</label>
                                    <input type="email" name="current_email" id="current_email" value="<?= htmlspecialchars($user['email']) ?>" readonly
                                           class="mt-1 block w-full px-4 py-2 bg-gray-100 dark:bg-gray-700 dark:text-gray-100 rounded-lg border-gray-200 dark:border-gray-600 cursor-not-allowed">
                                </div>
                                <div>
                                    <label for="new_email" class="block text-sm font-semibold text-gray-600 dark:text-gray-300 mb-1">Nouvel Email</label>
                                    <input type="email" name="new_email" id="new_email" placeholder="Entrez le nouvel email" required
                                           class="mt-1 block w-full px-4 py-2 bg-gray-50 dark:bg-gray-700 dark:text-gray-100 rounded-lg border-gray-200 dark:border-gray-600 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-150 ease-in-out">
                                </div>
                                <div>
                                    <label for="password_confirm_email" class="block text-sm font-semibold text-gray-600 dark:text-gray-300 mb-1">Confirmez votre Mot de passe actuel</label>
                                    <input type="password" name="password_confirm_email" id="password_confirm_email" placeholder="Entrez votre mot de passe pour confirmer" required
                                           class="mt-1 block w-full px-4 py-2 bg-gray-50 dark:bg-gray-700 dark:text-gray-100 rounded-lg border-gray-200 dark:border-gray-600 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-150 ease-in-out">
                                </div>
                                <div class="pt-4 flex items-center justify-end">
                                    <button type="submit" class="inline-flex justify-center py-2 px-5 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transform hover:scale-105 transition-all duration-150 ease-in-out">
                                        <i data-feather="mail" class="w-4 h-4 mr-2"></i> Changer l'Email
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Formulaire de changement de Mot de passe -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden mb-8">
                        <div class="p-6 sm:p-8">
                            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100 mb-4">Changer le Mot de passe</h2>
                            <form action="actions/_sauvegarder_securite.php" method="POST" class="space-y-6">
                                <input type="hidden" name="form_type" value="password_change">
                                <div>
                                    <label for="current_password" class="block text-sm font-semibold text-gray-600 dark:text-gray-300 mb-1">Mot de passe Actuel</label>
                                    <input type="password" name="current_password" id="current_password" placeholder="Entrez votre mot de passe actuel" required
                                           class="mt-1 block w-full px-4 py-2 bg-gray-50 dark:bg-gray-700 dark:text-gray-100 rounded-lg border-gray-200 dark:border-gray-600 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-150 ease-in-out">
                                </div>
                                <div>
                                    <label for="new_password" class="block text-sm font-semibold text-gray-600 dark:text-gray-300 mb-1">Nouveau Mot de passe</label>
                                    <input type="password" name="new_password" id="new_password" placeholder="Entrez le nouveau mot de passe" required
                                           class="mt-1 block w-full px-4 py-2 bg-gray-50 dark:bg-gray-700 dark:text-gray-100 rounded-lg border-gray-200 dark:border-gray-600 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-150 ease-in-out">
                                </div>
                                <div>
                                    <label for="confirm_password" class="block text-sm font-semibold text-gray-600 dark:text-gray-300 mb-1">Confirmer le Nouveau Mot de passe</label>
                                    <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirmez le nouveau mot de passe" required
                                           class="mt-1 block w-full px-4 py-2 bg-gray-50 dark:bg-gray-700 dark:text-gray-100 rounded-lg border-gray-200 dark:border-gray-600 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-150 ease-in-out">
                                </div>
                                <div class="pt-4 flex items-center justify-end">
                                    <button type="submit" class="inline-flex justify-center py-2 px-5 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transform hover:scale-105 transition-all duration-150 ease-in-out">
                                        <i data-feather="key" class="w-4 h-4 mr-2"></i> Changer le Mot de passe
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Formulaire de changement d'OTP -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                        <div class="p-6 sm:p-8">
                            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100 mb-4">Changer le Code OTP</h2>
                            <form action="actions/_sauvegarder_parametres.php" method="POST" class="space-y-6">
                                <input type="hidden" name="form_type" value="otp_change">
                                <div>
                                    <label for="otp_code" class="block text-sm font-semibold text-gray-600 dark:text-gray-300 mb-1">Nouveau Code OTP (6 chiffres)</label>
                                    <input type="text" name="otp_code" id="otp_code" value="<?= htmlspecialchars($settings['otp_code'] ?? '') ?>" maxlength="6" pattern="\d{6}" required
                                           class="mt-1 block w-full px-4 py-2 bg-gray-50 dark:bg-gray-700 dark:text-gray-100 rounded-lg border-gray-200 dark:border-gray-600 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-150 ease-in-out">
                                </div>
                                <div>
                                    <label for="password_confirm_otp" class="block text-sm font-semibold text-gray-600 dark:text-gray-300 mb-1">Confirmez votre Mot de passe</label>
                                    <input type="password" name="password_confirm_otp" id="password_confirm_otp" placeholder="Entrez votre mot de passe pour confirmer" required
                                           class="mt-1 block w-full px-4 py-2 bg-gray-50 dark:bg-gray-700 dark:text-gray-100 rounded-lg border-gray-200 dark:border-gray-600 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-150 ease-in-out">
                                </div>
                                <div class="pt-4 flex items-center justify-end">
                                    <button type="submit" class="inline-flex justify-center py-2 px-5 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transform hover:scale-105 transition-all duration-150 ease-in-out">
                                        <i data-feather="key" class="w-4 h-4 mr-2"></i> Changer l'OTP
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                <?php else: ?>
                    <p class="text-red-500 dark:text-red-400">Erreur : Impossible de charger les paramètres de sécurité. L'utilisateur administrateur n'a pas été trouvé.</p>
                <?php endif; ?>
            </div>
        </main>
    </div>
    <script src="script.js"></script>
</body>
</html>
