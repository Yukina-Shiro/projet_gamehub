<?php
// models/PostModel.php
class PostModel extends Model {

    // Récupère les posts (Amis + Public Follows + Mes posts)
    public function getFeed($myId) {
        $sql = "
            SELECT p.*, u.pseudo, u.photo_profil,
            (SELECT SUM(vote) FROM Vote WHERE id_post = p.id_post) as score
            FROM Post p
            JOIN Utilisateur u ON p.id_utilisateur = u.id_utilisateur
            WHERE 
                p.id_utilisateur = ? 
                OR p.id_utilisateur IN (
                    SELECT sender_id FROM Amis WHERE receiver_id = ? AND statut = 'valide'
                    UNION
                    SELECT receiver_id FROM Amis WHERE sender_id = ? AND statut = 'valide'
                )
                OR (
                    p.statut = 'public' 
                    AND p.id_utilisateur IN (SELECT id_suivi FROM Follow WHERE id_suiveur = ?)
                )
            ORDER BY p.date_creation DESC LIMIT 20";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$myId, $myId, $myId, $myId]);
        return $stmt->fetchAll();
    }

    public function createPost($userId, $titre, $description, $visibilite) {
        $sql = "INSERT INTO Post (id_utilisateur, titre, description, statut) VALUES (?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$userId, $titre, $description, $visibilite]);
    }
}

?>