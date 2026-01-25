<?php
require_once 'controllers/Controller.php';
require_once 'models/NotificationModel.php';

class NotificationController extends Controller {

    public function index() {
        if (!isset($_SESSION['user_id'])) $this->redirect('index.php');

        $notifModel = new NotificationModel($this->pdo);
        
        // On récupère les notifs
        $notifs = $notifModel->getMyNotifications($_SESSION['user_id']);

        // On affiche la page
        $this->render('notification/index', ['notifs' => $notifs]);
        
        // Optionnel : On pourrait tout marquer comme "lu" dès l'ouverture, 
        // mais tu as demandé de pouvoir voir si on les a vus ou pas. 
        // Donc on les laisse telles quelles.
    }

    public function delete() {
        if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) $this->redirect('index.php');

        $notifModel = new NotificationModel($this->pdo);
        $notifModel->delete($_GET['id']);

        // On reste sur la page notif
        header("Location: index.php?controller=Notification&action=index");
        exit;
    }

    public function markRead() {
        if (isset($_GET['id'])) {
            $notifModel = new NotificationModel($this->pdo);
            $notifModel->markAsRead(notifId: $_GET['id']);
        }
        header("Location: index.php?controller=Notification&action=index");
        exit;
    }

    public function markAllRead() {
        if (!isset($_SESSION['user_id'])) $this->redirect('index.php');

        $notifModel = new NotificationModel($this->pdo);
        
        // Récupérer toutes les notifications non lues
        $notifs = $notifModel->getUnreadNotifications($_SESSION['user_id']);
        
        // Marquer chacune comme lue
        foreach ($notifs as $notif) {
            $notifModel->markAsRead($notif['id_notif']);
        }

        header("Location: index.php?controller=Notification&action=index");
        exit;
    }
}

?>