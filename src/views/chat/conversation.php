<?php include 'views/layout/header.php'; ?>

<div class="container" style="display: flex; flex-direction: column; height: 80vh;">
    <div style="border-bottom: 1px solid var(--border-color); padding-bottom: 10px; margin-bottom: 10px; display: flex; align-items: center;">
        <a href="index.php?controller=Chat&action=index" style="margin-right: 15px; font-size: 1.2em;">⬅</a>
        <img src="<?= !empty($otherUser['photo_profil']) ? 'uploads/'.$otherUser['photo_profil'] : 'https://via.placeholder.com/40' ?>" 
             style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; margin-right: 10px;">
        <h3><?= htmlspecialchars($otherUser['pseudo']) ?></h3>
    </div>

    <div id="chat-box" style="flex: 1; overflow-y: auto; padding: 10px; background: var(--bg-color); border-radius: 8px; margin-bottom: 10px; border: 1px solid var(--border-color);">
        <?php foreach($messages as $msg): ?>
            <?php $isMe = ($msg['id_emetteur'] == $_SESSION['user_id']); ?>
            
            <div style="display: flex; justify-content: <?= $isMe ? 'flex-end' : 'flex-start' ?>; margin-bottom: 10px;">
                <div class="<?= $isMe ? 'chat-bubble-me' : 'chat-bubble-other' ?>" 
                     style="max-width: 70%; padding: 10px 15px; border-radius: 15px;">
                    
                    <p style="margin: 0;"><?= htmlspecialchars($msg['contenu']) ?></p>
                    
                    <?php if(!empty($msg['id_post_partage'])): ?>
                        <div style="margin-top: 10px; background: rgba(0,0,0,0.1); padding: 5px; border-radius: 5px;">
                            <a href="index.php?controller=Post&action=show&id=<?= $msg['id_post_partage'] ?>" 
                               style="color: inherit; text-decoration: underline; font-size: 0.9em;">
                                🔗 Voir le post partagé
                            </a>
                        </div>
                    <?php endif; ?>

                    <small style="display: block; font-size: 0.7em; margin-top: 5px; opacity: 0.8; text-align: right;">
                        <?= date('H:i', strtotime($msg['date_envoi'])) ?>
                    </small>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <form method="post" style="display: flex; gap: 10px;">
        <input type="text" name="content" placeholder="Écrivez un message..." autocomplete="off" required style="margin: 0; border-radius: 20px;">
        <button type="submit" style="width: auto; border-radius: 50%; padding: 10px 15px;">➤</button>
    </form>
</div>

<script>
    const chatBox = document.getElementById('chat-box');
    if(chatBox) chatBox.scrollTop = chatBox.scrollHeight;
</script>

<?php include 'views/layout/footer.php'; ?>