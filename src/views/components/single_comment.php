<?php 
// Ce fichier attend une variable $com (tableau des données du commentaire)
?>
<div class="generic-item new-comment" style="align-items: flex-start; margin-bottom: 10px; cursor: default; opacity: 0; transition: opacity 0.5s ease-in;">
    <a href="index.php?controller=User&action=profile&id=<?= $com['id_utilisateur'] ?>">
        <img src="<?= !empty($com['photo_profil']) ? 'uploads/'.$com['photo_profil'] : 'https://via.placeholder.com/40' ?>" 
             style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; margin-right: 15px; border: 1px solid var(--border-color);">
    </a>
    <div style="flex:1;">
        <div style="display:flex; justify-content:space-between; align-items: center;">
            <div>
                <strong><?= htmlspecialchars($com['pseudo']) ?></strong>
                
                <?php if(isset($com['vote_at_time']) && $com['vote_at_time'] != 0): ?>
                    <?php if($com['vote_at_time'] == 1): ?>
                        <span style="color: var(--success); font-size: 0.8em; margin-left: 5px; background: rgba(40, 167, 69, 0.1); padding: 2px 6px; border-radius: 4px;">
                            <i class="fa-solid fa-thumbs-up"></i> A aimé
                        </span>
                    <?php elseif($com['vote_at_time'] == -1): ?>
                        <span style="color: var(--danger); font-size: 0.8em; margin-left: 5px; background: rgba(220, 53, 69, 0.1); padding: 2px 6px; border-radius: 4px;">
                            <i class="fa-solid fa-thumbs-down"></i> N'a pas aimé
                        </span>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            
            <small style="color:var(--text-secondary); font-size:0.8em;"><?= $com['date_com'] ?></small>
        </div>
        <p style="margin: 5px 0 0 0; line-height: 1.4; color:var(--text-color);">
            <?= nl2br(htmlspecialchars($com['commentaire'])) ?>
        </p>
    </div>
</div>