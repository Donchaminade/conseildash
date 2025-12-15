<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<aside class="fixed top-16 left-0 bottom-0 w-64 bg-white border-r border-gray-200 overflow-y-auto z-40 transition-all duration-300 transform -translate-x-full lg:translate-x-0" id="sidebar">
    <div class="px-4 py-6">
        <div class="space-y-6">
            <div class="space-y-1">
                <a href="index.php" class="flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100 <?= ($current_page == 'index.php') ? 'bg-blue-50 text-blue-600' : '' ?>">
                    <i data-feather="home" class="mr-3 text-gray-500 <?= ($current_page == 'index.php') ? 'text-blue-500' : '' ?>"></i>
                    Tableau de bord
                </a>
                
                <a href="conseils.php" class="flex items-center justify-between px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100 <?= ($current_page == 'conseils.php') ? 'bg-blue-50 text-blue-600' : '' ?>">
                    <div class="flex items-center">
                        <i data-feather="file-text" class="mr-3 <?= ($current_page == 'conseils.php') ? 'text-blue-500' : 'text-gray-500' ?>"></i>
                        Conseils
                    </div>
                    <?php if (isset($pending_conseils_global)): // Seulement si la variable est définie (sur index.php) ?>
                        <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2 py-0.5 rounded-full"><?= $pending_conseils_global ?></span>
                    <?php endif; ?>
                </a>
                
                <a href="publicites.php" class="flex items-center justify-between px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100 <?= ($current_page == 'publicites.php') ? 'bg-blue-50 text-blue-600' : '' ?>">
                    <div class="flex items-center">
                        <i data-feather="alert-circle" class="mr-3 <?= ($current_page == 'publicites.php') ? 'text-blue-500' : 'text-gray-500' ?>"></i>
                        Publicités
                    </div>
                    <?php if (isset($active_pubs_global)): // Seulement si la variable est définie (sur index.php) ?>
                        <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2 py-0.5 rounded-full"><?= $active_pubs_global ?></span>
                    <?php endif; ?>
                </a>
            </div>

            <div class="pt-4 border-t border-gray-200">
                <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Administration</h3>
                
                <div class="space-y-1">
                    <div class="group">
                        <div class="flex items-center justify-between px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100 cursor-pointer" id="usersMenuBtn">
                            <div class="flex items-center">
                                <i data-feather="users" class="mr-3 text-gray-500"></i>
                                Utilisateurs
                            </div>
                            <i data-feather="chevron-down" class="text-gray-500 w-4 h-4 transform group-hover:rotate-180 transition-transform"></i>
                        </div>
                        <div class="pl-4 mt-1 space-y-1 hidden" id="usersMenu">
                            <a href="conseillers_list.php" class="block px-3 py-1.5 text-sm text-gray-600 hover:bg-gray-100 rounded-lg">Liste des Conseillers</a>
                            <a href="#" class="block px-3 py-1.5 text-sm text-gray-600 hover:bg-gray-100 rounded-lg">Rôles</a>
                            <a href="#" class="block px-3 py-1.5 text-sm text-gray-600 hover:bg-gray-100 rounded-lg">Permissions</a>
                        </div>
                    </div>
                    
                    <div class="group">
                        <div class="flex items-center justify-between px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100 cursor-pointer" id="settingsMenuBtn">
                            <div class="flex items-center">
                                <i data-feather="settings" class="mr-3 text-gray-500"></i>
                                Paramètres
                            </div>
                            <i data-feather="chevron-down" class="text-gray-500 w-4 h-4 transform group-hover:rotate-180 transition-transform"></i>
                        </div>
                        <div class="pl-4 mt-1 space-y-1 hidden" id="settingsMenu">
                            <a href="#" class="block px-3 py-1.5 text-sm text-gray-600 hover:bg-gray-100 rounded-lg">Général</a>
                            <a href="#" class="block px-3 py-1.5 text-sm text-gray-600 hover:bg-gray-100 rounded-lg">Notifications</a>
                            <a href="#" class="block px-3 py-1.5 text-sm text-gray-600 hover:bg-gray-100 rounded-lg">Sécurité</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</aside>