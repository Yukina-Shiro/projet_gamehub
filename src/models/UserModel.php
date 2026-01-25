<?php
// models/UserModel.php
require_once 'models/Model.php';

class UserModel extends Model {

    // 1. Inscription
    public function createUser($pseudo, $email, $tel, $mdp, $nom, $prenom, $date_naissance) {
        // Protection : Hachage du mot de passe
        $mdp_hache = password_hash($mdp, PASSWORD_DEFAULT);

        $sql = "INSERT INTO Utilisateur (pseudo, email, tel,mdp, nom, prenom, date_naissance, date_creation) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
        $stmt = $this->pdo->prepare($sql);
        // On utilise le mot de passe haché ici
        return $stmt->execute([$pseudo, $email, $tel, $mdp_hache, $nom, $prenom, $date_naissance]);
    }

    // 2. Connexion
    public function getByEmail($email) {
        $sql = "SELECT * FROM Utilisateur WHERE email = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    // --- AJOUTS POUR LE PROFIL ---

    // 3. Récupérer un utilisateur par son ID (Pour afficher le profil)
    public function getById($id) {
        $sql = "SELECT * FROM Utilisateur WHERE id_utilisateur = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // 4. Mettre à jour le profil (Avec ou sans photo)
    public function updateUser($id, $pseudo, $bio, $nom, $prenom, $photo = null) {
        if ($photo) {
            // Si une nouvelle photo est envoyée, on met à jour tout y compris la photo
            $sql = "UPDATE Utilisateur SET pseudo = ?, bio = ?, nom = ?, prenom = ?, photo_profil = ? WHERE id_utilisateur = ?";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$pseudo, $bio, $nom, $prenom, $photo, $id]);
        } else {
            // Sinon, on ne touche pas à la colonne photo_profil
            $sql = "UPDATE Utilisateur SET pseudo = ?, bio = ?, nom = ?, prenom = ? WHERE id_utilisateur = ?";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$pseudo, $bio, $nom, $prenom, $id]);
        }
    }

    // Recherche d'utilisateurs par pseudo (partiel)
    public function searchUsers($query) {
        $sql = "SELECT id_utilisateur, pseudo, photo_profil, bio 
                FROM Utilisateur 
                WHERE pseudo LIKE ? 
                LIMIT 20";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['%' . $query . '%']);
        return $stmt->fetchAll();
    }
}

?>