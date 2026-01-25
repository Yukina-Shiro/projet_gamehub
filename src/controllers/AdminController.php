<?php
require_once 'controllers/Controller.php';
require_once 'models/UserModel.php';
require_once 'models/PostModel.php';

class AdminController extends Controller {

    public function __construct($pdo) {
        parent::__construct($pdo);
        // Sécurité stricte : vérification du rôle admin en session
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            $this->redirect('index.php');
        }
    }

    public function index() {
        // Filtrage par date d'inscription
        $date = $_GET['filter_date'] ?? null;
        $sql = "SELECT * FROM utilisateur";
        if ($date) {
            $sql .= " WHERE DATE(date_creation) = :d";
        }
        $stmt = $this->pdo->prepare($sql . " ORDER BY date_creation DESC");

        if ($date) {
            $stmt->execute([':d' => $date]);
        } else {
            $stmt->execute();
        }
        $users = $stmt->fetchAll();

        $postModel = new PostModel($this->pdo);
        $posts = $postModel->getAllPostsForAdmin();

        $this->render('admin/dashboard', [
            'users' => $users,
            'posts' => $posts,
            'filter_date' => $date
        ]);
    }

    // Envoi d'e-mails groupés aux membres sélectionnés
    public function sendMassMail() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['emails'])) {
            $subject = $_POST['subject'];
            $message = $_POST['message'];
            $recipients = $_POST['emails'];

            foreach ($recipients as $to) {
                mail($to, $subject, $message, "From: admin@gamehub.com");
            }
            $this->redirect('index.php?controller=Admin&action=index&msg=success');
        }
    }

    // Bloquer / Débloquer un contenu sans le supprimer
    public function blockPost() {
        if (isset($_GET['id'])) {
            $postModel = new PostModel($this->pdo);
            $post = $postModel->getPostById($_GET['id']);
            $newStatus = ($post['is_blocked'] == 1) ? 0 : 1;
            $postModel->toggleBlock($_GET['id'], $newStatus);
        }
        $this->redirect('index.php?controller=Admin&action=index');
    }

    // Supprimer un membre
    public function banUser() {
        if (isset($_GET['id']) && $_GET['id'] != $_SESSION['user_id']) {
            $sql = "DELETE FROM utilisateur WHERE id_utilisateur = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$_GET['id']]);
        }
        $this->redirect('index.php?controller=Admin&action=index');
    }

    // Supprimer une information (un post)
    public function deletePost() {
        if (isset($_GET['id'])) {
            $postModel = new PostModel($this->pdo);
            $postModel->deletePost($_GET['id']);
        }
        $this->redirect('index.php?controller=Admin&action=index');
    }
}