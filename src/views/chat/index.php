<?php include 'views/layout/header.php'; ?>

<div class="container">
    <h2>💬 Mes Messages</h2>
    
    <?php if(empty($conversations)): ?>
        <p>Aucune conversation. Allez sur le profil d'un ami pour discuter !</p>
    <?php else: ?>
        <div style="display: flex; flex-direction: column;">
            <?php foreach($conversations as $c): ?>
                <a href="index.php?controller=Chat&action=conversation&id=<?= $c['id_utilisateur'] ?>" class="generic-item">
                    <img src="<?= !empty($c['photo_profil']) ? 'uploads/'.$c['photo_profil'] : 'https://via.placeholder.com/50' ?>">
                    <div>
                        <h3 style="margin: 0; font-size: 1.1em;"><?= htmlspecialchars($c['pseudo']) ?></h3>
                        <span style="color: var(--text-secondary); font-size: 0.9em;">Ouvrir la discussion ➤</span>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include 'views/layout/footer.php'; ?>