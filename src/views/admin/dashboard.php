<?php include 'views/layout/header.php'; ?>

<div class="container">
    <h2>🛡️ Administration GameHub</h2>

    <form method="post" action="index.php?controller=Admin&action=sendMassMail">
        <h3>1. Gestion des Membres</h3>

        <div style="margin-bottom: 15px; padding: 10px; background: var(--secondary-bg); border-radius: 8px; border: 1px solid var(--border-color);">
            <label>Filtrer par date d'inscription : </label>
            <input type="date" name="filter_date" value="<?= htmlspecialchars($filter_date ?? '') ?>"
                   onchange="location.href='index.php?controller=Admin&action=index&filter_date='+this.value" style="width:auto; margin:0;">
            <a href="index.php?controller=Admin&action=index" style="margin-left:10px; font-size: 0.9em;">Effacer le filtre</a>
        </div>

        <table style="width:100%; border-collapse: collapse; margin-bottom: 20px;">
            <tr style="background:var(--brand-color); color:white; text-align: left;">
                <th style="padding: 10px;"><input type="checkbox" onclick="toggleAll(this)"></th>
                <th>Pseudo</th>
                <th>Email</th>
                <th>Rôle</th>
                <th>Actions</th>
            </tr>
            <?php foreach($users as $u): ?>
            <tr style="border-bottom: 1px solid var(--border-color);">
                <td style="padding: 10px;"><input type="checkbox" name="emails[]" value="<?= $u['email'] ?>"></td>
                <td><?= htmlspecialchars($u['pseudo']) ?></td>
                <td><?= htmlspecialchars($u['email']) ?></td>
                <td>
                    <span class="badge" style="padding: 2px 8px; border-radius: 4px; background: <?= $u['role'] === 'admin' ? '#dc3545' : '#6c757d' ?>; color: white; font-size: 0.8em;">
                        <?= strtoupper($u['role']) ?>
                    </span>
                </td>
                <td>
                    <?php if($u['id_utilisateur'] != $_SESSION['user_id']): ?>
                        <a href="index.php?controller=Admin&action=toggleRole&id=<?= $u['id_utilisateur'] ?>"
                           style="color: var(--brand-color); font-size: 0.9em; margin-right: 10px;"
                           onclick="return confirm('Voulez-vous vraiment <?= $u['role'] === 'admin' ? 'rétrograder' : 'promouvoir' ?> <?= htmlspecialchars($u['pseudo']) ?> ?');">
                           <?= $u['role'] === 'admin' ? 'Rétrograder' : 'Promouvoir' ?>
                        </a>

                        <a href="index.php?controller=Admin&action=banUser&id=<?= $u['id_utilisateur'] ?>"
                           onclick="return confirm('Supprimer ce membre définitivement ?')"
                           style="color:var(--danger); font-weight: bold;">Supprimer</a>
                    <?php else: ?>
                        <small style="color:gray;">(Moi)</small>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>

        <div style="padding:15px; background:var(--secondary-bg); border-radius:8px; border: 1px solid var(--border-color);">
            <h4>✉️ Envoyer un message aux membres sélectionnés</h4>
            <input type="text" name="subject" placeholder="Sujet du mail" required>
            <textarea name="message" rows="4" placeholder="Message aux membres..." required></textarea>
            <button type="submit" style="background:var(--brand-color);">Envoyer les e-mails</button>
        </div>
    </form>

    <h3 style="margin-top:40px;">2. Modération des Contenus</h3>
    <div class="feed">
        <?php if(empty($posts)): ?>
            <p>Aucun post trouvé.</p>
        <?php else: foreach($posts as $p): ?>
            <div class="post-card" style="<?= $p['is_blocked'] ? 'border: 2px solid orange; background: #fff5e6;' : '' ?>">
                <strong>@<?= htmlspecialchars($p['pseudo']) ?></strong> : <?= htmlspecialchars($p['titre']) ?>
                <p style="font-size: 0.9em; color: var(--text-secondary);"><?= htmlspecialchars($p['description']) ?></p>

                <div style="text-align:right; margin-top: 10px; display: flex; justify-content: flex-end; gap: 10px;">
                    <a href="index.php?controller=Admin&action=blockPost&id=<?= $p['id_post'] ?>">
                        <button style="width:auto; background:<?= $p['is_blocked'] ? '#28a745' : '#fd7e14' ?>; padding: 5px 15px;">
                            <?= $p['is_blocked'] ? '✅ Débloquer' : '🚫 Bloquer' ?>
                        </button>
                    </a>
                    <a href="index.php?controller=Admin&action=deletePost&id=<?= $p['id_post'] ?>"
                       onclick="return confirm('Supprimer définitivement ce post ?')"
                       style="color:var(--danger); font-weight: bold; align-self:center;">Supprimer</a>
                </div>
            </div>
        <?php endforeach; endif; ?>
    </div>
</div>

<script>
function toggleAll(source) {
    const checkboxes = document.getElementsByName('emails[]');
    for(let i=0; i < checkboxes.length; i++) {
        checkboxes[i].checked = source.checked;
    }
}
</script>

<?php include 'views/layout/footer.php'; ?>