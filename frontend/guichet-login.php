<?php
    session_start();
    require '../backend/config.php';

    $stmt = $conn->prepare("SELECT * FROM guichet");
    $stmt->execute();
    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guichet</title>
    <link rel="stylesheet" href="styles/guichet.css">
    <link rel="stylesheet" href="styles/form.css">
    <link rel="stylesheet" href="styles/form1.css">
</head>
<body>

    <header class="header" style="width: 100vw; position: absolute; top: 0;">
            <div class="left">
                Gestion du Péage - Agent
            </div>
            <div class="right">
                <a href="index.html"><span>&LeftArrow;</span> Retourner à l'accueil</a>
            </div>
    </header>

    <div class="container" style="display: flex; flex-direction: column; align-items: center; justify-content: center;">
        <div class="text">
            <h2>CONNECTEZ-VOUS A VOTRE GUICHET</h2>
            <P>Connectez-vous à votre App de Gestion</P>
        </div>  
        <form method="post" action="../backend/guichet-login.php">
            <div class="label">
                <label for="codeAgent">Code Agent</label>
                <input type="text" name="codeAgent" placeholder="Code agent">
            </div>
            <div class="label">
                <label for="libGui">Guichet</label>
                <select name="libGui" id="libGui">
                    <option value=""></option>
                    <?php
                        foreach($res as $guichet){
                            echo "<option value='".$guichet['libGui']."'>".$guichet['libGui']."</option>";
                        }
                    ?>
                </select>
            </div>
            <input type="submit" class="input" value="Soumettre" name="submit">
        </form>
    </div>
</body>
</html>