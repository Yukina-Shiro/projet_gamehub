<?php if(empty($posts)): ?>
    <div style="text-align:center; margin-top:50px; color:var(--text-secondary);">
        <i class="fa-solid fa-ghost" style="font-size:2rem; margin-bottom:10px;"></i><br>
        Aucun post à afficher pour le moment.<br>
        <small>Changez les filtres ou suivez plus de gens !</small>
    </div>
<?php else: ?>
    <?php foreach($posts as $post): ?>
        <?php
            $stats = $voteModel->getPostStats($post['id_post']);
            $myVote = $voteModel->getUserVote($_SESSION['user_id'], $post['id_post']);
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
                    
                    <a href="index.php?controller=Post&action=show&id=<?= $post['id_post'] ?>" class="action-btn btn-comment" style="text-decoration:none;">
                        <i class="fa-solid fa-comment"></i> <span><?= $nbComs ?></span>
                    </a>
                </div>
                <div class="post-actions-right">
                    <button class="action-btn btn-share" onclick="openShareModal(<?= $post['id_post'] ?>)"><i class="fa-solid fa-share"></i></button>
                </div>
            </div>
            </div>
    <?php endforeach; ?>
<?php endif; ?>