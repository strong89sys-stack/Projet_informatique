<?php
    session_start();
    require 'config.php';

    if (isset($_POST['submit'])){
        $username = $_POST['username'];
        $codeAdmin = $_POST['code'];

        if (!empty($username) && !empty($codeAdmin)){
            $stmt = $conn->prepare("
                SELECT *
                FROM admin
                WHERE nomAdmin = :nomAdmin
                LIMIT 1;
            ");

            $stmt->bindParam(':nomAdmin', $username, PDO::PARAM_STR);
            $stmt->execute();

            $res = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($res && password_verify($codeAdmin, $res['codeAdmin'])){
                $_SESSION["admin_id"] = $res["idAdmin"];
                $_SESSION["admin_name"] = $res["nomAdmin"];
                setcookie("admin_name", $res['nomAdmin'], time() + (3600 * 8), "/"); // Cookie valide pendant 08 hours

                header("location:../frontend/dashboard.php");
                exit();
            }
            else{
                header("location:../frontend/admin-login.php?error=invalid");
                exit();
            }
        }
        else{
            header("location:../frontend/admin-login.php?error=empty");
            exit();
        }
    }
?>