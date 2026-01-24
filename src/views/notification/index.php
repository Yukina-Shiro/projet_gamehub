<?php include 'views/layout/header.php'; ?>

<div class="container">
    <h2>🔔 Vos Notifications</h2>

    <?php if (empty($notifs)): ?>
        <p style="text-align:center; color:gray; margin-top:30px;">Aucune notification pour le moment.</p>
    <?php else: ?>
        <ul style="list-style: none; padding: 0;">
            <?php foreach($notifs as $n): ?>
                <li style="
                    background: <?= $n['lu'] == 0 ? '#e8f4ff' : 'white' ?>; 
                    border-bottom: 1px solid #ddd; 
                    padding: 15px; 
                    display: flex; 
                    justify-content: space-between; 
                    align-items: center;
                ">
                    <div style="display: flex; align-items: center;">
                        <img src="<?= !empty($n['photo_profil']) ? 'uploads/'.$n['photo_profil'] : 'https://via.placeholder.com/40' ?>" 
                             style="width: 40px; height: 40px; border-radius: 50%; margin-right: 15px; object-fit: cover;">
                        
                        <div>
                            <div>
                                <strong><?= htmlspecialchars($n['pseudo']) ?></strong> <?= $n['message'] ?>
                            </div>
                            <small style="color:gray;"><?= $n['date_notif'] ?></small>
                            
                            <?php if ($n['type'] === 'demande_ami'): ?>
                                <div style="margin-top: 5px;">
                                    
                                    <?php if ($n['statut_ami'] === 'valide'): ?>
                                        <span style="color: #28a745; font-weight: bold; font-size: 0.9em;">
                                            ✔ Demande acceptée
                                        </span>
                                    
                                    <?php elseif ($n['statut_ami'] === 'attente'): ?>
                                        <a href="index.php?controller=User&action=acceptFriend&id=<?= $n['id_emetteur'] ?>&fromNotif=1">
                                            <button style="width: auto; padding: 5px 10px; font-size: 0.8em; background: #28a745;">Accepter</button>
                                        </a>
                                        <a href="index.php?controller=User&action=refuseRequest&id=<?= $n['id_emetteur'] ?>">
                                            <button style="width: auto; padding: 5px 10px; font-size: 0.8em; background: #dc3545;">Refuser</button>
                                        </a>
                                    
                                    <?php else: ?>
                                        <span style="color: gray; font-size: 0.9em;">Demande obsolète</span>
                                    <?php endif; ?>

                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div style="text-align: right; min-width: 100px;">
                        <?php if($n['lu'] == 0): ?>
                            <a href="index.php?controller=Notification&action=markRead&id=<?= $n['id_notif'] ?>" title="Marquer comme lu" style="text-decoration:none; margin-right:10px;">🔵</a>
                        <?php endif; ?>
                        <a href="index.php?controller=Notification&action=delete&id=<?= $n['id_notif'] ?>" title="Supprimer" style="text-decoration:none; font-size: 1.2em;">❌</a>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>

<?php include 'views/layout/footer.php'; ?>