<?php include 'views/layout/header.php'; ?>

<div class="container">
    <h2>🔔 Vos Notifications</h2>
    
    <a href="index.php?controller=Notification&action=markAllRead" class="mark-read-btn" 
       style="display: inline-block; text-decoration: none; margin-bottom:20px;">
       <i class="fa-solid fa-check-double"></i> Marquer tout comme vu
    </a>

    <?php if (empty($notifs)): ?>
        <p style="text-align:center; color:var(--text-secondary); margin-top:30px;">Aucune notification.</p>
    <?php else: ?>
        <ul style="list-style: none; padding: 0;">
            <?php foreach($notifs as $n): ?>
                <li class="generic-item" style="<?= $n['lu'] == 0 ? 'border-left: 4px solid var(--logo-color); background: var(--hover-bg);' : '' ?>">
                    <div style="display: flex; align-items: center; width:100%;">
                        
                        <img src="<?= !empty($n['photo_profil']) ? 'uploads/'.$n['photo_profil'] : 'https://via.placeholder.com/40' ?>" 
                             style="width: 40px; height: 40px; border-radius: 50%; margin-right: 15px; object-fit: cover;">
                        
                        <div style="flex:1;">
                            <div>
                                <strong><?= htmlspecialchars($n['pseudo']) ?></strong> <?= $n['message'] ?>
                            </div>
                            <small style="color:var(--text-secondary); font-size:0.8em;"><?= $n['date_notif'] ?></small>
                        </div>

                        <div style="text-align: right; display: flex; align-items: center; gap: 10px;">
                            
                            <?php if($n['lu'] == 0): ?>
                                <a href="index.php?controller=Notification&action=markRead&id=<?= $n['id_notif'] ?>" 
                                   title="Marquer comme vu" 
                                   style="text-decoration:none; font-size: 1.2em; color: var(--logo-color);">
                                   <i class="fa-solid fa-circle-check"></i>
                                </a>
                            <?php endif; ?>

                            <a href="index.php?controller=Notification&action=delete&id=<?= $n['id_notif'] ?>" 
                               title="Supprimer" 
                               style="text-decoration:none; color:var(--text-secondary);">
                               <i class="fa-solid fa-xmark"></i>
                            </a>
                        </div>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>

<?php include 'views/layout/footer.php'; ?>