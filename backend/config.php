<?php
    $host = "localhost";
    $user = "root";
    $passwd = "";
    $dbname = "p_hkb";

    try{
        
        $conn = new PDO(
            "mysql:host=$host;dbname=$dbname;charset=utf8",
            $user,
            $passwd
        );

        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    }
    catch(PDOException $e){
        echo "Erreur de connexion : ". $e->getMessage();
    }

    // ---------- Preparer requête ----------
    //$stmt = $conn->prepare("SELECT id, username FROM utilisateur WHERE username = :username LIMIT 1");

    // ---------- Lier ---------------
    //$stmt->bindParam(':username', $username, PDO::PARAM_STR);

    /* Executer et Récupérer
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            echo "Utilisateur trouvé : " . $user['username'];
        } else {
            echo "Utilisateur introuvable.";
        }
  
*/
?>