<?php
// controllers/HomeController.php
require_once 'models/PostModel.php';

class HomeController extends Controller {

    public function index() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('index.php?controller=Auth&action=login');
        }

        $postModel = new PostModel($this->pdo);
        // Traitement du formulaire de publication
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['titre'])) {
            $postModel->createPost($_SESSION['user_id'], $_POST['titre'], $_POST['desc'], $_POST['statut']);
        }

        // Récupération du mur
        $posts = $postModel->getFeed($_SESSION['user_id']);
        
        $this->render('home', ['posts' => $posts]);
    }
}

?>