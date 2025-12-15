<?php
// Charger les paramètres globaux
$settings = require ROOT_PATH . '/settings.php';
?>
<nav class="fixed top-0 left-0 right-0 z-50 bg-white dark:bg-gray-800 shadow-sm h-16 flex items-center px-6 border-b dark:border-gray-700">
    <div class="flex items-center justify-between w-full">
        <div class="flex items-center">
            <button class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 mr-4 lg:hidden" id="sidebarToggle">
                <i data-feather="menu" class="text-gray-500 dark:text-gray-400"></i>
            </button>
            <a href="index.php" class="text-xl font-bold text-gray-800 dark:text-gray-100 flex items-center">
                <?php if (!empty($settings['site_logo'])): ?>
                    <img src="<?= htmlspecialchars($settings['site_logo']) ?>" alt="Logo" class="h-8 w-auto mr-2">
                <?php else: ?>
                    <i data-feather="box" class="text-blue-600 mr-2"></i>
                <?php endif; ?>
                <?= htmlspecialchars($settings['site_title']) ?>
            </a>
        </div>
        
        <div class="flex items-center space-x-6">
            <div class="relative">
                <input type="text" placeholder="Rechercher..." class="bg-gray-100 dark:bg-gray-700 dark:text-gray-100 border-none rounded-lg pl-10 pr-4 py-2 w-40 focus:w-64 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300">
                <i data-feather="search" class="absolute left-3 top-2.5 text-gray-500 dark:text-gray-400"></i>
            </div>
            
            <a href="logout.php" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 relative" title="Déconnexion">
                <i data-feather="log-out" class="text-gray-500 dark:text-gray-400"></i>
            </a>
            
            <div class="relative ml-4">
                <div class="flex items-center space-x-2 cursor-pointer" id="profileBtn">
                    <div class="h-8 w-8 rounded-full bg-blue-100 dark:bg-blue-800 flex items-center justify-center text-blue-600 dark:text-blue-100 font-medium">IK</div>
                    <span class="font-medium text-gray-800 dark:text-gray-100">IrokouKaizen</span>
                </div>
            </div>
        </div>
    </div>
</nav>