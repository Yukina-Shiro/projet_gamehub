<?php include 'views/layout/header.php'; ?>

<div class="auth-container">
    <h2>Créer un compte GameHub</h2>

    <?php if(isset($error)): ?>
        <div class="alert"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post" action="index.php?controller=Auth&action=register">
        <label for="pseudo">Pseudo</label>
        <input type="text" name="pseudo" id="pseudo" placeholder="Ex: Gamer75" required>

        <label for="nom">Nom</label>
        <input type="text" name="nom" id="nom" placeholder="Votre nom" required>

        <label for="prenom">Prénom</label>
        <input type="text" name="prenom" id="prenom" placeholder="Votre prénom" required>

        <label for="telephone">Téléphone</label>
        <input type="number" name="telephone" id="telephone" placeholder="Votre numéro de téléphone" required  maxlength="10" minlength="10">

        <label for="email">Email</label>
        <input type="email" name="email" id="email" placeholder="nom@exemple.com" required>

        <label for="mdp">Mot de passe</label>
        <input type="password" name="mdp" id="mdp" placeholder="**" required>

        <label for="date_naissance">Date de naissance</label>
        <input type="date" name="date_naissance" id="date_naissance" required>

        <button type="submit">S'inscrire</button>
    </form>

    <p style="margin-top: 15px; text-align: center;">
        Déjà inscrit ? <a href="index.php?controller=Auth&action=login">Connectez-vous ici</a>
    </p>
</div>

<?php include 'views/layout/footer.php'; ?>