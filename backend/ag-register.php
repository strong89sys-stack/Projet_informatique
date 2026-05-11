<?php
    session_start();
    require 'config.php';

    $nomAg = $_POST['nomAg'] ?? null;
    $pnomAg = $_POST['pnomAg'] ?? null;
    $codeAg = $_POST['codeAg'] ?? null;
    $dtnaisAg = $_POST['dtnaisAg'] ?? null;
    $adressAg = $_POST['adressAg'] ?? null;
    $contAg = $_POST['contAg'] ?? null;
    $sexeAg = $_POST['sexeAg'] ?? null;
    $serv = $_POST['libServ'] ?? null;

    if (isset($_POST['submit'])){
        if (!empty($nomAg) && !empty($pnomAg) && !empty($codeAg) && !empty($dtnaisAg) && !empty($adressAg) && !empty($contAg) && !empty($sexeAg) && !empty($serv)){

            $stmt = $conn->prepare("
                SELECT *
                FROM service
                WHERE libServ = :serv
                LIMIT 1;
            ");
            $stmt->bindParam(':serv', $serv, PDO::PARAM_STR);
            $stmt->execute();

            $res = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($res){
                $idServ = $res['idServ'];
                
                
                $stmt = $conn->prepare("
                    INSERT INTO agent (numAge, nomAge, prenomAge, dtnaisAge, adressAge, contAge, sexAge, idServ) VALUES
                    (:numAge, :nomAge, :prenomAge, :dtnaisAge, :adressAge, :contAge, :sexAge, :idServ);
                ");
                $stmt->bindParam(':numAge', $codeAg, PDO::PARAM_STR);
                $stmt->bindParam(':nomAge', $nomAg, PDO::PARAM_STR);
                $stmt->bindParam(':prenomAge', $pnomAg, PDO::PARAM_STR);
                $stmt->bindParam(':dtnaisAge', $dtnaisAg, PDO::PARAM_STR);
                $stmt->bindParam(':adressAge', $adressAg, PDO::PARAM_STR);
                $stmt->bindParam(':contAge', $contAg, PDO::PARAM_STR);
                $stmt->bindParam(':sexAge', $sexeAg, PDO::PARAM_STR);
                $stmt->bindParam(':idServ', $idServ, PDO::PARAM_INT);

                if($stmt->execute()){
                    header("location:../frontend/dashboard.html?result=success");
                    exit();
                }
                else{
                    header("location:../frontend/agent.html?result=error");
                    exit();
                }
            }
        }
    }
?>