<?php
// controllers/UserController.php
require_once 'controllers/Controller.php';
require_once 'models/UserModel.php';

class UserController extends Controller {

    // Afficher un profil (le mien OU celui d'un autre)
    public function profile() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('index.php?controller=Auth&action=login');
        }

        $userModel = new UserModel($this->pdo);

        // LOGIQUE : Si un ID est dans l'URL (ex: &id=12), on prend celui-là.
        // Sinon, on prend l'ID de la session (moi-même).
        $idToDisplay = isset($_GET['id']) ? $_GET['id'] : $_SESSION['user_id'];

        $user = $userModel->getById($idToDisplay);

        if (!$user) {
            die("Utilisateur introuvable.");
        }

        $this->render('user/profile', ['user' => $user]);
    }

    // Modifier mon profil (Reste inchangé, mais je te le remets pour être complet)
    public function edit() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('index.php?controller=Auth&action=login');
        }

        $userModel = new UserModel($this->pdo);
        $user = $userModel->getById($_SESSION['user_id']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $pseudo = $_POST['pseudo'];
            $nom = $_POST['nom'];
            $prenom = $_POST['prenom'];
            $bio = $_POST['bio'];
            $photoName = null;

            if (isset($_FILES['photo_profil']) && $_FILES['photo_profil']['error'] === 0) {
                $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                $filename = $_FILES['photo_profil']['name'];
                $filetype = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

                if (in_array($filetype, $allowed)) {
                    $targetDir = dirname(__DIR__) . '/uploads/';
                    if (!is_dir($targetDir)) mkdir($targetDir, 0755, true);
                    
                    $newName = "profil_" . $_SESSION['user_id'] . "_" . time() . "." . $filetype;
                    if (move_uploaded_file($_FILES['photo_profil']['tmp_name'], $targetDir . $newName)) {
                        $photoName = $newName;
                    }
                }
            }

            $userModel->updateUser($_SESSION['user_id'], $pseudo, $bio, $nom, $prenom, $photoName);
            $_SESSION['pseudo'] = $pseudo;
            $this->redirect('index.php?controller=User&action=profile');
        }

        $this->render('user/edit', ['user' => $user]);
    }
}

?>