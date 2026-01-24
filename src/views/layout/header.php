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
        <a href="index.php?controller=Auth&action=logout" style="float:right;">Déconnexion</a>
    <?php else: ?>
        <a href="index.php?controller=Auth&action=login" style="margin-left: 20px;">Connexion</a>
        <a href="index.php?controller=Auth&action=register">Inscription</a>
    <?php endif; ?>
</nav>

<div class="container">