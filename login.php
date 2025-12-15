<!DOCTYPE html>
<html lang="fr" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - ConseilBox</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <script src="script.js"></script>
</head>
<body class="h-full flex items-center justify-center bg-gradient-to-r from-blue-500 to-purple-600">
    <div class="w-full max-w-md p-8 space-y-8 bg-white rounded-xl shadow-lg">
        <div class="text-center">
            <div class="flex justify-center mb-6">
                <i data-feather="box" class="text-blue-600 w-12 h-12"></i>
            </div>
            <h2 class="text-3xl font-extrabold text-gray-900">Connexion</h2>
            <p class="mt-2 text-sm text-gray-600">Veuillez vous identifier pour accéder au tableau de bord</p>
        </div>

        <form id="loginForm" class="mt-8 space-y-6">
            <div class="grid grid-cols-1 gap-4">
                <div class="space-y-1">
                    <label for="authMethod" class="block text-sm font-medium text-gray-700">Méthode d'authentification</label>
                    <select id="authMethod" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        <option value="password">Mot de passe</option>
                        <option value="otp">Code OTP</option>
                    </select>
                </div>

                <div id="passwordField" class="space-y-1">
                    <label for="password" class="block text-sm font-medium text-gray-700">Mot de passe</label>
                    <input id="password" name="password" type="password" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div id="otpField" class="space-y-1 hidden">
                    <label for="otp" class="block text-sm font-medium text-gray-700">Code OTP (6 chiffres)</label>
                    <input id="otp" name="otp" type="text" maxlength="6" pattern="\d{6}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="rememberMe" name="rememberMe" type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="rememberMe" class="ml-2 block text-sm text-gray-700">Se souvenir de moi</label>
                </div>
            </div>

            <div>
                <button type="submit" class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <i data-feather="lock" class="h-5 w-5 text-blue-300 group-hover:text-blue-200"></i>
                    </span>
                    Se connecter
                </button>
            </div>
        </form>

        <div id="loadingIndicator" class="hidden flex flex-col items-center justify-center py-8">
            <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500 mb-4"></div>
            <p class="text-gray-600">Vérification en cours...</p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            feather.replace();

            // Toggle between password and OTP fields
            const authMethod = document.getElementById('authMethod');
            const passwordField = document.getElementById('passwordField');
            const otpField = document.getElementById('otpField');

            authMethod.addEventListener('change', function() {
                if (this.value === 'password') {
                    passwordField.classList.remove('hidden');
                    otpField.classList.add('hidden');
                } else {
                    passwordField.classList.add('hidden');
                    otpField.classList.remove('hidden');
                }
            });

            // Handle form submission
            const loginForm = document.getElementById('loginForm');
            const loadingIndicator = document.getElementById('loadingIndicator');

            loginForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const method = authMethod.value;
                let isValid = false;

                if (method === 'password') {
                    const password = document.getElementById('password').value;
                    isValid = password === 'IrokouKaizen';
                } else {
                    const otp = document.getElementById('otp').value;
                    isValid = otp === '252025';
                }

                // Show loading indicator
                loginForm.classList.add('hidden');
                loadingIndicator.classList.remove('hidden');

                // Simulate verification delay
                setTimeout(() => {
                    if (isValid) {
                        window.location.href = 'index.html';
                    } else {
                        alert('Identifiants incorrects. Veuillez réessayer.');
                        loginForm.classList.remove('hidden');
                        loadingIndicator.classList.add('hidden');
                    }
                }, 3000);
            });
        });
    </script>
</body>
</html>