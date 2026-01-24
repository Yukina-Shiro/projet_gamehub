<?php include 'views/layout/header.php'; ?>

<div class="container">
    <h2>Modifier le post</h2>
    <form method="post">
        <label>Titre</label>
        <input type="text" name="titre" value="<?= htmlspecialchars($post['titre']) ?>" required>
        
        <label>Description</label>
        <textarea name="desc" rows="5"><?= htmlspecialchars($post['description']) ?></textarea>
        
        <label>Visibilité</label>
        <select name="statut">
            <option value="public" <?= $post['statut']=='public'?'selected':'' ?>>Public</option>
            <option value="ami" <?= $post['statut']=='ami'?'selected':'' ?>>Amis seulement</option>
        </select>
        
        <button type="submit" style="margin-top: 15px;">Enregistrer les modifications</button>
    </form>
    
    <div style="margin-top: 10px; text-align: center;">
        <a href="index.php?controller=User&action=profile">Annuler</a>
    </div>
</div>

<?php include 'views/layout/footer.php'; ?>