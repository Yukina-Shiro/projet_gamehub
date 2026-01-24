<?php
// config/db.php
define('DB_HOST', 'linserv-info-01');
define('DB_NAME', 'gj402456_game_hub');
define('DB_USER', 'gj402456'); 
define('DB_PASS', 'gj402456'); 

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur de connexion BDD : " . $e->getMessage());
}

?>