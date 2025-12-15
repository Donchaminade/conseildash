<?php
// config.php

// Définir le chemin absolu vers la racine du projet
define('ROOT_PATH', __DIR__);

// Paramètres de la base de données locale
$db_host = 'localhost';
$db_name = 'conseilbox';
$db_user = 'root';
$db_pass = '';
$db_char = 'utf8mb4';

// Data Source Name (DSN)
$dsn = "mysql:host=$db_host;dbname=$db_name;charset=$db_char";

// Options pour PDO
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    // Création de l'instance PDO
    $pdo = new PDO($dsn, $db_user, $db_pass, $options);
} catch (\PDOException $e) {
    // En cas d'erreur de connexion, on affiche un message et on arrête le script
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

// Si vous souhaitez vérifier la connexion, vous pouvez décommenter la ligne suivante :
// echo "Connexion à la base de données réussie !";

?>
