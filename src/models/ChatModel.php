<?php
require_once 'models/Model.php';

class ChatModel extends Model {
    // Envoyer un message (texte ou partage de post)
    public function sendMessage($from, $to, $msg, $postId = null) {
        $sql = "INSERT INTO Message (id_emetteur, id_destinataire, contenu, id_post_partage) VALUES (?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$from, $to, $msg, $postId]);
    }

    // Récupérer la conversation entre deux personnes
    public function getConversation($user1, $user2) {
        $sql = "SELECT m.*, u.pseudo, u.photo_profil 
                FROM Message m
                JOIN utilisateur u ON m.id_emetteur = u.id_utilisateur
                WHERE (id_emetteur = ? AND id_destinataire = ?) 
                   OR (id_emetteur = ? AND id_destinataire = ?)
                ORDER BY date_envoi ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$user1, $user2, $user2, $user1]);
        return $stmt->fetchAll();
    }

    // Récupérer la liste des gens avec qui j'ai parlé (Mes conversations)
    public function getMyConversations($myId) {
        $sql = "SELECT DISTINCT u.id_utilisateur, u.pseudo, u.photo_profil
                FROM utilisateur u
                JOIN Message m ON (u.id_utilisateur = m.id_emetteur OR u.id_utilisateur = m.id_destinataire)
                WHERE (m.id_emetteur = ? OR m.id_destinataire = ?)
                AND u.id_utilisateur != ?"; // On exclut mon propre ID
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$myId, $myId, $myId]);
        return $stmt->fetchAll();
    }

    // Compter les messages non lus (pour le badge rouge)
    public function countUnreadMessages($userId) {
        $sql = "SELECT COUNT(*) FROM Message WHERE id_destinataire = ? AND lu = 0";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchColumn();
    }

    // Marquer les messages d'une conversation comme lus
    public function markAsRead($me, $otherId) {
        $sql = "UPDATE Message SET lu = 1 WHERE id_destinataire = ? AND id_emetteur = ? AND lu = 0";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$me, $otherId]);
    }
}

?>