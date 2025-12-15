<?php
session_start();
require_once 'config.php';
$settings = require_once 'settings.php'; // Charger les paramètres pour le thème
?>
<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - ConseilBox</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <script src="script.js"></script>
</head>
<body class="h-full flex items-center justify-center bg-gradient-to-r from-blue-500 to-purple-600 <?= $settings['theme'] === 'dark' ? 'dark' : '' ?>">
    <div class="w-full max-w-md p-8 space-y-8 bg-white dark:bg-gray-800 rounded-xl shadow-lg">
        <div class="text-center">
            <div class="flex justify-center mb-6">
                <?php if (!empty($settings['site_logo'])): ?>
                    <img src="<?= htmlspecialchars($settings['site_logo']) ?>" alt="Logo" class="h-12 w-12 mr-2">
                <?php else: ?>
                    <i data-feather="box" class="text-blue-600 dark:text-blue-400 w-12 h-12"></i>
                <?php endif; ?>
            </div>
            <h2 class="text-3xl font-extrabold text-gray-900 dark:text-gray-100">Connexion</h2>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">Veuillez vous identifier pour accéder au tableau de bord</p>
        </div>

        <?php
        if (isset($_SESSION['error_message'])) {
            echo '<script>document.addEventListener("DOMContentLoaded", function(){ showAlert("' . htmlspecialchars($_SESSION['error_message']) . '", "error"); });</script>';
            unset($_SESSION['error_message']);
        }
        if (isset($_SESSION['success_message'])) {
            echo '<script>document.addEventListener("DOMContentLoaded", function(){ showAlert("' . htmlspecialchars($_SESSION['success_message']) . '", "success"); });</script>';
            unset($_SESSION['success_message']);
        }
        ?>

        <form id="loginForm" class="mt-8 space-y-6" action="actions/_login_process.php" method="POST">
            <div class="grid grid-cols-1 gap-4">
                <div class="space-y-1">
                    <label for="authMethod" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Méthode d'authentification</label>
                    <select id="authMethod" name="authMethod" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 dark:text-gray-100">
                        <option value="password">Email et Mot de passe</option>
                        <option value="otp">Code OTP (pour le test)</option>
                    </select>
                </div>
                
                <div id="emailField" class="space-y-1">
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email / Code de Login</label>
                    <input id="email" name="email" type="email" autocomplete="email" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div id="passwordField" class="space-y-1">
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Mot de passe</label>
                    <input id="password" name="password" type="password" autocomplete="current-password" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div id="otpField" class="space-y-1 hidden">
                    <label for="otp" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Code OTP (pour le test)</label>
                    <input id="otp" name="otp" type="text" maxlength="6" pattern="\d{6}" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="rememberMe" name="rememberMe" type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600 rounded">
                    <label for="rememberMe" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">Se souvenir de moi</label>
                </div>
            </div>

            <div>
                <button type="submit" class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <i data-feather="log-in" class="h-5 w-5 text-blue-300 group-hover:text-blue-200"></i>
                    </span>
                    Se connecter
                </button>
            </div>
        </form>

        <div id="loadingIndicator" class="hidden flex flex-col items-center justify-center py-8">
            <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500 mb-4"></div>
            <p class="text-gray-600 dark:text-gray-300">Vérification en cours...</p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            feather.replace();

            const authMethod = document.getElementById('authMethod');
            const emailField = document.getElementById('emailField'); // Nouveau champ pour l'email
            const passwordField = document.getElementById('passwordField');
            const otpField = document.getElementById('otpField');
            const loginForm = document.getElementById('loginForm');
            const loadingIndicator = document.getElementById('loadingIndicator');

            authMethod.addEventListener('change', function() {
                if (this.value === 'password') {
                    emailField.classList.remove('hidden');
                    passwordField.classList.remove('hidden');
                    otpField.classList.add('hidden');
                    emailField.querySelector('input').required = true;
                    passwordField.querySelector('input').required = true;
                    otpField.querySelector('input').required = false;
                } else {
                    emailField.classList.add('hidden');
                    passwordField.classList.add('hidden');
                    otpField.classList.remove('hidden');
                    emailField.querySelector('input').required = false;
                    passwordField.querySelector('input').required = false;
                    otpField.querySelector('input').required = true;
                }
            });

            // Initial state based on default select value
            if (authMethod.value === 'otp') {
                emailField.classList.add('hidden');
                passwordField.classList.add('hidden');
                otpField.classList.remove('hidden');
                emailField.querySelector('input').required = false;
                passwordField.querySelector('input').required = false;
                otpField.querySelector('input').required = true;
            }

            // Afficher l'indicateur de chargement après soumission
            loginForm.addEventListener('submit', function() {
                loginForm.classList.add('hidden');
                loadingIndicator.classList.remove('hidden');
            });
        });
    </script>
</body>
</html>