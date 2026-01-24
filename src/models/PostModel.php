<?php
// models/PostModel.php
require_once 'models/Model.php';

class PostModel extends Model {

    // Nouvelle version : Affiche TOUT ce qui est Public + Les posts Amis
    public function getFeed($myId) {
        $sql = "
            SELECT p.*, u.pseudo, u.photo_profil,
            (SELECT IFNULL(SUM(vote), 0) FROM vote WHERE id_post = p.id_post) as score
            FROM post p
            JOIN utilisateur u ON p.id_utilisateur = u.id_utilisateur
            WHERE 
                -- 1. Tout ce qui est PUBLIC (tout le monde le voit)
                p.statut = 'public'
                
                OR 
                
                -- 2. C'est MON post (même privé)
                p.id_utilisateur = ? 
                
                OR 
                
                -- 3. C'est un post 'ami' d'un de mes AMIS
                (
                    p.statut = 'ami' 
                    AND 
                    p.id_utilisateur IN (
                        SELECT id_utilisateur1 FROM ami WHERE id_utilisateur2 = ?
                        UNION
                        SELECT id_utilisateur2 FROM ami WHERE id_utilisateur1 = ?
                    )
                )
            ORDER BY p.date_creation DESC LIMIT 50";
        
        $stmt = $this->pdo->prepare($sql);
        // On passe l'ID 3 fois maintenant
        $stmt->execute([$myId, $myId, $myId]);
        return $stmt->fetchAll();
    }

    // Ajout du paramètre $photo
    public function createPost($userId, $titre, $description, $visibilite, $photo = null) {
        $sql = "INSERT INTO post (id_utilisateur, titre, description, statut, photo) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$userId, $titre, $description, $visibilite, $photo]);
    }
}
?>