<?php
    require "config.php";
    
    if (isset($_POST['submit'])){
        $nom = $_POST['nom'];
        $pnom = $_POST['pnom'];
        $code = $_POST['code'];
    
    if (!empty($nom) && !empty($pnom) && !empty($code)){

            $hash = password_hash($code, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("
                INSERT INTO admin (nomAdmin, prenomAdmin, codeAdmin) VALUES
                (:nomAdmin, :prenomAdmin, :codeAdmin);
            ");

            $stmt->bindParam(':nomAdmin', $nom, PDO::PARAM_STR);
            $stmt->bindParam(':prenomAdmin', $pnom, PDO::PARAM_STR);
            $stmt->bindParam(':codeAdmin', $hash, PDO::PARAM_STR);

            try{
                if($stmt->execute()){
                    header("location:./../frontend/admin-login.php");
                    exit();
                }
                else{
                    echo "Echec de l'insertion !!!";
                }
            }
            catch(PDOException $e){
                echo "Erreur d'envoie : ".$e->getMessage();
            }
        }
        else{
            echo "Remplissez tout les champs !!!!";
        }
    }
?>