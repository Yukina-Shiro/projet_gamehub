<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GameHub</title>
    <link rel="stylesheet" href="style.css">
    </head>
<body>

<nav>
    <strong>GAMEHUB</strong>
    <?php if(isset($_SESSION['user_id'])): ?>
        <a href="index.php?controller=Home&action=index" style="margin-left: 20px;">Mur</a>
        <a href="index.php?controller=User&action=profile">Mon Profil</a>

        <?php
            require_once 'models/NotificationModel.php';
            global $pdo; // Le fix qui fait marcher le site
            
            $nModel = new NotificationModel($pdo);
            $nbUnread = $nModel->countUnread($_SESSION['user_id']);
        ?>
        
        <a href="index.php?controller=Notification&action=index" class="notif-btn">
            🔔
            <?php if($nbUnread > 0): ?>
                <span class="badge"><?= $nbUnread ?></span>
            <?php endif; ?>
        </a>

        <a href="index.php?controller=Auth&action=logout" style="float:right; color:#ff9999;">Déconnexion</a>
    <?php else: ?>
        <a href="index.php?controller=Auth&action=login" style="margin-left: 20px;">Connexion</a>
        <a href="index.php?controller=Auth&action=register">Inscription</a>
    <?php endif; ?>
</nav>

<div class="container">