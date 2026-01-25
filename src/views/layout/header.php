<?php
// Récupération Avatar
$headerAvatar = 'https://via.placeholder.com/40'; 
if (isset($_SESSION['user_id'])) {
    if (!isset($pdo)) { global $pdo; }
    $stmtH = $pdo->prepare("SELECT photo_profil, role FROM utilisateur WHERE id_utilisateur = ?");
    $stmtH->execute([$_SESSION['user_id']]);
    $resH = $stmtH->fetch();
    if ($resH && !empty($resH['photo_profil'])) {
        $headerAvatar = 'uploads/' . $resH['photo_profil'];
    }
    // On s'assure que le rôle en session est à jour avec la base de données
    if ($resH) {
        $_SESSION['role'] = $resH['role'];
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GameHub</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script>
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme) { document.documentElement.setAttribute('data-theme', savedTheme); }
    </script>
</head>
<body>

<nav class="top-header">
    <div class="header-centered-container">
        <div class="header-content-left">
            <a href="index.php?controller=Home&action=index" class="logo-link">
                <i class="fa-solid fa-gamepad"></i> GameHub
            </a>
        </div>
        <div class="header-content-right">
            <?php if(isset($_SESSION['user_id'])): ?>
                <div class="user-menu-container">
                    <img src="<?= htmlspecialchars($headerAvatar) ?>" class="header-user-avatar" id="avatarBtn">
                    <div id="userDropdown" class="dropdown-menu">

                        <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                            <a href="index.php?controller=Admin&action=index" class="dropdown-item" style="color: #dc3545; font-weight: bold; border-bottom: 2px solid var(--border-color);">
                                <i class="fa-solid fa-shield-halved"></i> Administration
                            </a>
                        <?php endif; ?>

                        <a href="index.php?controller=User&action=settings" class="dropdown-item">
                            <i class="fa-solid fa-gear"></i> Paramètres
                        </a>
                        <a href="index.php?controller=Home&action=faq" class="dropdown-item">
                            <i class="fa-solid fa-circle-question"></i> FAQ
                        </a>
                        <a href="index.php?controller=Auth&action=logout" class="dropdown-item logout">
                            <i class="fa-solid fa-right-from-bracket"></i> Déconnexion
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <a href="index.php?controller=Auth&action=login" style="margin-right: 10px; color: #6f42c1; font-weight:bold;">Connexion</a>
                <a href="index.php?controller=Auth&action=register">Inscription</a>
            <?php endif; ?>
        </div>
    </div>
</nav>
<div style="height: 60px;"></div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const avatarBtn = document.getElementById('avatarBtn');
        const dropdown = document.getElementById('userDropdown');
        if (avatarBtn) {
            avatarBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                dropdown.classList.toggle('show');
            });
            document.addEventListener('click', function(e) {
                if (dropdown && !dropdown.contains(e.target) && e.target !== avatarBtn) {
                    dropdown.classList.remove('show');
                }
            });
        }
    });
</script>

<div class="container main-content">