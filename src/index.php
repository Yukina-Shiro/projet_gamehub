<?php
// index.php
session_start();
require_once 'config/db.php';

// On charge les classes de base manuellement (pas d'autoloader complexe)
require_once 'models/Model.php';
require_once 'controllers/Controller.php';

// Récupération du contrôleur et de l'action (par défaut : Home / index)
$controllerName = isset($_GET['controller']) ? ucfirst($_GET['controller']) . 'Controller' : 'HomeController';
$actionName = isset($_GET['action']) ? $_GET['action'] : 'index';

// Chemin du fichier contrôleur
$controllerFile = "controllers/$controllerName.php";

if (file_exists($controllerFile)) {
    require_once $controllerFile;
    if (class_exists($controllerName)) {
        $controller = new $controllerName($pdo);
        if (method_exists($controller, $actionName)) {
            $controller->$actionName();
        } else {
            echo "Erreur 404 : L'action $actionName n'existe pas.";
        }
    } else {
        echo "Erreur : La classe $controllerName n'existe pas.";
    }
} else {
    // Si le contrôleur n'existe pas, on redirige vers Login si pas connecté
    if(!isset($_SESSION['user_id'])) {
        header("Location: index.php?controller=Auth&action=login");
    } else {
        echo "Erreur 404 : Page introuvable.";
    }
}

?>