<?php
require 'includes/config.php';
require 'includes/classes/GetDataFromDB.php';
require 'includes/functions/timeago.php';

$GetData = new GetDataFromDB;

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $article_id = $_GET['id'];
    $article_query = $con->prepare("SELECT * FROM `posts` WHERE `id`=:myid");
    $article_query->bindParam(":myid", $article_id);
    $article_query->execute();
    if ($article_query->rowCount() > 0) {
        $article_row = $article_query->fetch(PDO::FETCH_ASSOC);
    } else {
        header("Location: 404error.php");
    }
} else {
    header("Location: 404error.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title><?php echo $article_row['title']; ?> - <?php echo $GetData->getSiteName($con); ?></title>

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
        .article-image {
            max-width: 100%;
        }

        .article-content a {
            color: blue;
        }

        .article-content a:hover {
            color: #070785;
        }

        .article-content ul,
        ol {
            padding: 20px;
        }
    </style>

    <base target="_blank">
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
                                <form action="search.php" target="_self" method="GET" class="searchForm">
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
                                        <a href='profile.php' target='_self' class='col-md-6 col-sm-6 text-right profile'>$fullName</a>
                                    ";
                            }

                            $isAdmin_query = $con->prepare("SELECT `isAdmin` FROM `users` WHERE `email`=:em");
                            $isAdmin_query->bindParam(":em", $email);
                            $isAdmin_query->execute();
                            while ($admin = $isAdmin_query->fetch(PDO::FETCH_ASSOC)) {
                                if ($admin['isAdmin'] == "yes") {
                                    if (isset($_GET['id']) && !empty($_GET['id'])) {
                                        echo "
                                        <div class='row col-sm-12 justify-content-end'>
                                            <a href='edit_post.php?id=$article_id' class='btn post mr-2' id='BtnPost'>Edit</a>
                                            <a href='post.php' class='btn post mr-2' target='_self' id='BtnPost'>Post</a>
                                            <a href='logout.php' class='btn post' target='_self' id='BtnPost'>Logout</a>
                                        </div>
                                        ";
                                    }
                                } else {
                                    echo "
                                            <div class='row col-sm-12 justify-content-end'>
                                                <a href='logout.php' target='_self' class='btn post' id='BtnPost'>Logout</a>
                                            </div>
                                            ";
                                }
                            }
                        } else {
                        ?>
                            <div class="col-md-6 col-sm-6 text-right">
                                <a href="login.php" target="_self" class="btn login" id="BtnLogin">Login</a>
                            </div>
                        <?php
                        } //End else
                        ?>
                    </div>
                </div>
            </div>
            <div class="brand d-flex justify-content-center">
                <a href="index.php" style="text-decoration: none;" target="_self">
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

        <button onclick="topFunction()" id="myBtn" title="Go to Top">
            <i class="fas fa-arrow-up"></i>
        </button>

        <br><br>

        <div class="container">
            <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
            <ins class="adsbygoogle" style="display:block; text-align:center;" data-ad-layout="in-article" data-ad-format="fluid" data-ad-client="ca-pub-5686824873994854" data-ad-slot="1924416683"></ins>
            <script>
                (adsbygoogle = window.adsbygoogle || []).push({});
            </script>
            <div class="row justify-content-md-center">
                <div class="col-md-8 col-sm-12">
                    <img src="<?php echo $article_row['img']; ?>" alt="Article Image" class="article-image">
                    <br><br>
                </div>

                <div class="col-md-12 col-sm-12 text-center">
                    <h1><?php echo $article_row['title']; ?></h1>
                    <span class="ago"><?php echo getDateTimeDiff($article_row['date_added']); ?></span><br>
                </div>

                <div class="col-md-12 col-sm-12">
                    <br>
                    <h5><?php echo $article_row['description']; ?></h5>
                    <br><br>
                </div>

                <div class="col-md-12 col-sm-12 article-content">
                    <?php
                    echo $article_row['text'];
                    ?>
                </div>
            </div>
        </div>

        <br>

    </div>

    <footer class="footerMenu col-md-12 pb-md-3">
        <hr class="ml-md-3 gray_hr">
        <hr class="ml-md-3 gray_hr">
        <br>
        <a href="#mainContainer" class="text-center" style="font-family: Playball-Regular; font-size: 1.3em;">&copy;<?php echo $GetData->getSiteName($con); ?></a>
        <div class="last">
            <div class="row">
                <div class="col-md-3">
                    <a href="#mainContainer" target="_self"> &copy;<span class="year"></span><?php echo $GetData->getSiteName($con); ?> Company</a>
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
    <!-- Our Requireds Files -->
    <script src="assets/js/jQuery.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/loader.js"></script>
    <script src="assets/js/web.js"></script>
</body>

</html>