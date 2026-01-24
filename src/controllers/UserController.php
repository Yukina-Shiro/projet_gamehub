<?php

require_once 'controllers/Controller.php';
require_once 'models/UserModel.php';
require_once 'models/PostModel.php';
require_once 'models/FriendModel.php';
require_once 'models/FollowModel.php';
require_once 'models/NotificationModel.php';

class UserController extends Controller {

    public function profile() {
        if (!isset($_SESSION['user_id'])) $this->redirect('index.php?controller=Auth&action=login');

        $userModel = new UserModel($this->pdo);
        $postModel = new PostModel($this->pdo);
        $friendModel = new FriendModel($this->pdo);
        $followModel = new FollowModel($this->pdo);
        $notifModel = new NotificationModel($this->pdo);

        $idToDisplay = isset($_GET['id']) ? $_GET['id'] : $_SESSION['user_id'];

        $user = $userModel->getById($idToDisplay);

        if (!$user) die("Utilisateur introuvable.");

        $friendsList = $friendModel->getFriendsList($idToDisplay);
        $followingList = $followModel->getFollowingList($idToDisplay);

        $posts = $postModel->getUserPosts($idToDisplay);
        $isMe = ($idToDisplay == $_SESSION['user_id']);
        
        // Utilisation des modèles séparés
        $friendStatus = $isMe ? null : $friendModel->getFriendshipStatus($_SESSION['user_id'], $idToDisplay);
        $isFollowing = $isMe ? null : $followModel->isFollowing($_SESSION['user_id'], $idToDisplay);

        $this->render('user/profile', [
            'user' => $user, 
            'posts' => $posts,
            'isMe' => $isMe,
            'friendStatus' => $friendStatus,
            'isFollowing' => $isFollowing,
            'friendsList' => $friendsList, 
            'followingList' => $followingList 
        ]);
    }

    // ACTIONS SOCIALES (Dispatch vers les bons modèles)
    public function follow() {
        $model = new FollowModel($this->pdo);
        $model->follow($_SESSION['user_id'], $_GET['id']);
        header("Location: " . $_SERVER['HTTP_REFERER']);
    }

    public function unfollow() {
        $model = new FollowModel($this->pdo);
        $model->unfollow($_SESSION['user_id'], $_GET['id']);
        header("Location: " . $_SERVER['HTTP_REFERER']);
    }

    public function addFriend() {
        $model = new FriendModel($this->pdo);
        $model->sendRequest($_SESSION['user_id'], $_GET['id']);
        header("Location: " . $_SERVER['HTTP_REFERER']);
    }

    public function acceptFriend() {
        $model = new FriendModel($this->pdo);
        $model->acceptRequest($_SESSION['user_id'], $_GET['id']);
        header("Location: " . $_SERVER['HTTP_REFERER']);
    }
    
    public function refuseRequest() {
        if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
            $this->redirect('index.php');
        }

        $friendId = $_GET['id'];
        $myId = $_SESSION['user_id'];

        $friendModel = new FriendModel($this->pdo);
        $notifModel = new NotificationModel($this->pdo);

        // 1. On supprime la ligne dans la table ami (ce qui annule la demande)
        // Cela a pour effet immédiat de remettre le bouton sur "Ajouter en ami" sur le profil
        $friendModel->removeFriend($myId, $friendId);

        // 2. On notifie l'autre personne du refus
        $notifModel->add($friendId, $myId, 'refus_ami', "a refusé votre demande d'ami.");

        // 3. (Optionnel) On supprime la notification de demande que JE viens de traiter
        // On cherche la notif de type 'demande_ami' venant de cet ami pour moi
        $sql = "DELETE FROM notification WHERE id_destinataire = ? AND id_emetteur = ? AND type = 'demande_ami'";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$myId, $friendId]);

        // Retour à la page des notifications
        header("Location: index.php?controller=Notification&action=index");
        exit;
    }

    public function removeFriend() {
        $model = new FriendModel($this->pdo);
        $model->removeFriend($_SESSION['user_id'], $_GET['id']);
        header("Location: " . $_SERVER['HTTP_REFERER']);
    }

    // La méthode edit() reste inchangée (gestion photo profil...)
    public function edit() {
        if (!isset($_SESSION['user_id'])) $this->redirect('index.php?controller=Auth&action=login');
        $userModel = new UserModel($this->pdo);
        $user = $userModel->getById($_SESSION['user_id']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // (Le code d'update profil reste le même que la dernière fois)
            $pseudo = $_POST['pseudo']; $nom = $_POST['nom']; $prenom = $_POST['prenom']; $bio = $_POST['bio']; $photoName = null;
            if (isset($_FILES['photo_profil']) && $_FILES['photo_profil']['error'] === 0) {
                 // ... Code upload ...
                 $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                 $filename = $_FILES['photo_profil']['name'];
                 $filetype = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                 if (in_array($filetype, $allowed)) {
                     $targetDir = dirname(__DIR__) . '/uploads/';
                     if (!is_dir($targetDir)) mkdir($targetDir, 0755, true);
                     $newName = "profil_" . $_SESSION['user_id'] . "_" . time() . "." . $filetype;
                     if (move_uploaded_file($_FILES['photo_profil']['tmp_name'], $targetDir . $newName)) $photoName = $newName;
                 }
            }
            
            $userModel->updateUser($_SESSION['user_id'], $pseudo, $bio, $nom, $prenom, $photoName);
            $_SESSION['pseudo'] = $pseudo;
            $this->redirect('index.php?controller=User&action=profile');
        }

        $this->render('user/edit', ['user' => $user]);
    }
}

?>