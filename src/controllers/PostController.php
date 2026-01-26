<?php

require_once 'controllers/Controller.php';
require_once 'models/PostModel.php';
require_once 'models/VoteModel.php';
require_once 'models/CommentModel.php';

class PostController extends Controller {

    // Affichage de la page de détail (Nettoyée du traitement POST)
    public function show() {
        if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) $this->redirect('index.php');
        
        $postModel = new PostModel($this->pdo);
        $voteModel = new VoteModel($this->pdo);
        $commentModel = new CommentModel($this->pdo);
        
        $postId = $_GET['id'];
        $userId = $_SESSION['user_id'];

        $post = $postModel->getPostById($postId);
        if (!$post) die("Post introuvable");

        $myVote = $voteModel->getUserVote($userId, $postId);
        $stats = $voteModel->getPostStats($postId);

        // On récupère les commentaires (plus récents en premier)
        $comments = $commentModel->getCommentsSorted($postId, $userId, 'pertinence');

        $this->render('post_detail', [
            'post' => $post, 
            'stats' => $stats, 
            'myVote' => $myVote, 
            'comments' => $comments
        ]);
    }

    // --- FONCTION AJAX QUI GÉNÈRE LE HTML DIRECTEMENT ---
    public function addCommentAjax() {
        if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Non autorisé']); exit;
        }
        
        $userId = $_SESSION['user_id'];
        $postId = $_POST['post_id'] ?? 0;
        $content = trim($_POST['commentaire'] ?? '');

        if ($content === '' || empty($postId)) {
            echo json_encode(['success' => false, 'message' => 'Vide']); exit;
        }

        $commentModel = new CommentModel($this->pdo);
        $voteModel = new VoteModel($this->pdo);

        // 1. Snapshot du vote
        $voteSnapshot = $voteModel->getUserVote($userId, $postId);

        // 2. Ajout BDD
        $newCommentId = $commentModel->addComment($userId, $postId, $content, $voteSnapshot);

        if ($newCommentId) {
            // 3. Récupérer les infos
            $com = $commentModel->getCommentById($newCommentId);
            
            // 4. CONSTRUIRE LE HTML DIRECTEMENT ICI (Pas besoin de fichier externe)
            $avatar = !empty($com['photo_profil']) ? 'uploads/'.$com['photo_profil'] : 'https://via.placeholder.com/40';
            $pseudo = htmlspecialchars($com['pseudo']);
            $date = $com['date_com']; // Ou formater si besoin
            $texte = nl2br(htmlspecialchars($com['commentaire']));
            $linkProfile = "index.php?controller=User&action=profile&id=".$com['id_utilisateur'];
            
            // Gestion badge vote
            $badgeVote = '';
            if ($com['vote_at_time'] == 1) {
                $badgeVote = '<span style="color: var(--success); font-size: 0.8em; margin-left: 5px; background: rgba(40, 167, 69, 0.1); padding: 2px 6px; border-radius: 4px;"><i class="fa-solid fa-thumbs-up"></i> A aimé</span>';
            } elseif ($com['vote_at_time'] == -1) {
                $badgeVote = '<span style="color: var(--danger); font-size: 0.8em; margin-left: 5px; background: rgba(220, 53, 69, 0.1); padding: 2px 6px; border-radius: 4px;"><i class="fa-solid fa-thumbs-down"></i> N\'a pas aimé</span>';
            }

            // Le bloc HTML complet
            $html = '
            <div class="generic-item" style="align-items: flex-start; margin-bottom: 10px; cursor: default;">
                <a href="'.$linkProfile.'">
                    <img src="'.$avatar.'" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; margin-right: 15px; border: 1px solid var(--border-color);">
                </a>
                <div style="flex:1;">
                    <div style="display:flex; justify-content:space-between; align-items: center;">
                        <div>
                            <strong>'.$pseudo.'</strong>
                            '.$badgeVote.'
                        </div>
                        <small style="color:var(--text-secondary); font-size:0.8em;">'.$date.'</small>
                    </div>
                    <p style="margin: 5px 0 0 0; line-height: 1.4; color:var(--text-color);">'.$texte.'</p>
                </div>
            </div>';

            echo json_encode(['success' => true, 'html' => $html]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erreur bdd']);
        }
        exit;
    }

    public function voteAjax() {
        // (Ta fonction voteAjax existante reste ici, inchangée...)
        if (!isset($_SESSION['user_id']) || !isset($_POST['id']) || !isset($_POST['value'])) { echo json_encode(['success'=>false]); return; }
        $userId = $_SESSION['user_id']; $postId = $_POST['id']; $value = (int)$_POST['value'];
        if (!in_array($value, [1, -1])) { echo json_encode(['success'=>false]); return; }
        $voteModel = new VoteModel($this->pdo); $voteModel->toggleVote($userId, $postId, $value);
        $stats = $voteModel->getPostStats($postId); $userVote = $voteModel->getUserVote($userId, $postId);
        echo json_encode(['success'=>true, 'likes'=>$stats['nb_likes'], 'dislikes'=>$stats['nb_dislikes'], 'userVote'=>$userVote]);
    }
}

?>