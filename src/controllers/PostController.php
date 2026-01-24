<?php
require_once 'controllers/Controller.php';
require_once 'models/PostModel.php';

class PostController extends Controller {

    public function delete() {
        if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
            $this->redirect('index.php');
        }

        $postModel = new PostModel($this->pdo);
        $post = $postModel->getPostById($_GET['id']);

        if ($post && $post['id_utilisateur'] == $_SESSION['user_id']) {
            $postModel->deletePost($_GET['id']);
        }

        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }

    public function edit() {
        if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
            $this->redirect('index.php');
        }

        $postModel = new PostModel($this->pdo);
        $post = $postModel->getPostById($_GET['id']);

        if (!$post || $post['id_utilisateur'] != $_SESSION['user_id']) {
            die("Accès interdit.");
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titre = $_POST['titre'];
            $desc = $_POST['desc'];
            $statut = $_POST['statut'];
            $postModel->updatePost($_GET['id'], $titre, $desc, $statut);
            
            $this->redirect('index.php?controller=User&action=profile');
        }

        // C'est ici qu'on appelle la vue post_edit.php
        $this->render('post_edit', ['post' => $post]);
    }
}
?>