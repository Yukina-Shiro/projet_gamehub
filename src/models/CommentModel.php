<?php
require_once 'models/Model.php';
require_once 'models/NotificationModel.php';

class CommentModel extends Model {

    public function addComment($userId, $postId, $text) {
        $sql = "INSERT INTO commentaire (id_utilisateur, id_post, commentaire) VALUES (?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$userId, $postId, $text]);

        // Notification
        $stmtPost = $this->pdo->prepare("SELECT id_utilisateur FROM post WHERE id_post = ?");
        $stmtPost->execute([$postId]);
        $authorId = $stmtPost->fetchColumn();

        if ($authorId != $userId) {
            $notif = new NotificationModel($this->pdo);
            $notif->add($authorId, $userId, 'comment', "a commenté votre post.");
        }
    }

    // L'ALGORITHME DE TRI INTELLIGENT
    public function getCommentsSorted($postId, $viewerId, $sortType = 'pertinence') {
        // On récupère d'abord le vote du visiteur pour savoir ce qu'il préfère
        $stmtVote = $this->pdo->prepare("SELECT vote FROM vote WHERE id_utilisateur = ? AND id_post = ?");
        $stmtVote->execute([$viewerId, $postId]);
        $myVote = $stmtVote->fetchColumn(); // 1, -1 ou false

        // La requête de base récupère le commentaire + infos user + si c'est un ami + le vote de l'auteur du commentaire
        $sql = "SELECT c.*, u.pseudo, u.photo_profil,
                (SELECT vote FROM vote WHERE id_utilisateur = c.id_utilisateur AND id_post = c.id_post) as author_vote,
                (SELECT COUNT(*) FROM ami WHERE (id_utilisateur1 = :me AND id_utilisateur2 = c.id_utilisateur) OR (id_utilisateur1 = c.id_utilisateur AND id_utilisateur2 = :me)) as is_friend
                FROM commentaire c
                JOIN utilisateur u ON c.id_utilisateur = u.id_utilisateur
                WHERE c.id_post = :postid ";

        // Gestion des tris
        if ($sortType === 'recent') {
            $sql .= "ORDER BY c.date_com DESC";
        } 
        elseif ($sortType === 'old') {
            $sql .= "ORDER BY c.date_com ASC";
        }
        elseif ($sortType === 'pertinence') {
            // 1. D'abord les amis (is_friend DESC)
            // 2. Ensuite ceux qui ont voté comme moi (Si j'ai liké, je veux voir les likers)
            
            $sql .= "ORDER BY is_friend DESC, ";
            
            if ($myVote == 1) {
                // Je like : je veux voir les votes positifs (1) en premier
                $sql .= "author_vote DESC, c.date_com DESC"; 
            } elseif ($myVote == -1) {
                // Je dislike : je veux voir les votes négatifs (-1) en premier
                $sql .= "author_vote ASC, c.date_com DESC";
            } else {
                // Je suis neutre : tri par date classique
                $sql .= "c.date_com DESC";
            }
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':me' => $viewerId, ':postid' => $postId]);
        return $stmt->fetchAll();
    }
}

?>