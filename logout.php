<?php
    session_start();
    unset($_SESSION['email']);
    session_destroy();
    header("Location: index.php?successfully_logout");
    die();
?>  