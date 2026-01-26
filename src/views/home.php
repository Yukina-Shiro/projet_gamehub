<?php 
require_once 'models/VoteModel.php';
require_once 'models/CommentModel.php';
require_once 'models/FriendModel.php';
global $pdo;
$voteModel = new VoteModel($pdo);
$commentModel = new CommentModel($pdo);
$friendModel = new FriendModel($pdo);
$myFriends = isset($_SESSION['user_id']) ? $friendModel->getFriendsList($_SESSION['user_id']) : [];

// On inclut le header pour avoir le style et le menu
include 'views/layout/header.php'; 
?>

<div id="perso-filters" class="sub-filter-bar" style="display: none; justify-content: center; gap: 15px; background: var(--secondary-bg); padding: 10px; border-radius: 8px; margin-bottom: 20px; border: 1px solid var(--border-color);">
    <div style="display: flex; gap: 10px; align-items: center; width: 100%; justify-content: space-between;">
        <select id="filter-source" onchange="refreshFeed()" style="margin:0; padding: 8px; flex: 1;">
            <option value="all">Tout (Amis + Abos)</option>
            <option value="amis">Amis uniquement</option>
            <option value="abo">Abonnements uniquement</option>
        </select>
        <input type="date" id="filter-date" onchange="refreshFeed()" style="margin:0; width: auto;">
        <button onclick="resetDate()" style="width:auto; background:transparent; color:var(--danger); border:none;"><i class="fa-solid fa-xmark"></i></button>
    </div>
</div>

<div class="container" style="max-width: 600px; margin: 0 auto; padding-top: 0; border: none; background: transparent; box-shadow: none;">
    <div id="feed-loader" style="display:none; text-align:center; padding:20px; color:var(--logo-color);">
        <i class="fa-solid fa-circle-notch fa-spin fa-2x"></i>
    </div>

    <div id="feed-content">
        <?php include 'views/components/feed_content.php'; ?>
    </div>
</div>

<script>
    let currentFilter = 'global';

    function switchFeed(type) {
        currentFilter = type;

        // 1. CORRECTION COULEUR : On retire 'active' partout d'abord
        const allTabs = document.querySelectorAll('.floating-tab');
        allTabs.forEach(tab => {
            tab.classList.remove('active');
        });

        // 2. On ajoute 'active' uniquement sur le bouton cliqué
        const activeTab = document.getElementById('tab-' + type);
        if (activeTab) {
            activeTab.classList.add('active');
        }

        // 3. Afficher/Cacher la barre de filtres (Date/Source)
        const filterBar = document.getElementById('perso-filters');
        if (filterBar) {
            filterBar.style.display = (type === 'perso') ? 'flex' : 'none';
        }

        // 4. Charger le contenu
        refreshFeed();
    }

    function refreshFeed() {
        document.getElementById('feed-content').style.opacity = '0.5';
        document.getElementById('feed-loader').style.display = 'block';

        const source = document.getElementById('filter-source').value;
        const date = document.getElementById('filter-date').value;
        let url = `index.php?controller=Home&action=ajaxFeed&filter=${currentFilter}`;
        if (currentFilter === 'perso') url += `&source=${source}&date=${date}`;

        fetch(url)
        .then(response => response.text())
        .then(html => {
            document.getElementById('feed-content').innerHTML = html;
            document.getElementById('feed-content').style.opacity = '1';
            document.getElementById('feed-loader').style.display = 'none';
        });
    }

    function resetDate() {
        document.getElementById('filter-date').value = '';
        refreshFeed();
    }

    // Fonctions Vote/Share
    let currentPostId = null;
    function voteAjax(postId, value) {
        const fd = new FormData(); fd.append('id', postId); fd.append('value', value);
        fetch('index.php?controller=Post&action=voteAjax', { method: 'POST', body: fd })
        .then(r => r.json()).then(d => {
            if(d.success) {
                document.querySelector(`#post-${postId} .btn-like span`).innerText = d.likes;
                document.querySelector(`#post-${postId} .btn-dislike span`).innerText = d.dislikes;
                document.querySelector(`#post-${postId} .btn-like`).classList.toggle('active', d.userVote === 1);
                document.querySelector(`#post-${postId} .btn-dislike`).classList.toggle('active', d.userVote === -1);
            }
        });
    }
    function toggleComments(postId) {
        const s = document.getElementById(`comments-${postId}`);
        s.style.display = (s.style.display === 'block') ? 'none' : 'block';
    }
    function openShareModal(pid) { currentPostId = pid; document.getElementById('shareModal').style.display = 'flex'; }
    function copyLink() { alert("Lien copié !"); }
    function sendToFriend(fid) {
        const fd = new FormData(); fd.append('friend_id', fid); fd.append('post_id', currentPostId);
        fetch('index.php?controller=Chat&action=sharePost', { method: 'POST', body: fd })
        .then(r => r.json()).then(d => { alert("Envoyé !"); document.getElementById('shareModal').style.display = 'none'; });
    }
    function shareExternal(network) {
        const url = encodeURIComponent(window.location.origin + window.location.pathname + '?controller=Post&action=show&id=' + currentPostId);
        let shareUrl = (network === 'twitter') ? `https://twitter.com/intent/tweet?url=${url}` : `https://www.facebook.com/sharer/sharer.php?u=${url}`;
        window.open(shareUrl, '_blank');
    }
</script>

<?php include 'views/layout/footer.php'; ?>