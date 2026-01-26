<?php

// Debug erreur 500

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!defined('DB_HOST')) {
    define('DB_HOST', 'linserv-info-01');
    define('DB_NAME', 'se401325_gamehub'); // Vérifie bien le nom de ta BDD ici
    define('DB_USER', 'se401325');
    define('DB_PASS', 'se401325');
}

try {
    if (!isset($pdo)) {
        $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    }
} catch (PDOException $e) {
    die("Erreur de connexion BDD : " . $e->getMessage());
}

?>