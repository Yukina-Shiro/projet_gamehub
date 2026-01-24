<?php
// models/UserModel.php
class UserModel extends Model {

    public function createUser($pseudo, $email, $mdp, $nom, $prenom, $date_naissance) {
        // Protection : Hachage du mot de passe avec l'algorithme par défaut (BCRYPT)
        $mdp_hache = password_hash($mdp, PASSWORD_DEFAULT);

        $sql = "INSERT INTO utilisateur (pseudo, email, mdp, nom, prenom, date_naissance) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        // On utilise le mot de passe haché ici
        return $stmt->execute([$pseudo, $email, $mdp_hache, $nom, $prenom, $date_naissance]);
    }

    public function getByEmail($email) {
        $sql = "SELECT * FROM utilisateur WHERE email = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$email]);
        return $stmt->fetch();
    }
}

?>