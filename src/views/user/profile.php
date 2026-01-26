<?php include 'views/layout/header.php'; ?>

<div class="container" style="margin-top: 80px;">
    
    <div style="text-align: center; border-bottom: 2px solid var(--border-color); padding-bottom: 20px; margin-bottom: 20px;">
        <?php if (!empty($user['photo_profil'])): ?>
            <img src="uploads/<?= htmlspecialchars($user['photo_profil']) ?>" 
                 style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 4px solid var(--brand-color);">
        <?php else: ?>
            <div style="width: 120px; height: 120px; background: #ddd; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 40px; margin: 0 auto; color:white;">
                <?= strtoupper(substr($user['pseudo'], 0, 1)) ?>
            </div>
        <?php endif; ?>

        <h1 style="margin: 10px 0;"><?= htmlspecialchars($user['pseudo']) ?></h1>
        <p style="color: var(--text-secondary); font-style: italic;">
            <?= !empty($user['bio']) ? nl2br(htmlspecialchars($user['bio'])) : "Aucune bio renseignée." ?>
        </p>

        <div style="margin-top: 15px; display: flex; justify-content: center; gap: 10px; flex-wrap: wrap;">
            <?php if ($isMe): ?>
                <a href="index.php?controller=User&action=edit">
                    <button style="width:auto; background-color: var(--brand-color);">Modifier mon profil</button>
                </a>
            <?php else: ?>
                <!-- Système de Follow -->
                <?php if ($isFollowing): ?>
                    <a href="index.php?controller=User&action=unfollow&id=<?= $user['id_utilisateur'] ?>">
                        <button style="width:auto; background:#6c757d;">Ne plus suivre</button>
                    </a>
                <?php else: ?>
                    <a href="index.php?controller=User&action=follow&id=<?= $user['id_utilisateur'] ?>">
                        <button style="width:auto; background:var(--brand-color);">Suivre</button>
                    </a>
                <?php endif; ?>

                <!-- Système d'Amis & Bouton Message -->
                <?php if ($friendStatus): ?>
                    <?php if ($friendStatus['statut'] === 'valide'): ?>
                        <button style="width:auto; background:#28a745; cursor:default;"><i class="fa-solid fa-check"></i> Amis</button>
                        
                        <!-- NOUVEAU : Bouton Message direct si amis -->
                        <a href="index.php?controller=Chat&action=conversation&id=<?= $user['id_utilisateur'] ?>">
                            <button style="width:auto; background:var(--brand-color);"><i class="fa-solid fa-paper-plane"></i> Message</button>
                        </a>

                        <a href="index.php?controller=User&action=removeFriend&id=<?= $user['id_utilisateur'] ?>" 
                           onclick="return confirm('Retirer cet ami ?');" 
                           style="align-self: center; font-size:0.8em; color:var(--danger);">Retirer</a>
                           
                    <?php elseif ($friendStatus['statut'] === 'attente' && $friendStatus['id_utilisateur1'] == $_SESSION['user_id']): ?>
                        <button style="width:auto; background:#ffc107; color:black; cursor: default;">Demande envoyée...</button>
                    
                    <?php elseif ($friendStatus['statut'] === 'attente'): ?>
                        <a href="index.php?controller=User&action=acceptFriend&id=<?= $user['id_utilisateur'] ?>">
                            <button style="width:auto; background:#28a745;">Accepter la demande</button>
                        </a>
                    <?php endif; ?>
                <?php else: ?>
                    <a href="index.php?controller=User&action=addFriend&id=<?= $user['id_utilisateur'] ?>">
                        <button style="width:auto; background:#17a2b8;">Ajouter en ami</button>
                    </a>
                <?php endif; ?>

            <?php endif; ?>
        </div>
    </div>

    <!-- Reste du profil (Amis, Abonnements, Publications) -->
    <div style="display: flex; gap: 20px; margin-top: 30px; margin-bottom: 30px; text-align: left; flex-wrap: wrap;">
        <div class="friend-block" style="flex: 1; min-width: 250px;">
            <h4 style="margin-top: 0;">Amis (<?= count($friendsList) ?>)</h4>
            <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                <?php if(empty($friendsList)): ?>
                    <small style="color:var(--text-secondary);">Aucun ami.</small>
                <?php else: ?>
                    <?php foreach($friendsList as $f): ?>
                        <a href="index.php?controller=User&action=profile&id=<?= $f['id_utilisateur'] ?>" title="<?= htmlspecialchars($f['pseudo']) ?>">
                            <img src="<?= !empty($f['photo_profil']) ? 'uploads/'.$f['photo_profil'] : 'public/img/default_avatar.png' ?>" 
                                 style="width: 45px; height: 45px; border-radius: 50%; object-fit: cover; border: 2px solid var(--border-color);">
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="friend-block" style="flex: 1; min-width: 250px;">
            <h4 style="margin-top: 0;">Abonnements (<?= count($followingList) ?>)</h4>
            <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                <?php if(empty($followingList)): ?>
                    <small style="color:var(--text-secondary);">Aucun abonnement.</small>
                <?php else: ?>
                    <?php foreach($followingList as $f): ?>
                        <a href="index.php?controller=User&action=profile&id=<?= $f['id_utilisateur'] ?>" title="<?= htmlspecialchars($f['pseudo']) ?>">
                            <img src="<?= !empty($f['photo_profil']) ? 'uploads/'.$f['photo_profil'] : 'public/img/default_avatar.png' ?>" 
                                 style="width: 45px; height: 45px; border-radius: 50%; object-fit: cover; border: 2px solid var(--border-color);">
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <h3>Publications</h3>
    <div class="feed">
        <?php if (empty($posts)): ?>
            <p style="color:var(--text-secondary); font-style: italic; text-align: center;">Aucun post publié.</p>
        <?php else: ?>
            <?php foreach($posts as $post): ?>
                <div class="post-card">
                    <div class="post-header">
                        <img src="<?= !empty($user['photo_profil']) ? 'uploads/'.$user['photo_profil'] : 'public/img/default_avatar.png' ?>" class="post-avatar">
                        <div class="post-user-info">
                            <strong><?= htmlspecialchars($user['pseudo']) ?></strong>
                            <span class="post-meta"><?= $post['date_creation'] ?></span>
                        </div>
                    </div>
                    <div class="post-content">
                        <strong><?= htmlspecialchars($post['titre']) ?></strong><br>
                        <?= nl2br(htmlspecialchars($post['description'])) ?>
                    </div>
                    <?php if (!empty($post['photo'])): ?>
                        <div class="post-image-container">
                            <img src="uploads/<?= htmlspecialchars($post['photo']) ?>" class="post-img-full">
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($isMe): ?>
                        <div style="margin-top: 10px; border-top: 1px solid var(--border-color); padding-top: 10px; display: flex; gap: 15px;">
                            <a href="index.php?controller=Post&action=edit&id=<?= $post['id_post'] ?>" style="color: var(--brand-color); font-weight: bold; font-size: 0.9em; display:flex; align-items:center; gap:5px;">
                                <i class="fa-solid fa-pen"></i> Modifier
                            </a>
                            <a href="index.php?controller=Post&action=delete&id=<?= $post['id_post'] ?>" style="color: var(--danger); font-weight: bold; font-size: 0.9em; display:flex; align-items:center; gap:5px;" onclick="return confirm('Voulez-vous vraiment supprimer ce post ?');">
                                <i class="fa-solid fa-trash"></i> Supprimer
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php include 'views/layout/footer.php'; ?>