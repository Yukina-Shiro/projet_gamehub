<?php

require_once 'models/Model.php';
require_once 'models/FriendModel.php';
require_once 'models/FollowModel.php';
require_once 'models/NotificationModel.php';

class PostModel extends Model {

    // --- LE MUR (FEED) ---
    public function getFeed($myId) {
        $sql = "
            SELECT p.*, u.pseudo, u.photo_profil,
            (SELECT IFNULL(SUM(vote), 0) FROM vote WHERE id_post = p.id_post) as score
            FROM post p
            JOIN utilisateur u ON p.id_utilisateur = u.id_utilisateur
            WHERE 
                p.statut = 'public'
                OR p.id_utilisateur = ? 
                OR (
                    p.statut = 'ami' 
                    AND p.id_utilisateur IN (
                        SELECT id_utilisateur1 FROM ami WHERE id_utilisateur2 = ? AND statut = 'valide'
                        UNION
                        SELECT id_utilisateur2 FROM ami WHERE id_utilisateur1 = ? AND statut = 'valide'
                    )
                )
            ORDER BY p.date_creation DESC LIMIT 50";
        
        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([$myId, $myId, $myId]);
        return $stmt->fetchAll();
    }

    // --- POSTS D'UN PROFIL ---
    public function getUserPosts($userId) {
        $sql = "SELECT p.*, u.pseudo, u.photo_profil,
                (SELECT IFNULL(SUM(vote), 0) FROM vote WHERE id_post = p.id_post) as score
                FROM post p
                JOIN utilisateur u ON p.id_utilisateur = u.id_utilisateur
                WHERE p.id_utilisateur = ?
                ORDER BY p.date_creation DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    // --- CRÉATION ---
    public function createPost($userId, $titre, $description, $visibilite, $photo = null) {
        $sql = "INSERT INTO post (id_utilisateur, titre, description, statut, photo) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $success = $stmt->execute([$userId, $titre, $description, $visibilite, $photo]);

        if ($success) {
            $this->notifyNetwork($userId);
        }
        return $success;
    }

    // Méthode privée pour notifier le réseau via les autres modèles
    private function notifyNetwork($userId) {
        $friendModel = new FriendModel($this->pdo);
        $followModel = new FollowModel($this->pdo);
        $notifModel = new NotificationModel($this->pdo);

        // Récupérer les IDs des destinataires
        $amis = $friendModel->getFriendsIds($userId);
        $followers = $followModel->getFollowersIds($userId);

        // Fusionner et dédoublonner
        $ids = array_unique(array_merge($amis, $followers));

        foreach($ids as $destId) {
            $notifModel->add($destId, $userId, 'new_post', "a publié un nouveau post !");
        }
    }

    // --- MODIFICATION / SUPPRESSION ---
    public function getPostById($idPost) {
        $sql = "SELECT * FROM post WHERE id_post = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$idPost]);
        return $stmt->fetch();
    }

    public function updatePost($idPost, $titre, $desc, $statut) {
        $sql = "UPDATE post SET titre = ?, description = ?, statut = ? WHERE id_post = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$titre, $desc, $statut, $idPost]);
    }

    public function deletePost($idPost) {
        $sql = "DELETE FROM post WHERE id_post = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$idPost]);
    }
}

?>