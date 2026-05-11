<?php
    session_start();
    require '../backend/config.php';

    session_destroy();
    sleep(3);
    header("location:admin-login.php");
    exit();
?>