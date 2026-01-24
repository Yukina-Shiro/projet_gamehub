<?php
// controllers/HomeController.php
require_once 'controllers/Controller.php';
require_once 'models/PostModel.php';

class HomeController extends Controller {

    public function index() {
        // 1. Vérification de sécurité (Est-on connecté ?)
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('index.php?controller=Auth&action=login');
        }

        $postModel = new PostModel($this->pdo);

        // 2. Traitement du formulaire de publication (POST)
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['titre'])) {
            
            $titre = $_POST['titre'];
            $desc = $_POST['desc'];
            $statut = $_POST['statut'];
            $photoName = null; // Par défaut, pas d'image

            // --- GESTION UPLOAD ROBUSTE (Même logique que le profil) ---
            if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
                
                $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                $filename = $_FILES['photo']['name'];
                $filetype = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

                if (in_array($filetype, $allowed)) {
                    // A. Chemin ABSOLU (Racine du projet + uploads)
                    $targetDir = dirname(__DIR__) . '/uploads/';

                    // B. Création du dossier si inexistant
                    if (!is_dir($targetDir)) {
                        mkdir($targetDir, 0755, true);
                    }

                    // C. Nom unique pour éviter d'écraser les fichiers
                    // Ex: post_4_17068999_image.jpg
                    $newName = "post_" . $_SESSION['user_id'] . "_" . time() . "." . $filetype;
                    $targetFile = $targetDir . $newName;

                    // D. Déplacement final
                    if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetFile)) {
                        $photoName = $newName;
                    } else {
                        die("Erreur critique : Impossible d'écrire dans le dossier $targetDir");
                    }
                } else {
                    die("Erreur : Format de fichier non supporté.");
                }
            }

            // 3. Envoi des données au Modèle
            $postModel->createPost($_SESSION['user_id'], $titre, $desc, $statut, $photoName);
            
            // 4. Redirection pour éviter de reposter en actualisant la page
            $this->redirect('index.php?controller=Home&action=index');
        }

        // 5. Affichage du Mur
        $posts = $postModel->getFeed($_SESSION['user_id']);
        
        $this->render('home', ['posts' => $posts]);
    }
}

?>