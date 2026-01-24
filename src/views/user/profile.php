<?php include 'views/layout/header.php'; ?>

<div class="container">
    
    <div style="text-align: center; border-bottom: 2px solid #eee; padding-bottom: 20px; margin-bottom: 20px;">
        <?php if (!empty($user['photo_profil'])): ?>
            <img src="uploads/<?= htmlspecialchars($user['photo_profil']) ?>" 
                 style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 4px solid #28a745; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
        <?php else: ?>
            <div style="width: 120px; height: 120px; background: #ddd; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 40px; margin: 0 auto; color: white;">
                <?= strtoupper(substr($user['pseudo'], 0, 1)) ?>
            </div>
        <?php endif; ?>

        <h1 style="margin: 10px 0;"><?= htmlspecialchars($user['pseudo']) ?></h1>
        <p style="color: #555; font-style: italic;">
            <?= !empty($user['bio']) ? nl2br(htmlspecialchars($user['bio'])) : "Aucune bio renseignée." ?>
        </p>

        <div style="margin-top: 15px;">
            <?php if ($isMe): ?>
                <a href="index.php?controller=User&action=edit">
                    <button style="width:auto; background-color: #007bff;">Modifier mon profil</button>
                </a>
            <?php else: ?>
                <?php if ($isFollowing): ?>
                    <a href="index.php?controller=User&action=unfollow&id=<?= $user['id_utilisateur'] ?>">
                        <button style="width:auto; background:#6c757d;">Ne plus suivre</button>
                    </a>
                <?php else: ?>
                    <a href="index.php?controller=User&action=follow&id=<?= $user['id_utilisateur'] ?>">
                        <button style="width:auto; background:#007bff;">Suivre</button>
                    </a>
                <?php endif; ?>

                <?php if ($friendStatus): ?>
                    <?php if ($friendStatus['statut'] === 'valide'): ?>
                        <button style="width:auto; background:#28a745; cursor:default;">Amis ✔</button>
                        <a href="index.php?controller=User&action=removeFriend&id=<?= $user['id_utilisateur'] ?>" 
                           onclick="return confirm('Retirer cet ami ?');" 
                           style="font-size:0.8em; color:#dc3545; margin-left: 5px;">Retirer</a>

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

    <div style="display: flex; gap: 20px; margin-top: 30px; margin-bottom: 30px; text-align: left;">
        
        <div style="flex: 1; background: #f9f9f9; padding: 15px; border-radius: 8px; border: 1px solid #eee;">
            <h4 style="margin-top: 0;">Amis (<?= count($friendsList) ?>)</h4>
            <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                <?php if(empty($friendsList)): ?>
                    <small style="color:gray;">Aucun ami.</small>
                <?php else: ?>
                    <?php foreach($friendsList as $f): ?>
                        <a href="index.php?controller=User&action=profile&id=<?= $f['id_utilisateur'] ?>" title="<?= htmlspecialchars($f['pseudo']) ?>">
                            <img src="<?= !empty($f['photo_profil']) ? 'uploads/'.$f['photo_profil'] : 'https://via.placeholder.com/40' ?>" 
                                 style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid white; box-shadow: 0 0 3px rgba(0,0,0,0.2);">
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <div style="flex: 1; background: #f9f9f9; padding: 15px; border-radius: 8px; border: 1px solid #eee;">
            <h4 style="margin-top: 0;">Abonnements (<?= count($followingList) ?>)</h4>
            <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                <?php if(empty($followingList)): ?>
                    <small style="color:gray;">Aucun abonnement.</small>
                <?php else: ?>
                    <?php foreach($followingList as $f): ?>
                        <a href="index.php?controller=User&action=profile&id=<?= $f['id_utilisateur'] ?>" title="<?= htmlspecialchars($f['pseudo']) ?>">
                            <img src="<?= !empty($f['photo_profil']) ? 'uploads/'.$f['photo_profil'] : 'https://via.placeholder.com/40' ?>" 
                                 style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid white; box-shadow: 0 0 3px rgba(0,0,0,0.2);">
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

    </div>

    <h3>Publications de <?= htmlspecialchars($user['pseudo']) ?></h3>
    
    <?php if (empty($posts)): ?>
        <p style="color:gray; font-style: italic;">Cet utilisateur n'a rien publié pour le moment.</p>
    <?php else: ?>
        <div class="feed">
            <?php foreach($posts as $post): ?>
                <div class="container" style="border: 1px solid #ddd; padding: 15px; margin-bottom: 15px; border-radius: 5px;">
                    
                    <div style="display:flex; justify-content:space-between; margin-bottom: 10px;">
                        <h4 style="margin: 0;"><?= htmlspecialchars($post['titre']) ?></h4>
                        <span style="font-size:0.8em; color:gray;"><?= $post['date_creation'] ?></span>
                    </div>

                    <p style="margin-bottom: 15px;"><?= nl2br(htmlspecialchars($post['description'])) ?></p>

                    <?php if (!empty($post['photo'])): ?>
                        <div style="text-align: center; margin-bottom: 15px;">
                            <img src="uploads/<?= htmlspecialchars($post['photo']) ?>" style="max-width:100%; max-height:300px; border-radius:5px;">
                        </div>
                    <?php endif; ?>

                    <div style="font-size: 0.9em; color: #666; display: flex; justify-content: space-between; align-items: center;">
                        <span>Visibilité : <span style="background: #eee; padding: 2px 6px; border-radius: 4px;"><?= $post['statut'] ?></span></span>
                        
                        <?php if ($isMe): ?>
                            <div>
                                <a href="index.php?controller=Post&action=edit&id=<?= $post['id_post'] ?>" style="color: #007bff; margin-right: 15px; text-decoration: none;">✏ Modifier</a>
                                <a href="index.php?controller=Post&action=delete&id=<?= $post['id_post'] ?>" style="color: #dc3545; text-decoration: none;" onclick="return confirm('Voulez-vous vraiment supprimer ce post ?');">🗑 Supprimer</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>

<?php include 'views/layout/footer.php'; ?>