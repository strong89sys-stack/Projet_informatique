<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ACCUEIL_PHKB</title>
    <link rel="stylesheet" href="styles/form.css">
</head>
<body>
    <div class="container">
        <div>
            <h2>AUTHENTIFICATION</h2>
            <P>Connectez-vous à votre App de Gestion</P>
        </div>
        <form method="post" action="../backend/admin-login.php">
            <div class="label">
                <label for="nom">Nom</label>
            
                <input type="text" id="nom" name="username" placeholder="Nom">
            </div>
            <div class="label">
                <label for="code">Code</label>
                <input type="password" id="code" name="code" placeholder="Code">
            </div>
            <input type="submit" class="input" value="Soumettre" name="submit">
        </form>
    </div>

    <?php if (isset($_GET['error'])): ?>
        <p style="color:red;">
            <?php
                if ($_GET['error'] === 'invalid') echo "Nom ou code incorrect !!!";
                if ($_GET['error'] === 'empty') echo "Remplissez tous les champs !!!";
            ?>
        </p>
    <?php endif; ?>
</body>
</html>