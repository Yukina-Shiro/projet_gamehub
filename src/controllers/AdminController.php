<?php
require_once 'controllers/Controller.php';
require_once 'models/UserModel.php';
require_once 'models/PostModel.php';

class AdminController extends Controller {

    public function __construct($pdo) {
        parent::__construct($pdo);
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            $this->redirect('index.php');
        }
    }

    public function index() {
        // Filtrage par date (Cahier des charges point 1)
        $date = $_GET['filter_date'] ?? null;
        $where = "";
        $params = [];

        if ($date) {
            $where = " WHERE DATE(date_creation) = ?";
            $params[] = $date;
        }

        $stmtUsers = $this->pdo->prepare("SELECT * FROM utilisateur" . $where . " ORDER BY date_creation DESC");
        $stmtUsers->execute($params);
        $users = $stmtUsers->fetchAll();

        $stmtPosts = $this->pdo->query("SELECT p.*, u.pseudo FROM post p JOIN utilisateur u ON p.id_utilisateur = u.id_utilisateur ORDER BY date_creation DESC");
        $posts = $stmtPosts->fetchAll();

        $this->render('admin/dashboard', ['users' => $users, 'posts' => $posts, 'filter_date' => $date]);
    }

    public function deletePost() {
        if (isset($_GET['id'])) {
            $postModel = new PostModel($this->pdo);
            $postModel->deletePost($_GET['id']);
        }
        $this->redirect('index.php?controller=Admin&action=index');
    }

    public function banUser() {
        if (isset($_GET['id']) && $_GET['id'] != $_SESSION['user_id']) {
            $sql = "DELETE FROM utilisateur WHERE id_utilisateur = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$_GET['id']]);
        }
        $this->redirect('index.php?controller=Admin&action=index');
    }

    // Envoi de mail (Cahier des charges point 3)
    public function sendMail() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $to = $_POST['email'];
            $subject = $_POST['subject'];
            $message = $_POST['message'];
            $headers = "From: admin@gamehub.com";

            if (mail($to, $subject, $message, $headers)) {
                $this->redirect('index.php?controller=Admin&action=index&success=mail_sent');
            } else {
                die("Erreur lors de l'envoi du mail. Vérifiez la configuration de votre serveur (MAMP/XAMPP).");
            }
        }
    }
}