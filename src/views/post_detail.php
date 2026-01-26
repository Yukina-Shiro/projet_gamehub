<?php include 'views/layout/header.php'; ?>

<div class="container" style="max-width: 700px; margin-bottom: 80px;">
    
    <a href="index.php?controller=Home&action=index" style="display:inline-flex; align-items:center; gap:5px; margin-bottom:15px; color:var(--text-secondary); font-weight:bold; cursor:pointer; text-decoration:none;">
        <i class="fa-solid fa-arrow-left"></i> Retour au fil
    </a>

    <div class="post-card" id="post-<?= $post['id_post'] ?>">
        <div class="post-header">
            <a href="index.php?controller=User&action=profile&id=<?= $post['id_utilisateur'] ?>">
                <img src="<?= !empty($post['photo_profil']) ? 'uploads/'.$post['photo_profil'] : 'https://via.placeholder.com/40' ?>" class="post-avatar">
            </a>
            <div class="post-user-info">
                <a href="index.php?controller=User&action=profile&id=<?= $post['id_utilisateur'] ?>" class="user-name">
                    <?= htmlspecialchars($post['pseudo']) ?>
                </a>
                <span class="post-meta"><?= $post['date_creation'] ?></span>
            </div>
        </div>
        
        <div class="post-content">
            <h3 style="margin-top:0; color:var(--text-color);"><?= htmlspecialchars($post['titre']) ?></h3>
            <div style="color:var(--text-color); line-height:1.5;">
                <?= nl2br(htmlspecialchars($post['description'])) ?>
            </div>
        </div>

        <?php 
            $imagePath = 'uploads/' . $post['photo'];
            if (!empty($post['photo']) && file_exists($imagePath)): 
        ?>
            <div class="post-image-container">
                <img src="<?= htmlspecialchars($imagePath) ?>" class="post-img-full">
            </div>
        <?php endif; ?>

        <div class="post-actions" style="border-top: 1px solid var(--border-color); padding-top: 10px; margin-top: 10px;">
            <div class="post-actions-left">
                <button class="action-btn btn-like <?= $myVote == 1 ? 'active' : '' ?>" onclick="voteAjax(<?= $post['id_post'] ?>, 1)">
                    <i class="fa-solid fa-thumbs-up"></i> <span class="count-like-<?= $post['id_post'] ?>"><?= $stats['nb_likes'] ?></span>
                </button>
                <button class="action-btn btn-dislike <?= $myVote == -1 ? 'active' : '' ?>" onclick="voteAjax(<?= $post['id_post'] ?>, -1)">
                    <i class="fa-solid fa-thumbs-down"></i> <span class="count-dislike-<?= $post['id_post'] ?>"><?= $stats['nb_dislikes'] ?></span>
                </button>
                <button class="action-btn btn-comment" style="cursor:default;">
                    <i class="fa-solid fa-comment"></i> <span id="global-comment-count"><?= count($comments) ?></span>
                </button>
            </div>
        </div>
    </div>

    <h3 style="margin: 20px 0 10px 0;">Commentaires</h3>
    
    <div class="post-card" style="padding: 15px;">
        <form id="comment-form" style="display:flex; gap:10px; margin:0;">
            <input type="hidden" id="post_id" value="<?= $post['id_post'] ?>">
            <input type="text" id="comment_input" placeholder="Ajouter un commentaire..." required style="margin:0; flex:1; border-radius: 20px;">
            <button type="submit" id="submit-comment-btn" style="width: auto; border-radius: 20px; padding: 0 20px;">Envoyer</button>
        </form>
    </div>

    <div class="comments-list-wrapper" id="comments-container">
        <?php if(empty($comments)): ?>
            <p id="no-comment-msg" style="text-align:center; color:var(--text-secondary); margin-top:20px;">Soyez le premier à commenter !</p>
        <?php else: ?>
            <?php foreach($comments as $com): ?>
                
                <div class="generic-item" style="align-items: flex-start; margin-bottom: 10px; cursor: default;">
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
                <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<script>
    document.getElementById('comment-form').addEventListener('submit', function(e) {
        e.preventDefault(); 
        const input = document.getElementById('comment_input');
        const postId = document.getElementById('post_id').value;
        const content = input.value.trim();
        const submitBtn = document.getElementById('submit-comment-btn');

        if(content === '') return;

        submitBtn.disabled = true;
        submitBtn.innerText = '...';

        const fd = new FormData();
        fd.append('post_id', postId);
        fd.append('commentaire', content);

        fetch('index.php?controller=Post&action=addCommentAjax', { method: 'POST', body: fd })
        .then(response => response.json())
        .then(data => {
            submitBtn.disabled = false;
            submitBtn.innerText = 'Envoyer';

            if(data.success) {
                input.value = '';
                const noComMsg = document.getElementById('no-comment-msg');
                if(noComMsg) noComMsg.remove();

                const container = document.getElementById('comments-container');
                container.insertAdjacentHTML('afterbegin', data.html); // On injecte le HTML reçu
                
                // Petit effet d'apparition
                const newCom = container.firstChild;
                // Si newCom est un noeud texte (espace), on prend le suivant
                const el = (newCom.nodeType === 1) ? newCom : newCom.nextSibling;
                if(el) {
                    el.style.opacity = '0';
                    el.style.transition = 'opacity 0.5s';
                    setTimeout(() => { el.style.opacity = '1'; }, 10);
                }

                const countSpan = document.getElementById('global-comment-count');
                countSpan.innerText = parseInt(countSpan.innerText) + 1;
            } else {
                alert("Erreur : " + data.message);
            }
        });
    });

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
</script>

<?php include 'views/layout/footer.php'; ?>