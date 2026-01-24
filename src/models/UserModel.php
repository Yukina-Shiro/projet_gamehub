<?php
// models/UserModel.php
class UserModel extends Model {

    public function createUser($pseudo, $email, $mdp, $nom, $prenom, $date_naissance) {
        $sql = "INSERT INTO Utilisateur (pseudo, email, mdp, nom, prenom, date_naissance) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$pseudo, $email, $mdp, $nom, $prenom, $date_naissance]);
    }

    public function getByEmail($email) {
        $sql = "SELECT * FROM Utilisateur WHERE email = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$email]);
        return $stmt->fetch();
    }
}

?>