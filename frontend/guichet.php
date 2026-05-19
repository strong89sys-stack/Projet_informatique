<?php
    session_start();
    require '../backend/config.php';

    $immat = $_POST['immatriculation'] ?? null;
    $marque = $_POST['marque'] ?? null;
    $category = $_POST['category'] ?? null;
    $date = $_POST['date'] ?? null;
    $payment_mode = $_POST['payment-mode'] ?? null;
    $txt_arrea = $_POST['message'] ?? null;
    $valider = $_POST['valider'] ?? null;
    $imprimer = $_POST['imprimer'] ?? null;

    $message = null;
    $amount = null;

    if ($_SESSION["state"] != "connecté") {
        header("location:guichet-login.html");
        exit();
    }

    if (isset($_POST['disconnected'])){
        session_destroy();
        header("location:index.html");
        exit();
    }

    $stmt = $conn->prepare("select count(idPaie) from paiement where idGui = :idGui");
    $stmt->bindParam(':idGui', $_SESSION['idGui'], PDO::PARAM_INT);
    $stmt->execute();
    $res_paie = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $conn->prepare("select sum(mtPaie) from paiement where idGui = :idGui");
    $stmt->bindParam(":idGui", $_SESSION['idGui'], PDO::PARAM_INT);
    $stmt->execute();
    $res_mt = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$res_mt){
        $res_mt = [0];
    }

    if (isset($valider)) {
        if (empty($immat) || empty($marque) || empty($category) || empty($date) || empty($payment_mode)) {
            $message = 'Veuillez remplir tous les champs obligatoires.';
        } else {
            if ($category === 'Classe 1 (Léger)') {
                $amount = 500;
            } elseif ($category === 'Classe 2 (Intermédiaire)') {
                $amount = 1000;
            } elseif ($category === 'Classe 3 (Poids lourds 2 essieux)') {
                $amount = 1500;
            } elseif ($category === 'Classe 4 (Poids lourds 3+ essieux)') {
                $amount = 3000;
            }

            $_SESSION['immat'] = $immat;
            $_SESSION['marque'] = $marque;
            $_SESSION['amount'] = $amount;

            $stmt = $conn->prepare("SELECT * FROM marque WHERE libMarq = :marque LIMIT 1");
            $stmt->bindParam(':marque', $marque, PDO::PARAM_STR);
            $stmt->execute();
            $res = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($res) {
                $stmt = $conn->prepare("SELECT * FROM categorie WHERE libCat = :category LIMIT 1");
                $stmt->bindParam(':category', $category, PDO::PARAM_STR);
                $stmt->execute();
                $res1 = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($res1) {
                    $stmt = $conn->prepare("SELECT * FROM vehicule WHERE immatVeh = :immatVeh LIMIT 1");
                    $stmt->bindParam(':immatVeh', $immat, PDO::PARAM_STR);
                    $stmt->execute();
                    $vehicule = $stmt->fetch(PDO::FETCH_ASSOC);

                    if (!$vehicule) {
                        $stmt = $conn->prepare("INSERT INTO vehicule (immatVeh, idMarq, idCat) VALUES (:immatVeh, :idMarq, :idCat)");
                        $stmt->bindParam(':immatVeh', $immat, PDO::PARAM_STR);
                        $stmt->bindParam(':idMarq', $res['idMarq'], PDO::PARAM_INT);
                        $stmt->bindParam(':idCat', $res1['idCat'], PDO::PARAM_INT);

                        if ($stmt->execute()) {
                            $stmt = $conn->prepare("SELECT * FROM vehicule WHERE immatVeh = :immatVeh LIMIT 1");
                            $stmt->bindParam(':immatVeh', $immat, PDO::PARAM_STR);
                            $stmt->execute();
                            $vehicule_res = $stmt->fetch(PDO::FETCH_ASSOC);
                            $vehiculeId = $vehicule_res['idVeh'];
                        } else {
                            $message = 'Erreur lors de l\'insertion du véhicule.';
                            $vehiculeId = null;
                        }
                    } else {
                        $vehiculeId = $vehicule['idVeh'];
                    }

                    if ($vehiculeId) {
                        $stmt = $conn->prepare("SELECT * FROM nature_paiement WHERE libNatPaie = :payment_mode LIMIT 1");
                        $stmt->bindParam(':payment_mode', $payment_mode, PDO::PARAM_STR);
                        $stmt->execute();
                        $natPaie = $stmt->fetch(PDO::FETCH_ASSOC);

                        if (!$natPaie) {
                            $message = 'Mode de paiement non trouvé.';
                        } else {
                            $stmt = $conn->prepare("INSERT INTO paiement (dtPaie, mtPaie, idNatPaie, idVeh, idGui) VALUES (:dtPaie, :mtPaie, :idNatPaie, :idVeh, :idGui)");
                            $stmt->bindParam(':dtPaie', $date, PDO::PARAM_STR);
                            $stmt->bindParam(':mtPaie', $amount, PDO::PARAM_INT);
                            $stmt->bindParam(':idNatPaie', $natPaie['idNatPaie'], PDO::PARAM_INT);
                            $stmt->bindParam(':idVeh', $vehiculeId, PDO::PARAM_INT);
                            $stmt->bindParam(':idGui', $_SESSION['idGui'], PDO::PARAM_INT);
                            
                            if ($stmt->execute()) {
                                $dte = date('Y-m-d', time());
                                $stmt = $conn->prepare("INSERT INTO intervention (dtInterv, libInterv, dte, idAge, idVeh, idGui) VALUES (:dtInterv, 'Encaissement', :dte, :idAge, :idVeh, :idGui)");
                                $stmt->bindParam(':dtInterv', $date, PDO::PARAM_STR);
                                $stmt->bindParam(':dte', $dte, PDO::PARAM_STR);
                                $stmt->bindParam(':idAge', $_SESSION['idAge'], PDO::PARAM_INT);
                                $stmt->bindParam(':idVeh', $vehiculeId, PDO::PARAM_INT);
                                $stmt->bindParam(':idGui', $_SESSION['idGui'], PDO::PARAM_INT);

                                if ($stmt->execute()) {
                                    $message = 'Encaissement enregistré avec succès.';
                                    sleep(3);
                                    header("location:guichet.php?imprimer=true");
                                    exit();
                                } else {
                                    $message = 'Impossible d\'enregistrer l\'intervention.';
                                }
                            } else {
                                $message = 'Erreur lors de l\'insertion du paiement.';
                            }
                        }
                    }
                }
            }
        }    
        header("Refresh:0");
        exit();
    }

    /*if (isset($imprimer)) {
        // Logique d'impression du reçu
        header("location:guichet.php?imprimer=true");
        exit();
    }*/

    if(@$_GET['disconnected'] == true){
        $stmt = $conn->prepare("UPDATE agent
            SET statutAge = null
            WHERE idAge = :idAge");
        $stmt->bindParam(':idAge', $_SESSION['idAge'], PDO::PARAM_INT);
        $stmt->execute();

        $stmt = $conn->prepare("UPDATE guichet
            SET statutGui = null
            WHERE idGui = :idGui");
        $stmt->bindParam(':idGui', $_SESSION['idGui'], PDO::PARAM_INT);
        $stmt->execute();

        session_destroy();
        header("location:guichet-login.php");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GUICHET</title>
    <link rel="stylesheet" href="./styles/guichet.css">
</head>
<body>
    <div class="container">
        <header class="header">
            <div class="left">
                Gestion des Encaissements - Guichet
            </div>
            <div class="right">
                <button name="disconnected" id="disconnection-btn"><span>&LeftArrow;</span> Se déconnecter</button>
                <button><img src="assets/icon/notification.png" alt="notifs-icon"></button>
                <button><img src="assets/icon/profile.png" alt="profil-icon"></button>
            </div>
        </header>

        <div class="main">
            <div class="left">
                <?php if (!empty($message)){
                    echo "<div class='message'>
                        <div>
                            <p><span>&check;</span> $message</p>
                            <button id='close_btn'>x</button>
                        </div>
                    </div>";
                }
                ?>
                <div class="box">
                    <form method="post">
                        <!-- Vehicle Plate -->
                        <div class="col1 col">
                            <label>Immatriculation</label>
                            <input name="immatriculation" placeholder="ABC-1234" type="text" required/>
                        </div>
                        
                        <!-- Brand -->
                        <div class="col2 col">
                            <label>Marque</label>
                            <select name="marque" required>
                                <?php 
                                for($i = 1; $i <= 12; $i++){
                                    $stmt = $conn->prepare("
                                        SELECT *
                                        FROM marque
                                        WHERE idMarq = :i
                                        LIMIT 1;
                                    ");
                                    $stmt->bindParam(':i', $i, PDO::PARAM_INT);
                                    $stmt->execute();
                                    $marq = $stmt->fetch(PDO::FETCH_ASSOC);

                                    echo '<option value= "'.$marq['libMarq'].'">'.$marq['libMarq'].'</option>';
                                } 
                                ?>
                            </select>
                        </div>

                        <!-- Category -->
                        <div class="col1 col">
                            <label>Categorie</label>
                            <select name="category" id="category" required>
                                <option value="">---</option>
                                <?php 
                                for($i = 1; $i <= 4; $i++){
                                    $stmt = $conn->prepare("
                                        SELECT *
                                        FROM categorie
                                        WHERE idCat = :i
                                        LIMIT 1;
                                    ");
                                    $stmt->bindParam(':i', $i, PDO::PARAM_INT);
                                    $stmt->execute();
                                    $cat = $stmt->fetch(PDO::FETCH_ASSOC);

                                    echo '<option id = "Cat_'.$i.'" value= "'.$cat['libCat'].'">'.$cat['libCat'].'</option>';
                                } 
                                ?>
                            </select>
                        </div>
                        <!-- Date and Time -->
                        <div class="col2 col">
                            <label>Date et heure</label>
                            <input name="date" required type="datetime-local"/>
                        </div>

                        <!-- Amount Paid -->
                        <div class="col1 col">
                            <label>Montant à payer</label>
                            <div class="amount-box">
                                <span>FCFA</span>
                                <input class="amount" id="amount" placeholder="0" type="text" disabled="true"/>
                            </div>
                        </div>

                        <!-- Payment Type -->
                        <div class="col2 col">
                            <label>Mode de Paiement</label>
                            <select name="payment-mode" required>
                                <?php 
                                for($i = 1; $i <= 3; $i++){
                                    $stmt = $conn->prepare("
                                        SELECT *
                                        FROM nature_paiement
                                        WHERE idNatPaie = :i
                                        LIMIT 1;
                                    ");
                                    $stmt->bindParam(':i', $i, PDO::PARAM_INT);
                                    $stmt->execute();
                                    $natPaie = $stmt->fetch(PDO::FETCH_ASSOC);

                                    echo '<option value= "'.$natPaie['libNatPaie'].'">'.$natPaie['libNatPaie'].'</option>';
                                } 
                                ?>
                            </select>
                        </div>

                        <!-- Booth/Guichet -->
                        <div class="col1 col">
                            <label>Guichet</label>
                            <input type="text" name="guichet" disabled="true" value="
                                <?= $_SESSION["Guichet"] ?>
                            ">
                        </div>

                        <!-- Agent -->
                        <div class="col2 col">
                            <label>Agent</label>
                            <input type="text" name="agent" disabled="true" value="
                                <?= $_SESSION["Agent"] ?>
                            ">
                        </div>

                        <!-- Observations -->
                        <div class="col last-row">
                            <label>Observations</label>
                            <textarea placeholder="Saisir d'éventuelles remarques..." rows="5" name="message"></textarea>
                        </div>

                        <!-- Action Buttons -->
                        <div class="btn-row">
                            <button type="submit" class="first wide" name="valider">
                                <span data-icon="check">&check;</span> Valider l’encaissement
                            </button>
                            <button type="button" class="second wide" id="print-btn">
                                <img src="./assets/icon/printer.png" alt="printer-icon" height="16px">
                                Imprimer reçu
                            </button>
                            <button type="reset" class="last short" id="reset_btn">
                                <img src="./assets/icon/close.png" alt="close-icon" height="16px"> Annuler
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="right">
                <div class="top">
                    <h3>SESSION EN COURS</h3>
                    <div class="content">
                        <div class="txt">
                            <p>Passages</p>
                            <?php
                                echo "<p>".implode($res_paie)."</p>";
                            ?>
                        </div>
                        <div class="txt">
                            <p>Total (FCFA)</p>
                            <?php
                                echo "<p>".implode($res_mt)."</p>";
                            ?>
                        </div>
                    </div>
                </div>
                <div class="mid">
                    <div class="img">
                        <div class="img-cam">
                            <video id="camera" autoplay></video>
                            <button id="capture">Capturer</button>
                            <canvas id="snapshot"></canvas>
                        </div>
                        <div class="img-txt">
                            <p>
                                <span>ℹ</span>
                                Surveillance en temps réel
                            </p>
                            <p>Visualisation du portique Nord-Est opérationnel.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        const amount = document.getElementById('amount');
        const select = document.getElementById('category');

        function updateAmount(value) {
            let prix = '';
            if (value === 'Classe 1 (Léger)') prix = 500;
            else if (value === 'Classe 2 (Intermédiaire)') prix = 1000;
            else if (value === 'Classe 3 (Poids lourds 2 essieux)') prix = 1500;
            else if (value === 'Classe 4 (Poids lourds 3+ essieux)') prix = 3000;
            amount.value = prix;
        }

        select.addEventListener('change', () => updateAmount(select.value));
        updateAmount(select.value);
    </script>
    <script>
        const video = document.getElementById("camera");
        const canvas = document.getElementById("snapshot");
        const captureBtn = document.getElementById("capture");

        // Demande accès à la caméra
        navigator.mediaDevices.getUserMedia({ video: true })
        .then(stream => {
            video.srcObject = stream;
        })
        .catch(err => {
            console.error("Erreur caméra :", err);
        });

        // Capture une image
        captureBtn.addEventListener("click", () => {
        const ctx = canvas.getContext("2d");
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

        // Convertir en base64 pour envoi au serveur
        const imageData = canvas.toDataURL("image/png");
        console.log(imageData);
        });
    </script>
    <script>
        const disconnected = document.getElementById('disconnection-btn');

        disconnected.addEventListener('click', () => {
            if (confirm("Êtes-vous sûr de vouloir vous déconnecter ?")) {
                window.location.href = "guichet.php?disconnected=true";
            }
        });
    </script>
    <script>
        const printBtn = document.getElementById('print-btn');
        printBtn.addEventListener('click', () => {
            window.location.reload();
        });
        if (window.location.search.includes('imprimer=true')) {
            window.onload = () => {
                window.open('./printPage/reçu.php', '_blank');
            };
        }
    </script>
    <script>
        const resetBtn = document.getElementById('reset_btn');
        resetBtn.addEventListener('click', () => {
            if (confirm("Êtes-vous sûr de vouloir annuler cette opération ?")) {
                window.location.href = "guichet.php";
            }
        });
    </script>
</body>
</html>