<?php
require_once 'models/Model.php';

class NotificationModel extends Model {

    public function add($dest, $emetteur, $type, $msg) {
        $sql = "INSERT INTO notification (id_destinataire, id_emetteur, type, message) VALUES (?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$dest, $emetteur, $type, $msg]);
    }

    // Récupérer toutes les notifs avec le statut d'amitié
    public function getMyNotifications($userId) {
        $sql = "SELECT n.*, u.pseudo, u.photo_profil,
                (SELECT statut FROM ami WHERE 
                    (id_utilisateur1 = n.id_emetteur AND id_utilisateur2 = n.id_destinataire)
                    OR 
                    (id_utilisateur1 = n.id_destinataire AND id_utilisateur2 = n.id_emetteur)
                LIMIT 1) as statut_ami
                
                FROM notification n
                JOIN utilisateur u ON n.id_emetteur = u.id_utilisateur
                WHERE n.id_destinataire = ?
                ORDER BY n.date_notif DESC";
                
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    // Compter les non-lues (pour le point rouge)
    public function countUnread($userId) {
        $sql = "SELECT COUNT(*) FROM notification WHERE id_destinataire = ? AND lu = 0";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchColumn();
    }

    // Marquer comme lue une notification
    public function markAsRead($notifId) {
        $sql = "UPDATE notification SET lu = 1 WHERE id_notif = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$notifId]);
    }

    // Marquer comme lue toutes les notifs d'un utilisateur
    public function markAllAsRead($userId) {
        $sql = "UPDATE notification SET lu = 1 WHERE id_destinataire = ? AND lu = 0";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$userId]);
    }

    // Supprimer une notif
    public function delete($notifId) {
        $sql = "DELETE FROM notification WHERE id_notif = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$notifId]);
    }

    // Récupérer les notifications non lues
    public function getUnreadNotifications($userId) {
        $sql = "SELECT id_notif FROM notification WHERE id_destinataire = ? AND lu = 0";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>