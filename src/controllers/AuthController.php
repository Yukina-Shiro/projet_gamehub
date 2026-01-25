<?php
require_once 'controllers/Controller.php';
require_once 'models/UserModel.php';

class AuthController extends Controller {

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $mdp = $_POST['mdp'] ?? '';

            $model = new UserModel($this->pdo);
            $user = $model->getByEmail($email);

            // password_verify compare le mot de passe saisi avec le hachage en BDD
            if ($user && password_verify($mdp, $user['mdp'])) {
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
            // Nettoyage basique des données
            $pseudo = trim($_POST['pseudo']);
            $email = trim($_POST['email']);
            $mdp = $_POST['mdp'];
            $nom = trim($_POST['nom']);
            $prenom = trim($_POST['prenom']);
            $telephone = trim($_POST['telephone']);
            $date = $_POST['date_naissance'];

            if (empty($pseudo) || empty($email) || empty($mdp) || empty($nom) || empty($prenom) || empty($telephone)) {
                $this->render('auth/register', ['error' => 'Veuillez remplir tous les champs obligatoires.']);
                return;
            }

            $model = new UserModel($this->pdo);
            $success = $model->createUser($pseudo, $email, $telephone, $mdp, $nom, $prenom, $date);

            if ($success) {
                $this->redirect('index.php?controller=Auth&action=login');
            } else {
                $this->render('auth/register', ['error' => 'Erreur : Email ou Pseudo déjà utilisé.']);
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