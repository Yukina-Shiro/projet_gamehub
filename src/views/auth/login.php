<!DOCTYPE html>
<html>
<head><title>Connexion GameHub</title></head>
<body>
    <h1>Connexion</h1>
    <?php if(isset($error)) echo "<p style='color:red'>$error</p>"; ?>
    <form method="post">
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="password" name="mdp" placeholder="Mot de passe" required><br>
        <button type="submit">Se connecter</button>
    </form>
</body>
</html>