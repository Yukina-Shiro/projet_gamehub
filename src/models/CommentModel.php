<?php
require_once 'models/Model.php';

class CommentModel extends Model {

    public function getCommentsSorted($postId, $currentUserId, $sort = 'pertinence') {
        $sql = "SELECT c.*, u.pseudo, u.photo_profil, 
                (SELECT COUNT(*) FROM ami a WHERE 
                    (a.id_utilisateur1 = :me AND a.id_utilisateur2 = c.id_utilisateur) 
                    OR 
                    (a.id_utilisateur1 = c.id_utilisateur AND a.id_utilisateur2 = :me)
                    AND a.statut = 'valide'
                ) as is_friend
                FROM commentaire c
                JOIN utilisateur u ON c.id_utilisateur = u.id_utilisateur
                WHERE c.id_post = :postId ";

        // --- LOGIQUE DE TRI DYNAMIQUE ---
        if ($sort === 'old') {
            // Plus anciens en premier
            $sql .= "ORDER BY c.date_com ASC";
        } elseif ($sort === 'new') {
            // Plus récents en premier
            $sql .= "ORDER BY c.date_com DESC";
        } else {
            // Pertinence : Amis d'abord, puis les plus récents
            $sql .= "ORDER BY is_friend DESC, c.date_com DESC";
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['postId' => $postId, 'me' => $currentUserId]);
        return $stmt->fetchAll();
    }
    
    public function getCommentById($commentId) {
        $sql = "SELECT c.*, u.pseudo, u.photo_profil
                FROM commentaire c
                JOIN utilisateur u ON c.id_utilisateur = u.id_utilisateur
                WHERE c.id_commentaire = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$commentId]);
        return $stmt->fetch();
    }

    public function addComment($userId, $postId, $content, $voteSnapshot = 0) {
        $sql = "INSERT INTO commentaire (id_utilisateur, id_post, commentaire, vote_at_time, date_com) 
                VALUES (?, ?, ?, ?, NOW())";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$userId, $postId, $content, $voteSnapshot]);
        
        
        $newCommentId = $this->pdo->lastInsertId();
        
        $this->notifyPostOwner($userId, $postId);
        
        return $newCommentId;
    }

    private function notifyPostOwner($senderId, $postId) {
        $stmt = $this->pdo->prepare("SELECT id_utilisateur FROM post WHERE id_post = ?");
        $stmt->execute([$postId]);
        $post = $stmt->fetch();
        if ($post && $post['id_utilisateur'] != $senderId) {
            $msg = "a commenté votre publication.";
            $sqlNotif = "INSERT INTO notification (id_destinataire, id_emetteur, type, message, date_notif) VALUES (?, ?, 'comment', ?, NOW())";
            $stmtNotif = $this->pdo->prepare($sqlNotif);
            $stmtNotif->execute([$post['id_utilisateur'], $senderId, $msg]);
        }
    }
}

?>