<?php
require_once 'models/Model.php';
require_once 'models/NotificationModel.php';

class VoteModel extends Model {

    // Récupérer le vote actuel d'un utilisateur sur un post
    public function getUserVote($userId, $postId) {
        $sql = "SELECT vote FROM vote WHERE id_utilisateur = ? AND id_post = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$userId, $postId]);
        return $stmt->fetchColumn(); // Renvoie 1 (Like), -1 (Dislike) ou false
    }

    // Récupérer les stats (Nombre de likes, dislikes, commentaires)
    public function getPostStats($postId) {
        $sql = "SELECT 
                (SELECT COUNT(*) FROM vote WHERE id_post = ? AND vote = 1) as nb_likes,
                (SELECT COUNT(*) FROM vote WHERE id_post = ? AND vote = -1) as nb_dislikes,
                (SELECT COUNT(*) FROM commentaire WHERE id_post = ?) as nb_comments";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$postId, $postId, $postId]);
        return $stmt->fetch();
    }

    // Voter (Like = 1, Dislike = -1)
    public function toggleVote($userId, $postId, $value) {
        // 1. On vérifie si un vote existe déjà
        $current = $this->getUserVote($userId, $postId);
        $notif = new NotificationModel($this->pdo);

        // Récupérer l'auteur du post pour la notif
        $stmt = $this->pdo->prepare("SELECT id_utilisateur FROM post WHERE id_post = ?");
        $stmt->execute([$postId]);
        $authorId = $stmt->fetchColumn();

        if ($current == $value) {
            // J'ai déjà voté ça -> J'annule mon vote (click sur le même bouton)
            $sql = "DELETE FROM vote WHERE id_utilisateur = ? AND id_post = ?";
            $this->pdo->prepare($sql)->execute([$userId, $postId]);
        } else {
            // Nouveau vote ou changement d'avis
            // On supprime l'ancien s'il existe
            $this->pdo->prepare("DELETE FROM vote WHERE id_utilisateur = ? AND id_post = ?")->execute([$userId, $postId]);
            
            // On insère le nouveau
            $sql = "INSERT INTO vote (id_utilisateur, id_post, vote) VALUES (?, ?, ?)";
            $this->pdo->prepare($sql)->execute([$userId, $postId, $value]);

            // Notification (seulement si ce n'est pas mon propre post)
            if ($authorId != $userId) {
                $msg = ($value == 1) ? "a liké votre post." : "a disliké votre post.";
                $notif->add($authorId, $userId, 'vote', $msg);
            }
        }
    }
}

?>