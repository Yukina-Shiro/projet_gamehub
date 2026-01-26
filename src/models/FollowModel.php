<?php
require_once 'models/Model.php';
require_once 'models/NotificationModel.php';

class FollowModel extends Model {

    public function isFollowing($me, $other) {
        $sql = "SELECT * FROM relation WHERE suiveur = ? AND suivi = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$me, $other]);
        return $stmt->fetch();
    }

    // Récupérer qui me suit (pour notifier quand je poste)
    public function getFollowersIds($userId) {
        $sql = "SELECT suiveur FROM relation WHERE suivi = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function follow($me, $other) {
        $sql = "INSERT INTO relation (suiveur, suivi) VALUES (?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$me, $other]);

        // Notification
        $notif = new NotificationModel($this->pdo);
        $notif->add($other, $me, 'follow', "a commencé à vous suivre.");
    }

    public function unfollow($me, $other) {
        $sql = "DELETE FROM relation WHERE suiveur = ? AND suivi = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$me, $other]);
    }

    // Récupérer la liste des gens que je SUIS (Abonnements)
    public function getFollowingList($userId) {
        $sql = "SELECT u.id_utilisateur, u.pseudo, u.photo_profil
                FROM utilisateur u
                JOIN relation r ON u.id_utilisateur = r.suivi
                WHERE r.suiveur = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }
}

?>