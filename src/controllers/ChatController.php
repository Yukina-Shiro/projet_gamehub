<?php
require_once 'controllers/Controller.php';
require_once 'models/ChatModel.php';
require_once 'models/UserModel.php';

class ChatController extends Controller {
    
    // Page principale : Liste des discussions
    public function index() {
        if (!isset($_SESSION['user_id'])) $this->redirect('index.php');
        
        $chatModel = new ChatModel($this->pdo);
        $conversations = $chatModel->getMyConversations($_SESSION['user_id']);

        $this->render('chat/index', ['conversations' => $conversations]);
    }

    // Page de conversation avec une personne
    public function conversation() {
        if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) $this->redirect('index.php');
        
        $chatModel = new ChatModel($this->pdo);
        $userModel = new UserModel($this->pdo);

        $otherId = $_GET['id'];
        $me = $_SESSION['user_id'];

        // --- NOUVEAU : MARQUER COMME LU ---
        $chatModel->markAsRead($me, $otherId);

        // Envoyer un message (si formulaire soumis)
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['content'])) {
            $chatModel->sendMessage($me, $otherId, $_POST['content']);
            header("Location: index.php?controller=Chat&action=conversation&id=$otherId");
            exit;
        }

        $messages = $chatModel->getConversation($me, $otherId);
        $otherUser = $userModel->getById($otherId);

        $this->render('chat/conversation', ['messages' => $messages, 'otherUser' => $otherUser]);
    }

    // Partager un post (AJAX) - Déjà fait, on le garde
    public function sharePost() {
        header('Content-Type: application/json');
        if (!isset($_SESSION['user_id'])) { echo json_encode(['success' => false]); exit; }
        $chat = new ChatModel($this->pdo);
        $chat->sendMessage($_SESSION['user_id'], $_POST['friend_id'], "Regarde ce post !", $_POST['post_id']);
        echo json_encode(['success' => true]); exit;
    }
}

?>