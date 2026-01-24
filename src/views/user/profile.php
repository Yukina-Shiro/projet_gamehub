<?php include 'views/layout/header.php'; ?>

<div class="container" style="text-align: center;">
    <h2>Profil Joueur</h2>

    <div style="margin: 20px 0;">
        <?php if (!empty($user['photo_profil'])): ?>
            <img src="uploads/<?= htmlspecialchars($user['photo_profil']) ?>" 
                 alt="Photo de profil" 
                 style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover; border: 3px solid #28a745;">
        <?php else: ?>
            <div style="width: 150px; height: 150px; background: #ddd; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 50px; color: #666;">
                <?= strtoupper(substr($user['pseudo'], 0, 1)) ?>
            </div>
        <?php endif; ?>
    </div>

    <h1><?= htmlspecialchars($user['pseudo']) ?></h1>
    
    <p style="color: gray; font-style: italic;">
        <?= !empty($user['bio']) ? nl2br(htmlspecialchars($user['bio'])) : "Aucune bio renseignée." ?>
    </p>

    <div style="text-align: left; margin-top: 30px; background: #f9f9f9; padding: 20px; border-radius: 8px;">
        <p><strong>Nom :</strong> <?= htmlspecialchars($user['nom']) ?></p>
        <p><strong>Prénom :</strong> <?= htmlspecialchars($user['prenom']) ?></p>
        <p><strong>Email :</strong> <?= htmlspecialchars($user['email']) ?></p>
        <p><strong>Membre depuis :</strong> <?= $user['date_creation'] ?></p>
    </div>

    <?php if ($user['id_utilisateur'] == $_SESSION['user_id']): ?>
        <div style="margin-top: 20px;">
            <a href="index.php?controller=User&action=edit">
                <button style="width: auto; background-color: #007bff;">Modifier mon profil</button>
            </a>
        </div>
    <?php endif; ?>
    
    <div style="margin-top: 10px;">
        <a href="index.php?controller=Home&action=index" style="color: #666; text-decoration: underline;">Retour au fil d'actualité</a>
    </div>
</div>

<?php include 'views/layout/footer.php'; ?>