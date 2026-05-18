<?php
    session_start();
    require 'config.php';
    $codeAgent = $_POST['codeAgent'] ?? null;
    $libGui = $_POST['libGui'] ?? null;

    if (isset($_POST['submit'])){
        if (!empty($codeAgent) && !empty($libGui)){

            $stmt = $conn->prepare("
                SELECT *
                FROM agent
                WHERE numAge = :codeAgent
                LIMIT 1;
            ");
            $stmt->bindParam(':codeAgent', $codeAgent, PDO::PARAM_STR);
            $stmt->execute();
            $res = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($res){

                if ($res['statutAge'] == 1){
                    header("location:../frontend/guichet-login.html?error=connected");
                    exit();
                }elseif ($res['idServ'] != 1){
                    header("location:../frontend/guichet-login.html?error=service");
                    exit();
                }

                $stmt = $conn->prepare("
                    SELECT *
                    FROM guichet
                    WHERE libGui = :libGui
                    LIMIT 1;
                ");
                $stmt->bindParam(':libGui', $libGui, PDO::PARAM_STR);
                $stmt->execute();
                $res1 = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($res1){

                    if ($res1['statutGui'] == 1){
                        header("location:../frontend/guichet-login.html?error=occupied");
                        exit();
                    }

                    $stmt = $conn->prepare("
                        INSERT INTO intervention (dtInterv, libInterv, dte, idAge, idveh, idGui) VALUES
                        (:dtInterv, 'Connexion Caisse', :dte, :idAge, null, :idGui)
                    ");

                    $dtInterv = date("Y-m-d H:i:s", time());
                    $date = date("Y-m-d", time());

                    $stmt->bindParam(':dtInterv', $dtInterv, PDO::PARAM_STR);
                    $stmt->bindParam(':dte', $date, PDO::PARAM_STR);
                    $stmt->bindParam(':idAge', $res['idAge'], PDO::PARAM_INT);
                    $stmt->bindParam(':idGui', $res1['idGui'], PDO::PARAM_INT);

                    if ($stmt->execute()){
                        $stmt = $conn->prepare("UPDATE agent
                            SET statutAge = 1
                            WHERE idAge = :idAge");
                        $stmt->bindParam(':idAge', $res['idAge'], PDO::PARAM_INT);
                        $stmt->execute();

                        $stmt = $conn->prepare("UPDATE guichet
                            SET statutGui = 1
                            WHERE idGui = :idGui");
                        $stmt->bindParam(':idGui', $res1['idGui'], PDO::PARAM_INT);
                        $stmt->execute();

                        $_SESSION["Agent"] = $res['nomAge'];
                        $_SESSION['idAge'] = $res['idAge'];
                        $_SESSION['idServ'] = $res['idServ'];
                        $_SESSION['idGui'] = $res1['idGui'];
                        $_SESSION["Guichet"] = $res1['libGui'];
                        $_SESSION["state"] = "connecté";

                        header("location:../frontend/guichet.php");
                        exit();
                    }
                    else{
                        header("location:../frontend/guichet-login.html?error=db");
                        exit();
                    }
                }
                else{
                    header("location:../frontend/guichet-login.html?error=guichet");
                    exit();
                }
            }
            else{
                header("location:../frontend/guichet-login.html?error=invalid");
                exit();
            }
        }
        else{
            header("location:../frontend/guichet-login.html?error=empty");
            exit();
        }
    }
    else{
        header("location:../frontend/guichet-login.html");
        exit();
    }
?>