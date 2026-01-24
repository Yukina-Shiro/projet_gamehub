<?php
// models/PostModel.php
require_once 'models/Model.php';

class PostModel extends Model {

    // Récupère les posts (Amis + Public Follows + Mes posts)
    public function getFeed($myId) {
        $sql = "
            SELECT p.*, u.pseudo, u.photo_profil,
            -- Utilisation de vote (minuscule) et IFNULL pour éviter les bugs si 0 votes
            (SELECT IFNULL(SUM(vote), 0) FROM vote WHERE id_post = p.id_post) as score
            FROM post p
            JOIN utilisateur u ON p.id_utilisateur = u.id_utilisateur
            WHERE 
                -- 1. Mes propres posts
                p.id_utilisateur = ? 
                
                OR 
                
                -- 2. Les posts de mes AMIS (Table ami en minuscule)
                p.id_utilisateur IN (
                    SELECT id_utilisateur1 FROM ami WHERE id_utilisateur2 = ?
                    UNION
                    SELECT id_utilisateur2 FROM ami WHERE id_utilisateur1 = ?
                )
                
                OR 
                
                -- 3. Les posts des gens que je SUIS (Table relation en minuscule)
                (
                    p.statut = 'public' 
                    AND p.id_utilisateur IN (SELECT suivi FROM relation WHERE suiveur = ?)
                )
            ORDER BY p.date_creation DESC LIMIT 20";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$myId, $myId, $myId, $myId]);
        return $stmt->fetchAll();
    }

    public function createPost($userId, $titre, $description, $visibilite) {
        // Table post en minuscule
        $sql = "INSERT INTO post (id_utilisateur, titre, description, statut) VALUES (?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$userId, $titre, $description, $visibilite]);
    }
}

?>