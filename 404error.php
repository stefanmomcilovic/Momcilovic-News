<?php
    require "includes/config.php";
    require 'includes/classes/GetDataFromDB.php';
    $GetDataFromDB = new GetDataFromDB;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>404 Error Page - <?php echo $GetDataFromDB->getSiteName($con); ?></title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Web Icon -->
    <link rel="icon" href="assets/images/computer.png">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/96a76b11be.js" crossorigin="anonymous"></script>
    <!-- CSS -->
    <style>
        img{
            width: 40%;
        }
        
        a{
            text-align: center;
            font-size: 52px;
            color: #6a6a6a;
        }
        
        a:hover{
            color: #414040;
            text-decoration: none;
            cursor: pointer;
        }
        
        .error{
            align-items: center;
            height: 100vh;
            width: 100%;
        }
    </style>
</head>

<body>
    <div class="error d-flex mx-auto w-auto justify-content-center">
        <a href="index.php">
            <img src="assets/images/computer.png" alt="logo">
            404 Error Page Not Found
        </a>
    </div>
</body></html>