<?php 
// Ce fichier sert uniquement à afficher la liste des posts (utilisé par Home et par AJAX)
// On suppose que $posts, $voteModel, $commentModel sont disponibles
?>

<?php if(empty($posts)): ?>
    <div style="text-align:center; margin-top:50px; color:var(--text-secondary);">
        <i class="fa-solid fa-ghost" style="font-size:2rem; margin-bottom:10px;"></i><br>
        Aucun post à afficher pour le moment.<br>
        <small>Changez les filtres ou suivez plus de gens !</small>
    </div>
<?php else: ?>
    <?php foreach($posts as $post): ?>
        <?php
            // On récupère les stats pour chaque post (si ce n'est pas déjà fait par le contrôleur)
            // Note: En optimisation pro, on ferait ça dans le modèle en une seule requête, mais ici on garde ta logique.
            $stats = $voteModel->getPostStats($post['id_post']);
            $myVote = $voteModel->getUserVote($_SESSION['user_id'], $post['id_post']);
            // On ne charge pas les commentaires par défaut pour alléger, on laisse le clic le faire ou on met 0
            $nbComs = $stats['nb_comments'];
        ?>
        <div class="post-card" id="post-<?= $post['id_post'] ?>">
            <div class="post-header">
                <a href="index.php?controller=User&action=profile&id=<?= $post['id_utilisateur'] ?>">
                    <img src="<?= !empty($post['photo_profil']) ? 'uploads/'.$post['photo_profil'] : 'https://via.placeholder.com/40' ?>" class="post-avatar">
                </a>
                <div class="post-user-info">
                    <a href="index.php?controller=User&action=profile&id=<?= $post['id_utilisateur'] ?>" class="user-name"><?= htmlspecialchars($post['pseudo']) ?></a>
                    <span class="post-meta"><?= $post['date_creation'] ?></span>
                </div>
            </div>
            
            <div class="post-content">
                <strong><?= htmlspecialchars($post['titre']) ?></strong><br>
                <?= nl2br(htmlspecialchars($post['description'])) ?>
            </div>
            
            <?php 
                $imagePath = 'uploads/' . $post['photo'];
                $hasImage = !empty($post['photo']) && file_exists($imagePath);
            ?>
            <?php if ($hasImage): ?>
                <div class="post-image-container">
                    <img src="<?= htmlspecialchars($imagePath) ?>" class="post-img-full">
                </div>
            <?php endif; ?>

            <div class="post-actions">
                <div class="post-actions-left">
                    <button class="action-btn btn-like <?= $myVote == 1 ? 'active' : '' ?>" onclick="voteAjax(<?= $post['id_post'] ?>, 1)">
                        <i class="fa-solid fa-thumbs-up"></i> <span class="count-like-<?= $post['id_post'] ?>"><?= $stats['nb_likes'] ?></span>
                    </button>
                    <button class="action-btn btn-dislike <?= $myVote == -1 ? 'active' : '' ?>" onclick="voteAjax(<?= $post['id_post'] ?>, -1)">
                        <i class="fa-solid fa-thumbs-down"></i> <span class="count-dislike-<?= $post['id_post'] ?>"><?= $stats['nb_dislikes'] ?></span>
                    </button>
                    <button class="action-btn btn-comment" onclick="toggleComments(<?= $post['id_post'] ?>)">
                        <i class="fa-solid fa-comment"></i> <span><?= $nbComs ?></span>
                    </button>
                </div>
                <div class="post-actions-right">
                    <button class="action-btn btn-share" onclick="openShareModal(<?= $post['id_post'] ?>)"><i class="fa-solid fa-share"></i></button>
                </div>
            </div>

            <div id="comments-<?= $post['id_post'] ?>" class="comments-section">
                <p style="text-align:center; color:gray; font-size:0.8em;">
                    <a href="index.php?controller=Post&action=show&id=<?= $post['id_post'] ?>">Voir les commentaires en détail</a>
                </p>
                <form action="index.php?controller=Post&action=show&id=<?= $post['id_post'] ?>" method="post" class="comment-input-area">
                    <input type="text" name="commentaire" class="comment-input" placeholder="Écrire un commentaire..." required>
                    <button type="submit" style="width: auto; padding: 5px 15px;">➤</button>
                </form>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>