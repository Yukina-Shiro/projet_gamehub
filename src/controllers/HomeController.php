<?php

require_once 'controllers/Controller.php';
require_once 'models/PostModel.php';
require_once 'models/VoteModel.php';

class HomeController extends Controller {

    public function index() {
        if (!isset($_SESSION['user_id'])) $this->redirect('index.php?controller=Auth&action=login');

        $postModel = new PostModel($this->pdo);
        
        // Gestion du filtre
        $filter = isset($_GET['filter']) ? $_GET['filter'] : 'global';

        if ($filter === 'perso') {
            $posts = $postModel->getPersoFeed($_SESSION['user_id']);
        } else {
            $posts = $postModel->getGlobalFeed($_SESSION['user_id']);
        }

        // On passe le filtre à la vue pour activer le bon onglet
        $this->render('home', ['posts' => $posts, 'currentFilter' => $filter]);
    }

    public function faq() {
        $this->render('faq'); // Affiche views/faq.php
    }

    public function vote() {
        $voteModel = new VoteModel($this->pdo);
        $userId = $_SESSION['user_id'];
        $postId = $_POST['post_id'];
        $voteValue = $_POST['vote_value'];

        $voteModel->votePost($userId, $postId, $voteValue);
        $this->redirect('index.php?controller=Home&action=index');
    }
}

?>