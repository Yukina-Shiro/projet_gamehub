<?php

require_once 'controllers/Controller.php';
require_once 'models/PostModel.php';
require_once 'models/VoteModel.php';
require_once 'models/CommentModel.php';

class HomeController extends Controller {

    public function index() {
        if (!isset($_SESSION['user_id'])) $this->redirect('index.php?controller=Auth&action=login');
        
        // Par défaut, on charge le Global
        $postModel = new PostModel($this->pdo);
        $posts = $postModel->getGlobalFeed($_SESSION['user_id']);
        
        // On rend la vue complète (Header + Page + Footer)
        $this->render('home', ['posts' => $posts]);
    }

    // --- NOUVEAU : FONCTION AJAX ---
    public function ajaxFeed() {
        if (!isset($_SESSION['user_id'])) exit;

        $postModel = new PostModel($this->pdo);
        $voteModel = new VoteModel($this->pdo); // Nécessaire pour feed_content
        $commentModel = new CommentModel($this->pdo); // Nécessaire pour feed_content

        $filter = isset($_GET['filter']) ? $_GET['filter'] : 'global';
        $source = isset($_GET['source']) ? $_GET['source'] : 'all';
        $date = isset($_GET['date']) ? $_GET['date'] : null;

        if ($filter === 'perso') {
            $posts = $postModel->getPersoFeed($_SESSION['user_id'], $source, $date);
        } else {
            $posts = $postModel->getGlobalFeed($_SESSION['user_id']);
        }

        // On inclut DIRECTEMENT le fichier partiel (sans le header/footer)
        // C'est ce bloc HTML qui sera injecté par le JS
        include 'views/components/feed_content.php';
        exit; // On arrête tout ici pour ne pas renvoyer le reste de la page
    }

    public function faq() {
        $this->render('faq');
    }
}

?>