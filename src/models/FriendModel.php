<?php
require_once 'models/Model.php';
require_once 'models/NotificationModel.php'; // Pour notifier

class FriendModel extends Model {

    // Vérifie le statut (sont-ils amis ? demande en cours ?)
    public function getFriendshipStatus($me, $other) {
        $sql = "SELECT statut, id_utilisateur1 FROM ami 
                WHERE (id_utilisateur1 = ? AND id_utilisateur2 = ?) 
                   OR (id_utilisateur1 = ? AND id_utilisateur2 = ?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$me, $other, $other, $me]);
        return $stmt->fetch();
    }

    // Récupérer la liste des IDs de mes amis (validés)
    public function getFriendsIds($userId) {
        $sql = "SELECT id_utilisateur1 as id FROM ami WHERE id_utilisateur2 = ? AND statut = 'valide'
                UNION
                SELECT id_utilisateur2 as id FROM ami WHERE id_utilisateur1 = ? AND statut = 'valide'";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$userId, $userId]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function sendRequest($from, $to) {
        $sql = "INSERT INTO ami (id_utilisateur1, id_utilisateur2, statut) VALUES (?, ?, 'attente')";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$from, $to]);
        
        // Notification
        $notif = new NotificationModel($this->pdo);
        $notif->add($to, $from, 'demande_ami', "veut être votre ami.");
    }

    public function acceptRequest($me, $friendId) {
        $sql = "UPDATE ami SET statut = 'valide' 
                WHERE (id_utilisateur1 = ? AND id_utilisateur2 = ?) 
                   OR (id_utilisateur1 = ? AND id_utilisateur2 = ?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$me, $friendId, $friendId, $me]);

        // Notification
        $notif = new NotificationModel($this->pdo);
        $notif->add($friendId, $me, 'accept_ami', "a accepté votre demande d'ami.");
    }

    public function removeFriend($me, $other) {
        $sql = "DELETE FROM ami 
                WHERE (id_utilisateur1 = ? AND id_utilisateur2 = ?) 
                   OR (id_utilisateur1 = ? AND id_utilisateur2 = ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$me, $other, $other, $me]);
    }

    // Récupérer la liste COMPLÈTE des amis (Pseudo + Photo) pour l'affichage
    public function getFriendsList($userId) {
        $sql = "SELECT u.id_utilisateur, u.pseudo, u.photo_profil
                FROM utilisateur u
                JOIN ami a ON (u.id_utilisateur = a.id_utilisateur1 OR u.id_utilisateur = a.id_utilisateur2)
                WHERE (a.id_utilisateur1 = ? OR a.id_utilisateur2 = ?)
                AND a.statut = 'valide'
                AND u.id_utilisateur != ?"; // On exclut notre propre ID
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$userId, $userId, $userId]);
        return $stmt->fetchAll();
    }
}

?>