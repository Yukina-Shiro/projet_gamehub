<?php
require_once 'controllers/Controller.php';
require_once 'models/PostModel.php';
require_once 'models/VoteModel.php';
require_once 'models/CommentModel.php';

class PostController extends Controller {

    /**
     * Affiche la page de détail d'un post avec ses commentaires
     * Route : index.php?controller=Post&action=show&id=XX
     */
    public function show() {
        if (!isset($_GET['id'])) $this->redirect('index.php');
        
        $postId = (int)$_GET['id'];
        $userId = $_SESSION['user_id'] ?? 0;
        $sort = $_GET['sort'] ?? 'pertinence';

        $postModel = new PostModel($this->pdo);
        $voteModel = new VoteModel($this->pdo);
        $commentModel = new CommentModel($this->pdo);

        // 1. Récupération du post (Vérification de l'existence)
        $post = $postModel->getPostById($postId);
        if (!$post) {
            $_SESSION['error'] = "Ce post n'existe plus.";
            $this->redirect('index.php');
        }

        // 2. Traitement d'un nouveau commentaire
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['commentaire'])) {
            if (!$userId) $this->redirect('index.php?controller=Auth&action=login');
            
            $commentModel->addComment($userId, $postId, $_POST['commentaire']);
            // Redirection pour nettoyer le flux POST (éviter le double envoi au refresh)
            header("Location: index.php?controller=Post&action=show&id=$postId&sort=$sort");
            exit;
        }

        // 3. Récupération des données associées
        $stats = $voteModel->getPostStats($postId);
        $myVote = $voteModel->getUserVote($userId, $postId);
        $comments = $commentModel->getCommentsSorted($postId, $userId, $sort);

        // 4. Affichage de la vue
        $this->render('post/show', [
            'post' => $post,
            'stats' => $stats,
            'myVote' => $myVote,
            'comments' => $comments,
            'currentSort' => $sort
        ]);
    }

    // Suppression
    public function delete() {
        if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) $this->redirect('index.php');
        $postModel = new PostModel($this->pdo);
        $post = $postModel->getPostById($_GET['id']);
        if ($post && $post['id_utilisateur'] == $_SESSION['user_id']) {
            $postModel->deletePost($_GET['id']);
        }
        // Retour intelligent
        if(isset($_SERVER['HTTP_REFERER'])) header("Location: " . $_SERVER['HTTP_REFERER']);
        else $this->redirect('index.php');
    }

    // Modification
    public function edit() {
        if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) $this->redirect('index.php');
        $postModel = new PostModel($this->pdo);
        $post = $postModel->getPostById($_GET['id']);

        if (!$post || $post['id_utilisateur'] != $_SESSION['user_id']) die("Accès interdit.");

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titre = $_POST['titre'];
            $desc = $_POST['desc'];
            $statut = $_POST['statut'];
            $postModel->updatePost($_GET['id'], $titre, $desc, $statut);
            $this->redirect('index.php?controller=User&action=profile');
        }
        $this->render('post_edit', ['post' => $post]);
    }

    // Création depuis n'importe où
    public function create() {
        if (!isset($_SESSION['user_id'])) $this->redirect('index.php');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titre = isset($_POST['titre']) ? $_POST['titre'] : '';
            $desc = $_POST['desc'];
            $statut = $_POST['statut'];
            $photoName = null;

            if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
                $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                $filename = $_FILES['photo']['name'];
                $filetype = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                if (in_array($filetype, $allowed)) {
                    $targetDir = dirname(__DIR__) . '/uploads/';
                    if (!is_dir($targetDir)) mkdir($targetDir, 0755, true);
                    $newName = "post_" . $_SESSION['user_id'] . "_" . time() . "." . $filetype;
                    if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetDir . $newName)) {
                        $photoName = $newName;
                    }
                }
            }

            $postModel = new PostModel($this->pdo);
            $postModel->createPost($_SESSION['user_id'], $titre, $desc, $statut, $photoName);
        }
        
        // On redirige vers le mur
        $this->redirect('index.php?controller=Home&action=index');
    }

    // AJAX Votes
    public function voteAjax() {
        header('Content-Type: application/json');
        if (!isset($_SESSION['user_id'])) { echo json_encode(['success'=>false]); exit; }
        $voteModel = new VoteModel($this->pdo);
        $voteModel->toggleVote($_SESSION['user_id'], $_POST['id'], (int)$_POST['value']);
        $stats = $voteModel->getPostStats($_POST['id']);
        $newVote = $voteModel->getUserVote($_SESSION['user_id'], $_POST['id']);
        echo json_encode(['success'=>true, 'likes'=>$stats['nb_likes'], 'dislikes'=>$stats['nb_dislikes'], 'userVote'=>$newVote]);
        exit;
    }

    // Affichage détail
    public function show() {
        if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) $this->redirect('index.php');
        $postModel = new PostModel($this->pdo);
        $voteModel = new VoteModel($this->pdo);
        $commentModel = new CommentModel($this->pdo);
        
        $post = $postModel->getPostById($_GET['id']);
        if (!$post) die("Post introuvable");

        $stats = $voteModel->getPostStats($_GET['id']);
        $myVote = $voteModel->getUserVote($_SESSION['user_id'], $_GET['id']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['commentaire'])) {
            $commentModel->addComment($_SESSION['user_id'], $_GET['id'], $_POST['commentaire']);
            header("Location: index.php?controller=Post&action=show&id=".$_GET['id']); exit;
        }

        $sort = isset($_GET['sort']) ? $_GET['sort'] : 'pertinence';
        $comments = $commentModel->getCommentsSorted($_GET['id'], $_SESSION['user_id'], $sort);

        $this->render('post_detail', ['post'=>$post, 'stats'=>$stats, 'myVote'=>$myVote, 'comments'=>$comments, 'currentSort'=>$sort]);
    }
}
?>