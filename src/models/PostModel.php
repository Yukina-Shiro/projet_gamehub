<?php

require_once 'models/Model.php';
require_once 'models/FriendModel.php';
require_once 'models/FollowModel.php';
require_once 'models/NotificationModel.php';

class PostModel extends Model {

    // FIL GLOBAL : Masque les posts bloqués (is_blocked = 0)
    public function getGlobalFeed($myId) {
        $sql = "SELECT p.*, u.pseudo, u.photo_profil
                FROM post p
                JOIN utilisateur u ON p.id_utilisateur = u.id_utilisateur
                WHERE (p.statut = 'public'
                   OR p.id_utilisateur = ?
                   OR (p.statut = 'ami' AND p.id_utilisateur IN (
                        SELECT id_utilisateur1 FROM ami WHERE id_utilisateur2 = ? AND statut = 'valide'
                        UNION SELECT id_utilisateur2 FROM ami WHERE id_utilisateur1 = ? AND statut = 'valide'
                   )))
                AND p.is_blocked = 0
                ORDER BY p.date_creation DESC LIMIT 50";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$myId, $myId, $myId]);
        return $stmt->fetchAll();
    }

    // --- FIL PERSO AVEC FILTRES AVANCÉS ---
    public function getPersoFeed($myId, $sourceFilter = 'all', $specificDate = null) {
        
        $sql = "SELECT p.*, u.pseudo, u.photo_profil
                FROM post p
                JOIN utilisateur u ON p.id_utilisateur = u.id_utilisateur
                WHERE ";

        $conditions = [];
        $params = [];

        // 1. Toujours inclure MES posts
        $myPostCondition = "p.id_utilisateur = ?";
        
        // 2. Gestion de la source (Qui ?)
        if ($sourceFilter === 'amis') {
            // Uniquement Amis (+ Moi)
            $conditions[] = "($myPostCondition OR p.id_utilisateur IN (
                SELECT id_utilisateur1 FROM ami WHERE id_utilisateur2 = ? AND statut = 'valide'
                UNION 
                SELECT id_utilisateur2 FROM ami WHERE id_utilisateur1 = ? AND statut = 'valide'
            ))";
            $params[] = $myId; // Pour moi
            $params[] = $myId; // Pour amis 1
            $params[] = $myId; // Pour amis 2
        } 
        elseif ($sourceFilter === 'abo') {
            // Uniquement Abonnements (+ Moi)
            $conditions[] = "($myPostCondition OR p.id_utilisateur IN (
                SELECT suivi FROM relation WHERE suiveur = ?
            ))";
            $params[] = $myId; // Pour moi
            $params[] = $myId; // Pour abos
        } 
        else {
            // Par défaut 'all' : Amis + Abonnements (+ Moi)
            $conditions[] = "($myPostCondition OR p.id_utilisateur IN (
                SELECT id_utilisateur1 FROM ami WHERE id_utilisateur2 = ? AND statut = 'valide'
                UNION 
                SELECT id_utilisateur2 FROM ami WHERE id_utilisateur1 = ? AND statut = 'valide'
            ) OR p.id_utilisateur IN (
                SELECT suivi FROM relation WHERE suiveur = ?
            ))";
            $params[] = $myId; // Pour moi
            $params[] = $myId; // Pour amis 1
            $params[] = $myId; // Pour amis 2
            $params[] = $myId; // Pour abos
        }

        // 3. Gestion de la Date (Quand ?)
        if (!empty($specificDate)) {
            // On compare la partie DATE de la colonne datetime (YYYY-MM-DD)
            $conditions[] = "DATE(p.date_creation) = ?";
            $params[] = $specificDate;
        }

        // Assemblage
        $sql .= implode(" AND ", $conditions);

        // Tri par défaut (toujours du plus récent au plus vieux pour l'affichage)
        $sql .= " ORDER BY p.date_creation DESC LIMIT 50";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    // POSTS D'UN PROFIL : Masque les bloqués pour les autres, montre tout à l'admin
    public function getUserPosts($userId) {
        $sql = "SELECT p.*, u.pseudo, u.photo_profil,
                (SELECT IFNULL(SUM(vote), 0) FROM vote WHERE id_post = p.id_post) as score
                FROM post p
                JOIN utilisateur u ON p.id_utilisateur = u.id_utilisateur
                WHERE p.id_utilisateur = ?
                AND p.is_blocked = 0
                ORDER BY p.date_creation DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    // ADMIN : Récupère absolument tout
    public function getAllPostsForAdmin() {
        $sql = "SELECT p.*, u.pseudo FROM post p
                JOIN utilisateur u ON p.id_utilisateur = u.id_utilisateur
                ORDER BY p.date_creation DESC";
        return $this->pdo->query($sql)->fetchAll();
    }

    public function createPost($userId, $titre, $description, $visibilite, $photo = null) {
        $sql = "INSERT INTO post (id_utilisateur, titre, description, statut, photo, date_creation) VALUES (?, ?, ?, ?, ?, NOW())";
        $stmt = $this->pdo->prepare($sql);
        $success = $stmt->execute([$userId, $titre, $description, $visibilite, $photo]);

        if ($success) {
            $this->notifyNetwork($userId);
        }
        return $success;
    }

    private function notifyNetwork($userId) {
        $friendModel = new FriendModel($this->pdo);
        $followModel = new FollowModel($this->pdo);
        $notifModel = new NotificationModel($this->pdo);

        $amis = $friendModel->getFriendsIds($userId);
        $followers = $followModel->getFollowersIds($userId);
        $ids = array_unique(array_merge($amis, $followers));

        foreach($ids as $destId) {
            $notifModel->add($destId, $userId, 'new_post', "a publié un nouveau post !");
        }
    }

    public function getPostById($idPost) {
        $sql = "SELECT p.*, u.pseudo, u.photo_profil
                FROM post p
                JOIN utilisateur u ON p.id_utilisateur = u.id_utilisateur
                WHERE id_post = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$idPost]);
        return $stmt->fetch();
    }

    public function updatePost($idPost, $titre, $desc, $statut) {
        $sql = "UPDATE post SET titre = ?, description = ?, statut = ? WHERE id_post = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$titre, $desc, $statut, $idPost]);
    }

    public function toggleBlock($idPost, $status) {
        $sql = "UPDATE post SET is_blocked = ? WHERE id_post = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$status, $idPost]);
    }

    public function deletePost($idPost) {
        $sql = "DELETE FROM post WHERE id_post = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$idPost]);
    }
}