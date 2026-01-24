<?php
// controllers/Controller.php
class Controller {
    protected $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Fonction pour afficher une vue HTML
    protected function render($view, $data = []) {
        extract($data); // Transforme le tableau ['posts' => $p] en variable $posts
        include "views/$view.php";
    }

    // Fonction pour rediriger
    protected function redirect($url) {
        header("Location: $url");
        exit;
    }
}

?>