<?php
require 'includes/config.php';
require 'includes/classes/GetDataFromDB.php';

if (!isset($_SESSION['email'])) {
    header("Location: 404error.php?Please_Login_First");
}

$getDataFromDB = new GetDataFromDB();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title><?php echo $getDataFromDB->getFullName($con); ?> - Profile on <?php echo $getDataFromDB->getSiteName($con); ?></title>
    <!-- Required meta tags -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Web Icon -->
    <link rel="icon" href="assets/images/computer.png">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/96a76b11be.js" crossorigin="anonymous"></script>
    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/loader.css">
    <link rel="stylesheet" href="assets/css/profile.css">
</head>

<body>

    <?php require "includes/loader.php"; ?>

    <nav class="navbar navbar-light bg-light mx-auto w-auto justify-content-center">
        <a class="navbar-brand" href="index.php"><img src="assets/images/computer.png" alt="logo"> <?php echo $getDataFromDB->getSiteName($con); ?></a>
    </nav>
    <br><br>
    <div class="container">
        <div class="row">
            <a href="profile.php" class="col-md-12 col-sm-6 text-center name"><?php echo $getDataFromDB->getFullName($con); ?></a>
            <span class="dateJoined col-md-12 col-sm-6 text-center">Your Account Was Created: <br>
                <?php echo $getDataFromDB->getDateJoined($con); ?><br><br><br></span>
            <br><br><br><br>
            <div class="information d-flex justify-content-center col-lg-12 col-md-12 col-sm-12">
                <div class="yourPosts col-lg-5 col-md-12 col-sm-12 info">
                    <h2>All Posts You Have:</h2>
                    <br>
                    <h5 class="num text-center">
                        <?php
                        echo $getDataFromDB->hasPosts($con);
                        ?>
                    </h5>
                </div>

            </div>
            <br><br><br>
            <?php
            if (isset($_SESSION['email'])) {
                $email = $_SESSION['email'];

                $isAdmin_query = $con->prepare("SELECT `isAdmin` FROM `users` WHERE `email`=:em");
                $isAdmin_query->bindParam(":em", $email);
                $isAdmin_query->execute();
                while ($admin = $isAdmin_query->fetch(PDO::FETCH_ASSOC)) {
                    if ($admin['isAdmin'] == "yes") {
                        echo "
                            <div class='post_new col-md-12 col-sm-12 text-center'>
                                <br><br>
                                <a href='post.php' class='btn btnPost'>New Post</a>
                                <a href='logout.php' class='btn btnPost' id='BtnPost'>Logout</a>
                                <br><br>
                            </div>
                            <br><br>
                            ";
                    } else {
                        echo "
                            <div class='logout  col-md-12 col-sm-12 text-center'>
                                <br><br>
                                <a href='work.php' class='btn btnPost'>Work With Us!</a>
                                <a href='logout.php' class='btn btnPost' id='BtnPost'>Logout</a>
                                <br><br>
                            </div>
                            ";
                    }
                }
            }
            ?>

        </div>
    </div>

    <script src="assets/js/loader.js"></script>
</body>

</html>