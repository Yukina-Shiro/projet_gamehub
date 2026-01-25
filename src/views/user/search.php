<?php include 'views/layout/header.php'; ?>

<div class="container" style="max-width: 600px;">
    <h2>Explorer</h2>
    
    <form method="get" action="index.php" style="display: flex; gap: 10px; margin-bottom: 20px;">
        <input type="hidden" name="controller" value="User">
        <input type="hidden" name="action" value="search">
        <input type="text" name="q" placeholder="Rechercher..." value="<?= htmlspecialchars($query) ?>" style="border-radius:20px;">
        <button type="submit" style="width:auto; border-radius:50%; width:45px; height:45px; padding:0;"><i class="fa-solid fa-magnifying-glass"></i></button>
    </form>

    <?php if (!empty($query)): ?>
        <h4>Résultats pour "<?= htmlspecialchars($query) ?>"</h4>
        <?php if (empty($results)): ?>
            <p style="color:var(--text-secondary);">Aucun joueur trouvé.</p>
        <?php else: ?>
            <div style="display: flex; flex-direction: column;">
                <?php foreach($results as $user): ?>
                    <a href="index.php?controller=User&action=profile&id=<?= $user['id_utilisateur'] ?>" class="generic-item">
                        <img src="<?= !empty($user['photo_profil']) ? 'uploads/'.$user['photo_profil'] : 'https://via.placeholder.com/50' ?>">
                        <div>
                            <h3 style="margin: 0; font-size: 1rem;"><?= htmlspecialchars($user['pseudo']) ?></h3>
                            <small style="color: var(--text-secondary);">Voir le profil</small>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?php include 'views/layout/footer.php'; ?>