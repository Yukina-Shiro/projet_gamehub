<?php include 'views/layout/header.php'; ?>

<h1>Fil d'actualité Global</h1>

<div class="container" style="margin-bottom: 20px; border-left: 5px solid #28a745;">
    <h3>Exprimez-vous</h3>
    <form method="post" enctype="multipart/form-data">
        <input type="text" name="titre" placeholder="Titre du post" required><br>
        <textarea name="desc" placeholder="Quoi de neuf ?" rows="3"></textarea><br>
        
        <label>Ajouter une image :</label>
        <input type="file" name="photo" accept="image/*"><br>

        <label>Visibilité :</label>
        <select name="statut">
            <option value="public">Public (Tout le monde)</option>
            <option value="ami">Amis seulement</option>
        </select>
        
        <button type="submit" style="margin-top:10px;">Publier</button>
    </form>
</div>

<div class="feed">
    <?php if (empty($posts)): ?>
        <p>Aucun post pour le moment. Soyez le premier !</p>
    <?php else: ?>
        <?php foreach($posts as $post): ?>
            <div class="container" style="margin-bottom: 15px;">
                
                <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #eee; padding-bottom: 5px;">
                    
                    <a href="index.php?controller=User&action=profile&id=<?= $post['id_utilisateur'] ?>" 
                       style="text-decoration: none; color: inherit; display: flex; align-items: center;">
                        
                        <?php if (!empty($post['photo_profil'])): ?>
                            <img src="uploads/<?= htmlspecialchars($post['photo_profil']) ?>" 
                                 alt="Avatar" 
                                 style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; margin-right: 10px; border: 1px solid #ddd;">
                        <?php else: ?>
                            <div style="width: 40px; height: 40px; background: #ccc; border-radius: 50%; margin-right: 10px; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">
                                <?= strtoupper(substr($post['pseudo'], 0, 1)) ?>
                            </div>
                        <?php endif; ?>

                        <strong><?= htmlspecialchars($post['pseudo']) ?></strong>
                    </a>

                    <span style="font-size: 0.8em; color: gray;"><?= $post['date_creation'] ?></span>
                </div>

                <h3 style="margin: 10px 0;"><?= htmlspecialchars($post['titre']) ?></h3>
                <p><?= nl2br(htmlspecialchars($post['description'])) ?></p>

                <?php if (!empty($post['photo'])): ?>
                    <div style="text-align: center; margin: 10px 0;">
                        <img src="uploads/<?= htmlspecialchars($post['photo']) ?>" alt="Post image" style="max-width: 100%; max-height: 400px; border-radius: 5px;">
                    </div>
                <?php endif; ?>

                <div style="border-top: 1px solid #eee; padding-top: 5px; font-size: 0.9em; color: #555;">
                    Score : <?= $post['score'] ?> | 
                    <span style="background: #eee; padding: 2px 6px; border-radius: 4px;"><?= $post['statut'] ?></span>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php include 'views/layout/footer.php'; ?>