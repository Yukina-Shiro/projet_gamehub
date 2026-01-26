<?php
// Récupération Avatar
$headerAvatar = 'https://via.placeholder.com/40'; 
if (isset($_SESSION['user_id'])) {
    if (!isset($pdo)) { global $pdo; }
    $stmtH = $pdo->prepare("SELECT photo_profil FROM utilisateur WHERE id_utilisateur = ?");
    $stmtH->execute([$_SESSION['user_id']]);
    $resH = $stmtH->fetch();
    if ($resH && !empty($resH['photo_profil']) && file_exists('uploads/' . $resH['photo_profil'])) {
        $headerAvatar = 'uploads/' . $resH['photo_profil'];
    }
}

// Détection Page d'accueil
$isHome = (isset($_GET['controller']) && $_GET['controller'] === 'Home' && (!isset($_GET['action']) || $_GET['action'] === 'index'));
if(empty($_GET)) $isHome = true;
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
                <i class="fa-solid fa-gamepad"></i> <span class="logo-text">GameHub</span>
            </a>
        </div>

        <div class="header-content-right">
            <?php if(isset($_SESSION['user_id'])): ?>
                <div class="user-menu-container">
                    <img src="<?= htmlspecialchars($headerAvatar) ?>" class="header-user-avatar" id="avatarBtn">
                    <div id="userDropdown" class="dropdown-menu">
                        <a href="index.php?controller=User&action=settings" class="dropdown-item"><i class="fa-solid fa-gear"></i> Paramètres</a>
                        <a href="index.php?controller=Home&action=faq" class="dropdown-item"><i class="fa-solid fa-circle-question"></i> FAQ</a>
                        <a href="index.php?controller=Auth&action=logout" class="dropdown-item logout"><i class="fa-solid fa-right-from-bracket"></i> Déconnexion</a>
                    </div>
                </div>
            <?php else: ?>
                <a href="index.php?controller=Auth&action=login" style="margin-right: 10px; color: #6f42c1; font-weight:bold;">Connexion</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<?php if($isHome && isset($_SESSION['user_id'])): ?>
    <div class="floating-tabs-container">
        <button id="tab-global" class="floating-tab active" onclick="switchFeed('global')">
            Global
        </button>
        
        <button id="tab-perso" class="floating-tab" onclick="switchFeed('perso')">
            Mon Fil
        </button>
    </div>
<?php endif; ?>

<div style="height: 120px;"></div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const avatarBtn = document.getElementById('avatarBtn');
        const dropdown = document.getElementById('userDropdown');
        if (avatarBtn) {
            avatarBtn.addEventListener('click', function(e) { e.stopPropagation(); dropdown.classList.toggle('show'); });
            document.addEventListener('click', function(e) { if (dropdown && !dropdown.contains(e.target) && e.target !== avatarBtn) { dropdown.classList.remove('show'); } });
        }
    });
</script>

<div class="container main-content">