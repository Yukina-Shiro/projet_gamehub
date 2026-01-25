</div> <div style="height: 80px;"></div>

<div id="shareModal" class="modal-overlay" onclick="if(event.target.id === 'shareModal') this.style.display='none'">
    <div class="modal-content" style="max-width:350px;">
        <h3>Partager</h3>
        <div class="share-options">
            <button onclick="copyLink()">
                <i class="fa-solid fa-link" style="color:gray;"></i> Copier le lien
            </button>
            <button onclick="shareExternal('twitter')">
                <i class="fa-brands fa-x-twitter" style="color:black;"></i> X (Twitter)
            </button>
            <button onclick="shareExternal('facebook')">
                <i class="fa-brands fa-facebook" style="color:#1877F2;"></i> Facebook
            </button>
            <hr style="margin: 15px 0; border: 0; border-top: 1px solid var(--border-color);">
            <h4 style="margin: 5px 0 10px 0; font-size: 0.9em; color: var(--text-secondary); text-align:left;">Envoyer à un ami</h4>
            <?php if(isset($myFriends) && !empty($myFriends)): foreach($myFriends as $friend): ?>
                <button onclick="sendToFriend(<?= $friend['id_utilisateur'] ?>)">
                    <img src="<?= !empty($friend['photo_profil']) ? 'uploads/'.$friend['photo_profil'] : 'https://via.placeholder.com/30' ?>" style="width:25px; height:25px; border-radius:50%; object-fit:cover;"> 
                    <?= htmlspecialchars($friend['pseudo']) ?>
                </button>
            <?php endforeach; else: ?>
                <p style="font-size:0.8em; color:gray;">Aucun ami trouvé.</p>
            <?php endif; ?>
        </div>
        <button onclick="document.getElementById('shareModal').style.display='none'" class="btn-cancel-modal">Annuler</button>
    </div>
</div>

<div id="createPostModal" class="modal-overlay" onclick="if(event.target.id === 'createPostModal') this.style.display='none'">
    <div class="modal-content create-post-modal" style="text-align: left;">
        <h3 style="margin-top:0;">Créer un post</h3>
        <form action="index.php?controller=Post&action=create" method="post" enctype="multipart/form-data">
            <input type="text" name="titre" placeholder="Titre (Optionnel)">
            <textarea name="desc" placeholder="Quoi de neuf ?" rows="4" required></textarea>
            <input type="file" name="photo" accept="image/*">
            <select name="statut">
                <option value="public">Public</option>
                <option value="ami">Amis seulement</option>
            </select>
            <button type="submit">Publier</button>
        </form>
        <button onclick="document.getElementById('createPostModal').style.display='none'" class="btn-cancel-modal">Annuler</button>
    </div>
</div>

<?php if(isset($_SESSION['user_id'])): ?>
    <?php
        // 1. Récupération Notifs
        require_once 'models/NotificationModel.php';
        require_once 'models/ChatModel.php'; // On inclut le ChatModel
        
        if(!isset($pdo)){ global $pdo; }
        
        $nModel = new NotificationModel($pdo);
        $cModel = new ChatModel($pdo);

        $nbNotif = $nModel->countUnread($_SESSION['user_id']);
        $nbMsg = $cModel->countUnreadMessages($_SESSION['user_id']); // Compte les messages
    ?>

    <div class="bottom-nav">
        <a href="index.php?controller=User&action=search" class="nav-item">
            <i class="fa-solid fa-magnifying-glass"></i>
            <span>Explorer</span>
        </a>
        
        <a href="index.php?controller=Notification&action=index" class="nav-item">
            <div style="position:relative;">
                <i class="fa-regular fa-bell"></i>
                <?php if($nbNotif > 0): ?>
                    <span class="nav-badge"><?= $nbNotif ?></span>
                <?php endif; ?>
            </div>
            <span>Notifs</span>
        </a>
        
        <div class="nav-item-center">
            <button class="fab-center" onclick="document.getElementById('createPostModal').style.display='flex'">
                <i class="fa-solid fa-plus"></i>
            </button>
        </div>

        <a href="index.php?controller=Chat&action=index" class="nav-item">
            <div style="position:relative;">
                <i class="fa-regular fa-comments"></i>
                <?php if($nbMsg > 0): ?>
                    <span class="nav-badge"><?= $nbMsg ?></span>
                <?php endif; ?>
            </div>
            <span>Messages</span>
        </a>
        
        <a href="index.php?controller=User&action=profile" class="nav-item">
            <i class="fa-regular fa-user"></i>
            <span>Profil</span>
        </a>
    </div>
<?php endif; ?>

</body>
</html>