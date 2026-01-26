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

    // RENOMMÉ de index() à dashboard() pour correspondre au lien du header
    public function dashboard() {
        // Point 1 : Filtrage par date d'inscription
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

    // Promouvoir ou rétrograder un utilisateur
    public function toggleRole() {
        if (isset($_GET['id']) && $_GET['id'] != $_SESSION['user_id']) {
            $id = $_GET['id'];

            // On récupère le rôle actuel
            $stmt = $this->pdo->prepare("SELECT role FROM utilisateur WHERE id_utilisateur = ?");
            $stmt->execute([$id]);
            $user = $stmt->fetch();

            if ($user) {
                $newRole = ($user['role'] === 'admin') ? 'user' : 'admin';
                $update = $this->pdo->prepare("UPDATE utilisateur SET role = ? WHERE id_utilisateur = ?");
                $update->execute([$newRole, $id]);
            }
        }
        $this->redirect('index.php?controller=Admin&action=dashboard');
    }

    public function sendMassMail() {
        require_once 'models/EmailModel.php';
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['emails'])) {
            $subject = $_POST['subject'];
            $message = $_POST['message'];
            foreach ($_POST['emails'] as $to) {
                sendEmail($to, $subject, $message);
            }
            $this->redirect('index.php?controller=Admin&action=dashboard&msg=success');
        }
    }

    public function blockPost() {
        if (isset($_GET['id'])) {
            $postModel = new PostModel($this->pdo);
            $post = $postModel->getPostById($_GET['id']);
            $newStatus = ($post['is_blocked'] == 1) ? 0 : 1;
            $postModel->toggleBlock($_GET['id'], $newStatus);
        }
        $this->redirect('index.php?controller=Admin&action=dashboard');
    }

    public function banUser() {
        if (isset($_GET['id']) && $_GET['id'] != $_SESSION['user_id']) {
            $this->pdo->prepare("DELETE FROM utilisateur WHERE id_utilisateur = ?")->execute([$_GET['id']]);
        }
        $this->redirect('index.php?controller=Admin&action=dashboard');
    }

    public function deletePost() {
        if (isset($_GET['id'])) {
            $postModel = new PostModel($this->pdo);
            $postModel->deletePost($_GET['id']);
        }
        $this->redirect('index.php?controller=Admin&action=dashboard');
    }
}