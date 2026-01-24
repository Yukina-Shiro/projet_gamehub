<?php
// controllers/AuthController.php
require_once 'models/UserModel.php';

class AuthController extends Controller {

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $mdp = $_POST['mdp'];
            
            $model = new UserModel($this->pdo);
            $user = $model->getByEmail($email);

            // Vérification mot de passe (Hash ou clair pour test)
            if ($user && ($mdp === $user['mdp'] || password_verify($mdp, $user['mdp']))) {
                $_SESSION['user_id'] = $user['id_utilisateur'];
                $_SESSION['pseudo'] = $user['pseudo'];
                $this->redirect('index.php?controller=Home&action=index');
            } else {
                $error = "Identifiants incorrects";
                $this->render('auth/login', ['error' => $error]);
            }
        } else {
            $this->render('auth/login');
        }
    }

    public function logout() {
        session_destroy();
        $this->redirect('index.php?controller=Auth&action=login');
    }
}

?>