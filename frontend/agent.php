<?php
    session_start();
    require '../backend/config.php';

    $stmt = $conn->prepare("SELECT * FROM service");
    $stmt->execute();
    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ACCUEIL_PHKB</title>
    <link rel="stylesheet" href="styles/guichet.css">
    <link rel="stylesheet" href="styles/form1.css">
</head>
<body>

    <header class="header">
            <div class="left">
                Gestion du Péage - Admin
            </div>
            <div class="right">
                <button type="submit" name="disconnected" class="disconnected"><span>&LeftArrow;</span> Rétourner au Dashboard</button>
                <button><img src="assets/icon/notification.png" alt="notifs-icon"></button>
                <button><img src="assets/icon/profile.png" alt="profil-icon"></button>
            </div>
    </header>

    <div class="container">
        <div class="logo">&check;</div>
        <div class="text">
            <h2>ENREGISTREZ UN NOUVEAU AGENT</h2>
            <P>Connectez-vous à votre App de Gestion</P>
        </div>
        <form action="../backend/ag-register.php" method="post">
            <div class="label">
                <label for="nomAg">Nom</label>
                <input type="text" id="nomAg" name="nomAg" placeholder="Nom">
            </div>
            <div class="label">
                <label for="pnomAg">Prénom</label>
                <input type="text" id="pnomAg" name="pnomAg" placeholder="Prenom">
            </div>
            <div class="label">
                <label for="codeAg">Code Agent</label>
                <input type="text" id="codeAg" name="codeAg" placeholder="CodeAgent">
            </div>
            <div class="label">
                <label for="dtnaisAg">Date de naissance</label>
                <input type="date" id="dtnaisAg" name="dtnaisAg" placeholder="Date de naissance">
            </div>
            <div class="label">
                <label for="adressAg">Adresse</label>
                <input type="text" id="adressAg" name="adressAg" placeholder="Adresse">
            </div>
            <div class="label">
                <label for="contAg">Contact</label>
                <input type="text" id="contAg" name="contAg" placeholder="Contact">
            </div>
            <div class="label">
                <label for="sexeAg">Sexe</label>
                <select name="sexeAg" id="sexeAg">
                    <option value=""></option>
                    <option value="M">M</option>
                    <option value="F">F</option>
                </select>
            </div>
            <div class="label">
                <label for="libServ">Service</label>
                <select name="libServ" id="libServ">
                    <option value=""></option>
                    <?php
                        foreach($res as $serv){
                            echo "<option value='".$serv['libServ']."'>".$serv['libServ']."</option>";
                        }
                    ?>
                </select>
            </div>
            <input type="submit" class="input" name="submit" value="Soumettre">
        </form>
    </div>

    <script>
        const btnDisconnected = document.querySelector('.disconnected');
        btnDisconnected.addEventListener('click', () => {
            window.location.href = 'dashboard.html';
        });
    </script>
</body>
</html>