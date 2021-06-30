    <?php
    require 'includes/config.php';
    require 'includes/classes/GetDataFromDB.php';
    require 'includes/functions/timeago.php';
    require 'includes/classes/GetPosts.php';

    $GetPosts = new GetPosts;
    $GetData = new GetDataFromDB;

    $main_post = $GetPosts->getMainPosts($con);
    $left_post = $GetPosts->getLeftMainPosts($con);
    $all_section_centered = $GetPosts->getAllSectionCenteredPosts($con);
    $all_section_left = $GetPosts->getAllSectionLeftPosts($con);
    $all_section_other = $GetPosts->getAllOtherSectionPosts($con);
    ?>
    <!doctype html>
    <html lang="en">

    <head>
        <title><?php echo $GetData->getSiteName($con); ?></title>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="Momcilovic-News - Be Informed!">
        <meta name="keywords" content="Momcilovic News, news, Momcilovic-News, blog, create blogs, create blogs online, blogs">
        <meta name="author" content="Stefan Momcilovic">
        <meta name="robots" content="index, follow" />

        <meta property="og:title" content="Momcilovic-News - Be Informed!" />
        <meta property="og:type" content="website" />
        <meta property="og:url" content="https://www.momcilovic-news.com/" />

        <meta itemprop="name" content="Momcilovic-News">
        <meta itemprop="description" content="Momcilovic-News - Be Informed!">

        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:site" content="https://www.momcilovic-news.com/">
        <meta name="twitter:title" content="Momcilovic-News">
        <meta name="twitter:description" content="Momcilovic-News - Be Informed!">
        <meta name="twitter:creator" content="@StefanM_2001">

        <meta name="ahrefs-site-verification" content="d788f22e7b64f1c8d6025b78028546460225da1edd0f0bf58b3241a8a9716bff">
        <!-- Web Icon -->
        <link rel="icon" href="assets/images/computer.png">
        <!-- Bootstrap -->
        <link rel="stylesheet" href="assets/css/bootstrap.min.css">
        <!-- Font Awesome -->
        <script src="https://kit.fontawesome.com/96a76b11be.js" crossorigin="anonymous"></script>
        <!-- CSS -->
        <link rel="stylesheet" href="assets/css/style.css?v=1.1">
        <link rel="stylesheet" href="assets/css/sidebar.css">
        <link rel="stylesheet" href="assets/css/loader.css">

        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-23L0BW5P8J"></script>
        <script>
            window.dataLayer = window.dataLayer || [];

            function gtag() {
                dataLayer.push(arguments);
            }
            gtag('js', new Date());

            gtag('config', 'G-23L0BW5P8J');
        </script>
        <script data-ad-client="ca-pub-5686824873994854" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
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
                                    <form action="search.php" method="GET" class="searchForm">
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

            <button onclick="topFunction()" id="myBtn" title="Go to Top">
                <i class="fas fa-arrow-up"></i>
            </button>

            <br><br>