<?php include 'views/layout/header.php'; ?>

<div class="container">
    <h1>Administration GameHub</h1>

    <section style="margin-bottom: 30px; padding: 15px; background: var(--secondary-bg); border-radius: 8px;">
        <h3>Visualiser les membres par date</h3>
        <form method="get" action="index.php" style="display: flex; gap: 10px;">
            <input type="hidden" name="controller" value="Admin">
            <input type="hidden" name="action" value="index">
            <input type="date" name="filter_date" value="<?= $filter_date ?? '' ?>" style="margin:0;">
            <button type="submit" style="width: auto;">Filtrer</button>
            <a href="index.php?controller=Admin&action=index" style="padding:10px;">Réinitialiser</a>
        </form>
    </section>

    <section>
        <h3>Membres inscrits</h3>
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 30px;">
            <tr style="background: var(--brand-color); color: white; text-align: left;">
                <th style="padding: 10px;">Pseudo</th>
                <th>Email</th>
                <th>Date Inscription</th>
                <th>Actions</th>
            </tr>
            <?php foreach($users as $u): ?>
                <tr style="border-bottom: 1px solid var(--border-color);">
                    <td style="padding: 10px;"><?= htmlspecialchars($u['pseudo']) ?></td>
                    <td><?= htmlspecialchars($u['email']) ?></td>
                    <td><?= date('d/m/Y', strtotime($u['date_creation'])) ?></td>
                    <td>
                        <button onclick="openMailModal('<?= $u['email'] ?>')" style="width:auto; background:#17a2b8; padding:5px 10px;">Email</button>
                        <a href="index.php?controller=Admin&action=banUser&id=<?= $u['id_utilisateur'] ?>"
                           style="color: var(--danger); margin-left:10px;"
                           onclick="return confirm('Supprimer ce membre ?');">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </section>

    <section>
        <h3>Modération des Publications</h3>
        <?php foreach($posts as $p): ?>
            <div class="post-card" style="padding: 10px; display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <strong>@<?= htmlspecialchars($p['pseudo']) ?></strong>: <?= htmlspecialchars($p['titre']) ?>
                    <br><small><?= htmlspecialchars(substr($p['description'], 0, 50)) ?>...</small>
                </div>
                <a href="index.php?controller=Admin&action=deletePost&id=<?= $p['id_post'] ?>"
                   style="color: var(--danger); font-weight:bold;"
                   onclick="return confirm('Supprimer définitivement ce contenu ?');">Bloquer / Supprimer</a>
            </div>
        <?php endforeach; ?>
    </section>
</div>

<div id="mailModal" class="modal-overlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:2000; justify-content:center; align-items:center;">
    <div class="modal-content">
        <h3>Envoyer un avertissement / information</h3>
        <form action="index.php?controller=Admin&action=sendMail" method="post">
            <input type="hidden" name="email" id="mailDest">
            <input type="text" name="subject" placeholder="Sujet" required>
            <textarea name="message" rows="5" placeholder="Votre message..." required></textarea>
            <button type="submit">Envoyer le mail</button>
        </form>
        <button onclick="document.getElementById('mailModal').style.display='none'" class="btn-cancel-modal">Annuler</button>
    </div>
</div>

<script>
function openMailModal(email) {
    document.getElementById('mailDest').value = email;
    document.getElementById('mailModal').style.display = 'flex';
}
</script>

<?php include 'views/layout/footer.php'; ?>