
    <?php require "includes/header.php"; ?>

        <div class="mainNews d-md-flex text-center">
            <?php
                if(isset($main_post)){
                    if ($main_post['section'] == "Main News") {
                        $timeago = getDateTimeDiff($main_post['date_added']);
                        echo '
                        <div class="mainImage col-md-6">
                            <a href="article.php?id=' . $main_post['id'] . '"><img src="' . $main_post['img'] . '" alt="Main Image"></a>
                        </div>

                        <div class="mainText col-md-6">
                            <a href="article.php?id=' . $main_post['id'] . '">
                                <h1>' . $main_post['title'] . '</h1>
                            </a>
                            <p>' . $main_post['description'] . '</p>
                            <span class="ago">' . $timeago . '</span>
                        </div>
                        ';
                    }
                }
            ?>
        </div>


        <br><br><br><br><br>

        <div class="sectionContent d-flex">
            <?php
                if(isset($all_section_centered)){
                    foreach ($all_section_centered as $row) {
                        if ($row['section'] == "New Section Centered") {
                            $timeago = getDateTimeDiff($row['date_added']);
                            echo '<div class="sectionsNews col-sm-4">
                                <a href="article.php?id=' . $row['id'] . '">
                                    <img src="' . $row['img'] . '" alt="Section image">
                                </a>
                                
                                <a href="article.php?id=' . $row['id'] . '">
                                    <h1 style="font-size:1.6rem">' . $row['title'] . '</h1>
                                </a>

                                <p>' . $row['description'] . '</p>
                                <span class="ago">' . $timeago . '</span>
                            </div>';
                        }
                    }
                }
            ?>
        </div>

        <hr class="black_hr">

        <div class="leftSection text-center">
            <?php
                if(isset($all_section_left)){
                    foreach ($all_section_left as $row) {
                        if ($row['section'] == "New Left Section") {
                            $timeago = getDateTimeDiff($row['date_added']);
                            echo '
                                <div class="lsection d-md-flex">
                                    <div class="col-md-3 col-sm-12">
                                        <a href="article.php?id=' . $row['id'] . '">
                                            <img style="width: 450px;" class="img-fluid" src="' . $row['img'] . '" alt="Left Image Section">
                                        </a>
                                    </div>
                                    <div class="breakLeft"></div>                            
                                    <div class="ltext col-md-9 col-sm-12">
                                        <a href="article.php?id=' . $row['id'] . '">
                                            <h1>' . $row['title'] . '</h1>
                                        </a>
                                        <p>' . $row['description'] . '</p>
                                        <span class="ago">' . $timeago . '</span>
                                    </div>
                            </div>
                            <br><br>
                            ';
                        }
                    }
                }
            ?>
        </div>

        <hr class="dbl_hr">
        <hr class="dbl_hr">
        <br><br>

        <div class="leftImageSection d-flex col-md-12 text-center">
            <div class="row">
                <?php
                    if(isset($left_post)){
                        if ($left_post['section'] == "New Left Section Main") {
                            $timeago = getDateTimeDiff($main_post['date_added']);
                            echo '
                            <div class="col-md-6 col-sm-12">
                                <a href="article.php?id=' . $left_post['id'] . '">
                                    <img src="' . $left_post['img'] . '" alt="Left Image">
                                </a>
                            </div>
                            <div class="leftText col-md-6 col-sm-12">
                                <a href="article.php?id=' . $left_post['id'] . '">
                                    <h1>' . $left_post['title'] . '</h1>
                                </a>
                                <p>' . $left_post['description'] . '</p>
                                <span class="ago">' . $timeago . '</span>
                            </div>
                            ';
                        }
                    }
                ?>
            </div>
        </div>

        <br><br><br>

        <div class="col-md-12 col-sm-12  text-center">
            <h1 class="text-center">Other</h1>
            <br><br><br>
            <div class="row">
                <?php
                    if(isset($all_section_other)){
                        foreach($all_section_other as $row){
                            if ($row['section'] == "New Other") {
                                $timeago = getDateTimeDiff($row['date_added']);
                                echo '
                                    <div class="col-md-5 col-sm-12">
                                        <a href="article.php?id=' . $row['id'] . '" class="col-md-12">
                                            <img src="'. $row['img'] .'" style="width:60%;" class="img-fluid" alt="Other Image">
                                            <h1 style="font-size:1.6rem;">'. $row['title'] .'</h1>
                                        </a>
                                    </div>
                                ';
                            }
                        }
                    }
                ?>
            </div>
        </div>

    <?php require 'includes/footer.php'; ?>

