<?php
require_once 'controllers/Controller.php';
require_once 'models/UserModel.php';

class AuthController extends Controller {

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $mdp = $_POST['mdp'];
            
            $model = new UserModel($this->pdo);
            $user = $model->getByEmail($email);

            // Vérification simple (mettre password_verify si haché)
            if ($user && $mdp === $user['mdp']) {
                $_SESSION['user_id'] = $user['id_utilisateur'];
                $_SESSION['pseudo'] = $user['pseudo'];
                $_SESSION['role'] = $user['role'];
                $this->redirect('index.php?controller=Home&action=index');
            } else {
                $this->render('auth/login', ['error' => 'Identifiants incorrects']);
            }
        } else {
            $this->render('auth/login');
        }
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupération des données du formulaire
            $pseudo = $_POST['pseudo'];
            $email = $_POST['email'];
            $mdp = $_POST['mdp'];
            $nom = $_POST['nom'];
            $prenom = $_POST['prenom'];
            $date = $_POST['date_naissance'];

            $model = new UserModel($this->pdo);
            
            // Création
            // (Assure-toi que la méthode createUser dans UserModel accepte bien ces 6 arguments)
            $success = $model->createUser($pseudo, $email, $mdp, $nom, $prenom, $date);

            if ($success) {
                // Succès -> Redirection vers Login
                $this->redirect('index.php?controller=Auth&action=login');
            } else {
                $this->render('auth/register', ['error' => 'Erreur lors de l\'inscription (Email ou Pseudo déjà pris ?)']);
            }
        } else {
            $this->render('auth/register');
        }
    }

    public function logout() {
        session_destroy();
        $this->redirect('index.php?controller=Auth&action=login');
    }
}
?>