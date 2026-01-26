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

        // 1. Récupération du post
        $post = $postModel->getPostById($postId);
        if (!$post) {
            $_SESSION['error'] = "Ce post n'existe plus.";
            $this->redirect('index.php');
        }

        // 2. Traitement commentaire
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['commentaire'])) {
            if (!$userId) $this->redirect('index.php?controller=Auth&action=login');
            
            $commentModel->addComment($userId, $postId, $_POST['commentaire']);
            header("Location: index.php?controller=Post&action=show&id=$postId&sort=$sort");
            exit;
        }

        // 3. Données associées
        $stats = $voteModel->getPostStats($postId);
        $myVote = $voteModel->getUserVote($userId, $postId);
        $comments = $commentModel->getCommentsSorted($postId, $userId, $sort);

        // 4. Affichage (CORRECTION ICI : suppression des espaces)
        $this->render('post_detail', [
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

    // --- AJAX : RECHARGER LES COMMENTAIRES (TRI) ---
    public function reloadCommentsAjax() {
        if (!isset($_SESSION['user_id'])) { echo json_encode(['success'=>false]); exit; }
        
        $postId = $_GET['id'] ?? 0;
        $sort = $_GET['sort'] ?? 'pertinence';
        $userId = $_SESSION['user_id'];

        $commentModel = new CommentModel($this->pdo);
        $comments = $commentModel->getCommentsSorted($postId, $userId, $sort);

        // On construit le HTML de toute la liste
        $html = '';
        if(empty($comments)) {
            $html = '<p id="no-comment-msg" style="text-align:center; color:var(--text-secondary); margin-top:20px;">Soyez le premier à commenter !</p>';
        } else {
            foreach($comments as $com) {
                // Logique d'affichage identique à addCommentAjax
                $avatar = !empty($com['photo_profil']) ? 'uploads/'.$com['photo_profil'] : 'https://via.placeholder.com/40';
                $pseudo = htmlspecialchars($com['pseudo']);
                $date = $com['date_com'];
                $texte = nl2br(htmlspecialchars($com['commentaire']));
                $linkProfile = "index.php?controller=User&action=profile&id=".$com['id_utilisateur'];
                
                $badgeVote = '';
                if ($com['vote_at_time'] == 1) {
                    $badgeVote = '<span style="color: var(--success); font-size: 0.8em; margin-left: 5px; background: rgba(40, 167, 69, 0.1); padding: 2px 6px; border-radius: 4px;"><i class="fa-solid fa-thumbs-up"></i> A aimé</span>';
                } elseif ($com['vote_at_time'] == -1) {
                    $badgeVote = '<span style="color: var(--danger); font-size: 0.8em; margin-left: 5px; background: rgba(220, 53, 69, 0.1); padding: 2px 6px; border-radius: 4px;"><i class="fa-solid fa-thumbs-down"></i> N\'a pas aimé</span>';
                }

                $html .= '
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
            }
        }
        
        echo json_encode(['success' => true, 'html' => $html]);
        exit;
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
}
?>