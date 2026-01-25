<?php 
require_once 'models/VoteModel.php';
require_once 'models/CommentModel.php';
require_once 'models/FriendModel.php';
global $pdo;
$voteModel = new VoteModel($pdo);
$commentModel = new CommentModel($pdo);
$friendModel = new FriendModel($pdo);
$myFriends = isset($_SESSION['user_id']) ? $friendModel->getFriendsList($_SESSION['user_id']) : [];
include 'views/layout/header.php'; 
?>

<div class="feed-tabs">
    <a href="index.php?controller=Home&action=index&filter=global" class="tab-link <?= (!isset($_GET['filter']) || $_GET['filter'] == 'global') ? 'active' : '' ?>">Global</a>
    <a href="index.php?controller=Home&action=index&filter=perso" class="tab-link <?= (isset($_GET['filter']) && $_GET['filter'] == 'perso') ? 'active' : '' ?>">Abonnements</a>
</div>

<div class="container" style="max-width: 600px; margin: 0 auto; padding-top: 0; border: none; background: transparent; box-shadow: none;">
    <div id="feed">
        <?php if(empty($posts)): ?>
            <p style="text-align:center; margin-top:50px;">Aucun post.</p>
        <?php else: ?>
            <?php foreach($posts as $post): ?>
                <?php
                    $stats = $voteModel->getPostStats($post['id_post']);
                    $myVote = $voteModel->getUserVote($_SESSION['user_id'], $post['id_post']);
                    $comments = $commentModel->getCommentsSorted($post['id_post'], $_SESSION['user_id'], 'pertinence');
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
                    <?php if (!empty($post['photo'])): ?>
                        <div class="post-image-container"><img src="uploads/<?= htmlspecialchars($post['photo']) ?>" class="post-img-full"></div>
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
                                <i class="fa-solid fa-comment"></i> <span><?= count($comments) ?></span>
                            </button>
                        </div>
                        <div class="post-actions-right">
                            <button class="action-btn btn-share" onclick="openShareModal(<?= $post['id_post'] ?>)"><i class="fa-solid fa-share"></i></button>
                        </div>
                    </div>

                    <div id="comments-<?= $post['id_post'] ?>" class="comments-section">
                        <div class="comment-list">
                             <?php foreach($comments as $com): ?>
                                <div class="comment-item">
                                    <strong><?= htmlspecialchars($com['pseudo']) ?></strong>: <?= htmlspecialchars($com['commentaire']) ?>
                                </div>
                             <?php endforeach; ?>
                        </div>
                        <form action="index.php?controller=Post&action=show&id=<?= $post['id_post'] ?>" method="post" class="comment-input-area">
                            <input type="text" name="commentaire" class="comment-input" placeholder="..." required>
                            <button type="submit" style="width: auto; padding: 5px 15px;">➤</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<div id="createPostModal" class="modal-overlay" onclick="if(event.target.id === 'createPostModal') this.style.display='none'" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:2000; justify-content:center; align-items:center;">
    <div class="modal-content create-post-modal">
        <h3>Créer un post</h3>
        <form method="post" enctype="multipart/form-data">
            <input type="text" name="titre" placeholder="Titre" required>
            <textarea name="desc" placeholder="Quoi de neuf ?" rows="4"></textarea>
            <input type="file" name="photo" accept="image/*">
            <select name="statut"><option value="public">Public</option><option value="ami">Amis</option></select>
            <button type="submit">Publier</button>
        </form>
        <button onclick="document.getElementById('createPostModal').style.display='none'" class="btn-cancel-modal">Annuler</button>
    </div>
</div>

<div id="shareModal" onclick="if(event.target.id === 'shareModal') this.style.display='none'" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:2000; justify-content:center; align-items:center;">
    <div class="modal-content">
        <h3>Partager</h3>
        <div class="share-options">
            <button onclick="copyLink()">Copier lien</button>
            <?php foreach($myFriends as $friend): ?>
                <button onclick="sendToFriend(<?= $friend['id_utilisateur'] ?>)">👤 <?= htmlspecialchars($friend['pseudo']) ?></button>
            <?php endforeach; ?>
        </div>
        <button onclick="document.getElementById('shareModal').style.display='none'" class="btn-cancel-modal">Annuler</button>
    </div>
</div>

<script>
    let currentPostId = null;
    function voteAjax(postId, value) {
        const fd = new FormData(); fd.append('id', postId); fd.append('value', value);
        fetch('index.php?controller=Post&action=voteAjax', { method: 'POST', body: fd })
        .then(r => r.json()).then(d => {
            if(d.success) {
                document.querySelector(`.count-like-${postId}`).innerText = d.likes;
                document.querySelector(`.count-dislike-${postId}`).innerText = d.dislikes;
                const bl = document.querySelector(`#post-${postId} .btn-like`);
                const bd = document.querySelector(`#post-${postId} .btn-dislike`);
                bl.classList.remove('active'); bd.classList.remove('active');
                if (d.userVote === 1) bl.classList.add('active');
                if (d.userVote === -1) bd.classList.add('active');
            }
        });
    }
    function toggleComments(postId) {
        const s = document.getElementById(`comments-${postId}`);
        s.style.display = (s.style.display === 'block') ? 'none' : 'block';
    }
    function openShareModal(pid) { currentPostId = pid; document.getElementById('shareModal').style.display = 'flex'; }
    function copyLink() { alert("Lien copié (simulation)"); }
    function sendToFriend(fid) {
        const fd = new FormData(); fd.append('friend_id', fid); fd.append('post_id', currentPostId);
        fetch('index.php?controller=Chat&action=sharePost', { method: 'POST', body: fd })
        .then(r => r.json()).then(d => { alert("Envoyé !"); document.getElementById('shareModal').style.display = 'none'; });
    }
</script>

<?php include 'views/layout/footer.php'; ?>