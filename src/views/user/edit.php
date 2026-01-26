<?php include 'views/layout/header.php'; ?>

<div class="container">
    <h2>Modifier mon profil</h2>
    
    <form method="post" enctype="multipart/form-data">
        
        <label>Photo de profil</label>
        <div style="margin-bottom: 10px;">
            <?php if (!empty($user['photo_profil'])): ?>
                <img src="uploads/<?= htmlspecialchars($user['photo_profil']) ?>" width="50" style="border-radius: 50%; vertical-align: middle;">
                <span style="font-size: 0.8em; color: green;">Photo actuelle</span>
            <?php endif; ?>
        </div>
        <input type="file" name="photo_profil" accept="image/*">

        <label>Pseudo</label>
        <input type="text" name="pseudo" value="<?= htmlspecialchars($user['pseudo']) ?>" required>

        <label>Bio</label>
        <textarea name="bio" rows="4"><?= htmlspecialchars($user['bio']) ?></textarea>

        <label>Nom</label>
        <input type="text" name="nom" value="<?= htmlspecialchars($user['nom']) ?>" required>

        <label>Prénom</label>
        <input type="text" name="prenom" value="<?= htmlspecialchars($user['prenom']) ?>" required>

        <button type="submit">Enregistrer les modifications</button>
    </form>

    <form method="post" enctype="multipart/form-data">
        <h3>Changer de mot de passe</h3>
        <input type="hidden" name="action_type" value="update_password">

        <label>Nouveau mot de passe</label>
        <input type="password" name="mdp" required>

        <label>Réecrire nouveau mot de passe</label>
        <input type="password" name="mdp_confirm" required>

        <button type="submit">Enregistrer les modifications</button>
    </form>
    
    <div style="margin-top: 15px; text-align: center;">
        <a href="index.php?controller=User&action=profile" style="color: #666;">Annuler</a>
    </div>
</div>

<?php include 'views/layout/footer.php'; ?>