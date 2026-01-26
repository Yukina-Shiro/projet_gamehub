<?php include 'views/layout/header.php'; ?>

<div class="auth-container">
    <h1>Connexion</h1>

    <?php if(isset($error)): ?>
        <div class="alert"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post" action="index.php?controller=Auth&action=login">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" placeholder="votre@email.com" required>

        <label for="mdp">Mot de passe</label>
        <input type="password" name="mdp" id="mdp" placeholder="Votre mot de passe" required>

        <button type="submit">Se connecter</button>
    </form>

    <p style="margin-top: 15px; text-align: center;">
        Pas encore de compte ? <a href="index.php?controller=Auth&action=register">Inscrivez-vous ici</a>
    </p>
    <p style="margin-top: 15px; text-align: center;">
        Mot de passe oublié ? <a href="index.php?controller=Auth&action=findPass">Réinitialisez-le ici</a>
    </p>
</div>

<?php include 'views/layout/footer.php'; ?>