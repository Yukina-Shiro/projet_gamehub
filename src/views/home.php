<!DOCTYPE html>
<html>
<head>
    <title>GameHub - Accueil</title>
    <style>
        .post { border: 1px solid #ccc; padding: 10px; margin-bottom: 10px; }
        .meta { color: gray; font-size: 0.9em; }
    </style>
</head>
<body>
    <nav>
        Bonjour <?= $_SESSION['pseudo'] ?> | 
        <a href="index.php?controller=Auth&action=logout">Déconnexion</a>
    </nav>

    <div style="background: #f0f0f0; padding: 10px; margin: 20px 0;">
        <h3>Quoi de neuf ?</h3>
        <form method="post">
            <input type="text" name="titre" placeholder="Titre" required style="width:100%"><br>
            <textarea name="desc" placeholder="Message..." style="width:100%"></textarea><br>
            <select name="statut">
                <option value="public">Public</option>
                <option value="ami">Amis seulement</option>
            </select>
            <button type="submit">Publier</button>
        </form>
    </div>

    <h2>Fil d'actualité</h2>
    <?php foreach($posts as $post): ?>
        <div class="post">
            <h3><?= htmlspecialchars($post['titre']) ?></h3>
            <p><?= nl2br(htmlspecialchars($post['description'])) ?></p>
            <div class="meta">
                Par <?= htmlspecialchars($post['pseudo']) ?> | 
                Score : <?= $post['score'] ?? 0 ?> |
                <?= $post['date_creation'] ?>
            </div>
            </div>
    <?php endforeach; ?>
</body>
</html>