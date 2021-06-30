<?php
require 'includes/config.php';
require 'includes/classes/GetDataFromDB.php';
require 'includes/functions/timeago.php';
require 'includes/classes/GetPosts.php';

$GetData = new GetDataFromDB;

if (isset($_GET['go_search']) && !empty($_GET['go_search']) && $_GET['go_search'] == "Go" && isset($_GET['value_searched']) && !empty($_GET['value_searched'])) {
    $value_searched = $_GET['value_searched'];
    $search_query = $con->prepare("SELECT * FROM `posts` WHERE `title` LIKE '%$value_searched%' OR `description` LIKE '%$value_searched%'");
    $search_query->execute();
} else {
    header("Location: 404error.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Search: <?php echo $_GET['value_searched']; ?> - <?php echo $GetData->getSiteName($con); ?></title>
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
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/sidebar.css">
    <link rel="stylesheet" href="assets/css/loader.css">
    <style>
        @media screen and (max-width:768px) {
            .lsection {
                text-align: center;
            }
        }
    </style>
</head>

<body>
    <?php require "includes/loader.php"; ?>

    <div id="mySidebar" class="sidebar">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">×</a>
        <?php
        $getSidebar = $GetData->getSideBar($con);
        ?>
    </div>

    <div class="mainContainer" id="mainContainer">

        <div class="navigation">
            <div class="navbar">
                <div class="col-md-12 col-sm-12">
                    <div class="row">
                        <div class="col-md-6 col-sm-6">
                            <div class="row">
                                <div class="sandwitch" onclick="openNav()">
                                    <div class="bar1"></div>
                                    <div class="bar2"></div>
                                    <div class="bar3"></div>
                                </div>
                                <div class="HideSearch">
                                    <button class="search">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                                <form action="#" method="GET" class="searchForm">
                                    <div class="inputs hideInputs">
                                        <input type="text" placeholder="Search.." name="value_searched" class="searchValue" required autocomplete="off">
                                        <input type="submit" value="Go" name="go_search" class="goSearch">
                                    </div>
                                </form>
                            </div>
                        </div>
                        <?php
                        if (isset($_SESSION['email'])) {
                            $email = $_SESSION['email'];
                            $getFullName_query = $con->prepare("SELECT `fname`,`lname` FROM `users` WHERE `email`=:em");
                            $getFullName_query->bindParam(":em", $email);
                            $getFullName_query->execute();
                            while ($row = $getFullName_query->fetch(PDO::FETCH_ASSOC)) {
                                $fullName = $row['fname'] . " " . $row['lname'];
                                echo "
                                        <a href='profile.php' class='col-md-6 col-sm-6 text-right profile'>$fullName</a>
                                    ";
                            }

                            $isAdmin_query = $con->prepare("SELECT `isAdmin` FROM `users` WHERE `email`=:em");
                            $isAdmin_query->bindParam(":em", $email);
                            $isAdmin_query->execute();
                            while ($admin = $isAdmin_query->fetch(PDO::FETCH_ASSOC)) {
                                if ($admin['isAdmin'] == "yes") {
                                    echo "
                                        <div class='row col-sm-12 justify-content-end'>
                                            <a href='post.php' class='btn post mr-2' id='BtnPost'>Post</a>
                                            <a href='logout.php' class='btn post' id='BtnPost'>Logout</a>
                                        </div>
                                        ";
                                } else {
                                    echo "
                                            <div class='row col-sm-12 justify-content-end'>
                                                <a href='logout.php' class='btn post' id='BtnPost'>Logout</a>
                                            </div>
                                            ";
                                }
                            }
                        } else {
                        ?>
                            <div class="col-md-6 col-sm-6 text-right">
                                <a href="login.php" class="btn login" id="BtnLogin">Login</a>
                            </div>
                        <?php
                        } //End else
                        ?>
                    </div>
                </div>
            </div>
            <div class="brand d-flex justify-content-center">
                <a href="index.php" style="text-decoration: none;">
                    <h1 class="brand-txt"><?php echo $GetData->getSiteName($con); ?></h1>
                </a>
            </div>
            <br><br>
            <div class="col-md-12 col-sm-12">
                <div class="row">
                    <div class="col-md-6 col-sm-0 text-left">
                        <span id="date" class="ml-md-3 ml-sm-0" style="font-size:12px;"></span>
                    </div>
                    <div class="col-md-6 col-sm-0 text-right" id="TodayPaper">
                        <span style="font-size: 12px;">Today’s Paper</span>
                    </div>
                </div>
            </div>
            <div class="categories col-md-12 col-sm-12" id="Navmenu">
                <hr class="ml-md-3 black_hr">
                <div class="provide_categories text-center">
                    <?php
                    $getNavbar = $GetData->getNavbar($con);
                    ?>
                </div>
                <hr class="ml-md-3 dbl_hr">
                <hr class="ml-md-3 dbl_hr">
            </div>
        </div>

        <br><br>

        <?php
        if ($search_query->rowCount() > 0) {
            $result = $search_query->fetchAll();

            foreach ($result as $row) {
                $timeago = getDateTimeDiff($row['date_added']);
                echo '<div class="lsection d-md-flex">
                            <div class="col-md-3 col-sm-12">
                                <a href="article.php?id=' . $row['id'] . '">
                                    <img style="width: 450px;" class="img-fluid" src="' . $row['img'] . '" alt="Left Image Section">
                                </a>
                            </div>
                            <div class="ltext col-md-9 col-sm-12">
                                <a href="article.php?id=' . $row['id'] . '">
                                    <h1>' . $row['title'] . '</h1>
                                </a>
                                <p>' . $row['description'] . '</p>
                                <span class="ago">' . $timeago . '</span>
                            </div>
                        </div>
                        <br><br>';
            }
        }else{
            echo "<h4 class='text-center'>Searched Value Not Found..</h4>";
        }
        ?>

        <footer class="footerMenu col-md-12 pb-md-3">
            <hr class="ml-md-3 gray_hr">
            <hr class="ml-md-3 gray_hr">
            <br>
            <a href="#mainContainer" class="text-center" style="font-family: Playball-Regular; font-size: 1.3em;">&copy;<?php echo $GetData->getSiteName($con); ?></a>
            <div class="last">
                <div class="row">
                    <div class="col-md-3">
                        <a href="#mainContainer"> &copy;<span class="year"></span><?php echo $GetData->getSiteName($con); ?> Company</a>
                    </div>
                    <div class="col-md-2">
                        <a href="contact.php">Contact Us</a>
                    </div>
                    <div class="col-md-2">
                        <a href="work.php">Work with us</a>
                    </div>
                    <div class="col-md-1">
                        <a href="privacy.html" target="_blank">Privacy</a>
                    </div>
                    <div class="col-md-2">
                        <a href="termsofservice.html" target="_blank">Terms of Service</a>
                    </div>
                    <div class="col-md-2">
                        <a href="termsofuse.html" target="_blank">Terms of Use</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- Our Requireds Files -->
    <script src="assets/js/jQuery.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/loader.js"></script>
    <script src="assets/js/web.js"></script>
</body>

</html>