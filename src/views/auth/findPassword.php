<?php include 'views/layout/header.php'; ?>

<div class="auth-container">
    <h1>Mot de passe oublié</h1>

    <?php if(isset($error)): ?>
        <div class="alert"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post" action="index.php?controller=Auth&action=FindPass">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" placeholder="votre@email.com" required>

        <button type="submit">Envoyer le mot de passe
        </button>
    </form>
</div>

<?php include 'views/layout/footer.php'; ?>