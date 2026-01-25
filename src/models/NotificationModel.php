<?php
require_once 'models/Model.php';

class NotificationModel extends Model {

    public function add($dest, $emetteur, $type, $msg) {
        $sql = "INSERT INTO Notification (id_destinataire, id_emetteur, type, message) VALUES (?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$dest, $emetteur, $type, $msg]);
    }

    // Récupérer toutes les notifs AVEC le statut d'amitié
    public function getMyNotifications($userId) {
        // La sous-requête (SELECT statut ...) va chercher si une relation existe dans la table ami
        $sql = "SELECT n.*, u.pseudo, u.photo_profil,
                (SELECT statut FROM Ami WHERE 
                    (id_utilisateur1 = n.id_emetteur AND id_utilisateur2 = n.id_destinataire)
                    OR 
                    (id_utilisateur1 = n.id_destinataire AND id_utilisateur2 = n.id_emetteur)
                LIMIT 1) as statut_ami
                
                FROM Notification n
                JOIN Utilisateur u ON n.id_emetteur = u.id_utilisateur
                WHERE n.id_destinataire = ?
                ORDER BY n.date_notif DESC";
                
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    // Compter les non-lues (pour le point rouge)
    public function countUnread($userId) {
        $sql = "SELECT COUNT(*) FROM Notification WHERE id_destinataire = ? AND lu = 0";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchColumn();
    }

    // Marquer comme lue
    public function markAsRead($notifId) {
        $sql = "UPDATE Notification SET lu = 1 WHERE id_notif = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$notifId]);
    }

    // Supprimer une notif
    public function delete($notifId) {
        $sql = "DELETE FROM Notification WHERE id_notif = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$notifId]);
    }
}

?>