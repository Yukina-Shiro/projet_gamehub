<?php 
include 'views/layout/header.php'; 

// --- LOGIQUE DE RETOUR INTELLIGENT ---
// Par défaut, on retourne à l'accueil
$backLink = 'index.php?controller=Home&action=index';

// Si on a un referer (page précédente) et qu'il vient de NOTRE site, on l'utilise
if (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'index.php') !== false) {
    // Petit fix : si le referer est la page login ou register, on évite, on retourne à l'accueil
    if (strpos($_SERVER['HTTP_REFERER'], 'action=login') === false && strpos($_SERVER['HTTP_REFERER'], 'action=register') === false) {
        $backLink = $_SERVER['HTTP_REFERER'];
    }
}
?>

<div class="container">
    <a href="<?= htmlspecialchars($backLink) ?>" style="color:gray; text-decoration: none; font-size: 1.1em; display: flex; align-items: center; margin-bottom: 20px;">
        ⬅ Retour
    </a>

    <div style="background: white; border: 1px solid #e1e8ed; border-radius: 12px; padding: 20px;">
        <div style="display: flex; align-items: center; margin-bottom: 15px;">
            <img src="<?= !empty($post['photo_profil']) ? 'uploads/'.$post['photo_profil'] : 'https://via.placeholder.com/40' ?>" 
                 style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover; margin-right: 15px;">
            <div>
                <h3 style="margin: 0;"><?= htmlspecialchars($post['pseudo']) ?></h3>
                <span style="color: gray; font-size: 0.9em;"><?= $post['date_creation'] ?></span>
            </div>
        </div>
        
        <h2 style="margin: 0 0 10px 0;"><?= htmlspecialchars($post['titre']) ?></h2>
        <p style="font-size: 1.2em; line-height: 1.6; margin-bottom: 20px;"><?= nl2br(htmlspecialchars($post['description'])) ?></p>

        <?php if (!empty($post['photo'])): ?>
            <img src="uploads/<?= htmlspecialchars($post['photo']) ?>" style="max-width:100%; border-radius: 8px; margin-bottom: 20px;">
        <?php endif; ?>

        <div style="display: flex; gap: 15px;">
             <a href="index.php?controller=Post&action=vote&id=<?= $post['id_post'] ?>&value=1" style="text-decoration:none;">
                <button style="width:auto; background: <?= $myVote == 1 ? '#28a745' : '#ccc' ?>; border-radius: 20px;">
                    👍 <?= $stats['nb_likes'] ?>
                </button>
            </a>
            <a href="index.php?controller=Post&action=vote&id=<?= $post['id_post'] ?>&value=-1" style="text-decoration:none;">
                <button style="width:auto; background: <?= $myVote == -1 ? '#dc3545' : '#ccc' ?>; border-radius: 20px;">
                    👎 <?= $stats['nb_dislikes'] ?>
                </button>
            </a>
        </div>
    </div>

    <div style="margin-top: 30px;">
        <h3>Commentaires (<?= $stats['nb_comments'] ?>)</h3>

        <div style="margin-bottom: 15px; font-size: 0.9em;">
            Trier par : 
            <a href="index.php?controller=Post&action=show&id=<?= $post['id_post'] ?>&sort=pertinence" style="font-weight: <?= $currentSort=='pertinence'?'bold':'normal' ?>">Pertinence</a> | 
            <a href="index.php?controller=Post&action=show&id=<?= $post['id_post'] ?>&sort=recent" style="font-weight: <?= $currentSort=='recent'?'bold':'normal' ?>">Plus récents</a> | 
            <a href="index.php?controller=Post&action=show&id=<?= $post['id_post'] ?>&sort=old" style="font-weight: <?= $currentSort=='old'?'bold':'normal' ?>">Plus anciens</a>
        </div>

        <form method="post" style="margin-bottom: 30px; display:flex; gap:10px;">
            <input type="text" name="commentaire" placeholder="Votre commentaire..." required style="border-radius: 20px;">
            <button type="submit" style="width:auto; padding: 10px 20px; border-radius: 20px;">Envoyer</button>
        </form>

        <?php if (empty($comments)): ?>
            <p style="color:gray;">Aucun commentaire.</p>
        <?php else: ?>
            <?php foreach($comments as $com): ?>
                <div style="border-bottom: 1px solid #eee; padding: 15px 0; <?= $com['is_friend'] ? 'background:#f9fff9; padding:15px; border-radius:8px;' : '' ?>">
                    <div style="display: flex; justify-content: space-between;">
                        <div style="display: flex; align-items: center;">
                            <img src="<?= !empty($com['photo_profil']) ? 'uploads/'.$com['photo_profil'] : 'https://via.placeholder.com/30' ?>" 
                                 style="width: 30px; height: 30px; border-radius: 50%; margin-right: 10px;">
                            <strong><?= htmlspecialchars($com['pseudo']) ?></strong>
                            
                            <?php if ($com['is_friend']): ?>
                                <span style="background: #28a745; color: white; font-size: 0.7em; padding: 2px 5px; border-radius: 4px; margin-left: 5px;">Ami</span>
                            <?php endif; ?>
                            
                            <?php if ($com['author_vote'] == 1): ?>
                                <span style="color: #28a745; font-size: 0.8em; margin-left: 10px;">(👍)</span>
                            <?php elseif ($com['author_vote'] == -1): ?>
                                <span style="color: #dc3545; font-size: 0.8em; margin-left: 10px;">(👎)</span>
                            <?php endif; ?>
                        </div>
                        <small style="color:gray;"><?= $com['date_com'] ?></small>
                    </div>
                    <p style="margin-top: 5px; padding-left: 40px;"><?= nl2br(htmlspecialchars($com['commentaire'])) ?></p>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php include 'views/layout/footer.php'; ?>