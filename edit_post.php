<?php
require "includes/config.php";
require "includes/classes/GetDataFromDB.php";
require "includes/classes/FormSanitizer.php";

$GetDataFromDB = new GetDataFromDB;
$isAdmin = $GetDataFromDB->isAdmin($con);

if (isset($_GET['id']) && !empty($_GET['id']) && $isAdmin == "yes") {
    $edit_id = $_GET['id'];
    $edit_name_query = $con->prepare("SELECT * FROM `posts` WHERE `id`=:editid LIMIT 1");
    $edit_name_query->bindParam(":editid", $edit_id);
    $edit_name_query->execute();
    $edit_name_result = $edit_name_query->fetch(PDO::FETCH_ASSOC);

    if (isset($_POST['edit-article'])) {
        /***********************************************************
            0 - Remove The Temp image if it exists
         ***********************************************************/
        if (!isset($_POST['x']) && !isset($_FILES['image']['name'])) {
            $FormSanitizer = new FormSanitizer;

            $Get_FullName  = $GetDataFromDB->getFullName($con);
            $Get_FullName = $FormSanitizer->sanitizeProfileId($Get_FullName);
            $_SESSION['profileid'] = $Get_FullName;
            $profile_id = $_SESSION['profileid'];
            //Delete users temp image
            $temppath = 'assets/images/article_pics/' . $profile_id . '_temp.jpeg';
            if (file_exists($temppath)) {
                @unlink($temppath);
            }
        }

        if (isset($_FILES['image']['name'])) {
            $FormSanitizer = new FormSanitizer;

            $Get_FullName  = $GetDataFromDB->getFullName($con);
            $Get_FullName = $FormSanitizer->sanitizeProfileId($Get_FullName);
            $_SESSION['profileid'] = $Get_FullName;
            $profile_id = $_SESSION['profileid'];
            /***********************************************************
                 1 - Upload Original Image To Server
             ***********************************************************/
            //Get Name | Size | Temp Location		    
            $ImageName = $_FILES['image']['name'];
            $ImageSize = $_FILES['image']['size'];
            $ImageTempName = $_FILES['image']['tmp_name'];
            //Get File Ext   
            $ImageType = @explode('/', $_FILES['image']['type']);
            $type = $ImageType[1]; //file type	
            //Set Upload directory    
            $uploaddir = 'assets/images/article_pics/';
            //Set File name	
            $file_temp_name = $profile_id . '_original.' . md5(time()) . 'n' . $type; //the temp file name
            $fullpath = $uploaddir . "/" . $file_temp_name; // the temp file path
            $file_name = $profile_id . md5(time()) . ".jpeg"; //$profile_id.'_temp.'.$type; // for the final resized image
            $fullpath_2 = $uploaddir . "/" . $file_name; //for the final resized image
            //Check for valid uplaod
            $allowed_image_extension = array(
                "png",
                "jpg",
                "jpeg",
                "gif"
            );
            $msg = [];
            $move = NULL;

            if ($_FILES['image']['size'] > 10485760) { //10 MB (size is also in bytes)
                // File too big
                $msg = "File is need to be less then 10MB";
            } else {
                // File within size restrictions
                if (in_array($type, $allowed_image_extension)) {
                    $move = move_uploaded_file($ImageTempName, $fullpath);
                    chmod($fullpath, 0777);
                } else {
                    $msg = "There was an error uploading the file. Please upload a .jpg, .gif or .png file. <br />";
                }
            }

            if (!$move) {
                $msg = "File didn't upload. There was an error uploading the file. Please upload a .jpg, .gif or .png file.";
            } else {
                $imgSrc = "assets/images/article_pics/" . $file_name; // the image to display in crop area
                $src = $file_name;             //the file name to post from cropping form to the resize		
            }

            if($move){
                /***********************************************************
                 2  - Resize The Image To Fit In Cropping Area
                 ***********************************************************/
                //get the uploaded image size	
                clearstatcache();
                $original_size = getimagesize($fullpath);
                $original_width = $original_size[0];
                $original_height = $original_size[1];
                // Specify The new size
                $main_width = 500; // set the width of the image
                $main_height = $original_height / ($original_width / $main_width);    // this sets the height in ratio									
                //create new image using correct php func			
                if ($_FILES["image"]["type"] == "image/gif") {
                    $src2 = imagecreatefromgif($fullpath);
                } elseif ($_FILES["image"]["type"] == "image/jpeg" || $_FILES["image"]["type"] == "image/pjpeg") {
                    $src2 = imagecreatefromjpeg($fullpath);
                } elseif ($_FILES["image"]["type"] == "image/png") {
                    $src2 = imagecreatefrompng($fullpath);
                } else {
                    $msg .= "There was an error uploading the file. Please upload a .jpg, .gif or .png file. <br />";
                }
                //create the new resized image
                $main = imagecreatetruecolor($main_width, $main_height);
                imagecopyresampled($main, $src2, 0, 0, 0, 0, $main_width, $main_height, $original_width, $original_height);
                //upload new version
                $main_temp = $fullpath_2;
                imagejpeg($main, $main_temp, 90);
                chmod($main_temp, 0777);
                //free up memory
                imagedestroy($src2);
                imagedestroy($main);
                //imagedestroy($fullpath);
                @unlink($fullpath); // delete the original upload
                $deleteOldImg_query = $con->prepare("SELECT `img` FROM `posts` WHERE `id`=:eid");
                $deleteOldImg_query->bindParam(":eid", $edit_id);
                $deleteOldImg_query->execute();
                $deleted_row = $deleteOldImg_query->fetch(PDO::FETCH_ASSOC);
                unlink($deleted_row['img']);
                //Update db
                $edit_id = $_GET['id'];
                $edited_title = strip_tags($_POST['title']);
                $edited_desc = strip_tags($_POST['description']);
                $edited_text = stripslashes($_POST['copy_html']);
                $edited_preview = strip_tags($_POST['ptext']);
                $edited_image = "assets/images/article_pics/" . $file_name;
                $edited_category = $_POST['category'];

                $edit_id = $_GET['id'];
                $category_oldNum_query = $con->prepare("SELECT * FROM `posts` WHERE `id`=:editid LIMIT 1");
                $category_oldNum_query->bindParam(":editid", $edit_id);
                $category_oldNum_query->execute();
                $category_row = $category_oldNum_query->fetch(PDO::FETCH_ASSOC);
                if ($category_row['category'] != $edited_category) {
                    $old_cat_name = $category_row['category'];
                    $old_cat_query = $con->prepare("UPDATE `categories` SET `num_of_posts`=`num_of_posts` - 1 WHERE `category_name`=:oldName");
                    $old_cat_query->bindParam(":oldName", $old_cat_name);
                    $old_cat_query->execute();
                    $category_update_query = $con->prepare("UPDATE `categories` SET `num_of_posts`= `num_of_posts` + 1 WHERE `category_name`=:catName");
                    $category_update_query->bindParam(":catName", $edited_category);
                    $category_update_query->execute();
                }
                $edit_query = $con->prepare("UPDATE `posts` SET `title`=:etitle, `description`=:edesc, `text`=:etext, `preview`=:eprev, `category`=:ecat, `img`=:eimg WHERE `id`=:eid LIMIT 1");
                $edit_query->bindParam(":etitle", $edited_title);
                $edit_query->bindParam(":edesc", $edited_desc);
                $edit_query->bindParam(":etext", $edited_text);
                $edit_query->bindParam(":eprev", $edited_preview);
                $edit_query->bindParam(":ecat", $edited_category);
                $edit_query->bindParam(":eimg", $edited_image);
                $edit_query->bindParam(":eid", $edit_id);
            }
           
        } //ADD Image
    }
} else {
    header("Location: 404error.php?Admin_Permission_Only");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Editing <?php echo $edit_name_result['title'] ?> Article - <?php echo $GetDataFromDB->getSiteName($con); ?></title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Web Icon -->
    <link rel="icon" href="assets/images/computer.png">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/96a76b11be.js" crossorigin="anonymous"></script>
    <!-- WMD -->
    <link rel="stylesheet" href="assets/wmd/wmd.css">
    <script src="assets/wmd/wmd.js"></script>
    <script src="assets/wmd/showdown.js"></script>
    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/loader.css">
    <link rel="stylesheet" href="assets/css/post.css">
    <style type="text/css" media="screen">
        textarea {
            width: 784px;
            height: 215px;
            transition: all 1s ease;
            margin: 0;
        }

        textarea,
        #notes-preview {
            border: 1px solid gray;
            height: 100px;
        }

        #notes-preview {
            overflow-x: hidden;
            overflow-y: scroll;
            width: 1000px !important;
        }

        #remaining-chars {
            color: #333;
            font-size: 14px;
            font-weight: bold;
            text-align: right;
            margin-left: -7em;
        }

        @media (max-width:961px) {
            #notes-preview {
                width: 100% !important;
            }
        }
    </style>
</head>

<body>

    <?php require "includes/loader.php"; ?>

    <nav class="navbar navbar-light bg-light mx-auto w-auto justify-content-center">
        <a class="navbar-brand" href="index.php"><img src="assets/images/computer.png" alt="logo"> <?php echo $GetDataFromDB->getSiteName($con); ?></a>
    </nav>

    <br>

    <div class="container post-form d-flex">
        <form action="#" method="POST" enctype="multipart/form-data" class="form-post">

            <?php
            if (isset($_POST['edit-article'])) {
                if (!empty($msg)) {
                    echo "<div class='msgfaild' id='msgdiv'>
                    $msg
                    </div>
                    <br>
                    ";
                } else {
                    echo "<div class='msgsuccess' id='msgdiv'>
                    Article Upload Successfully
                    </div>
                    <br>
                    ";
                }
            }
            ?>

            <label>Edit Article Title</label><br>
            <input type="text" name="title" id="title" class="post-title" autocomplete="off" value="<?php echo $edit_name_result['title']; ?>"><br><br>

            <label>Edit Article Description For Viewers on Front-Page <span style="color:red;">(Remove Spaces First)</span></label><br>
            <textarea name="description" id="desc" class="post-description" maxlength="400">
                <?php echo $edit_name_result['description']; ?>
            </textarea><br>
            <div id="remaining-chars" class="col-sm-12">400 charactes remaining</div><br>

            <label>Edit Article Text <span style="color:red;">(Remove Spaces First)</span></label><br>
            <div id="notes-button-bar"></div>
            <textarea name="ptext" id="notes" class="post-text" contenteditable="true">
                 <?php echo $edit_name_result['preview']; ?>
            </textarea><br>
            <label>Edit Preview:</label>
            <div id="notes-preview" style="text-align:initial;">
            </div><br>
            <textarea type="text" name="copy_html" value="" id="copy_html" style="display:none;"></textarea>

            <label>Edit Article Image</label><br>
            <input type="file" name="image" class="post-image" required><br><br><br>

            <label>Edit Article Category</label><br>
            <select name="category" class="post-category" required><br><br>
                <option value="" selected disabled>Select Category</option>>
                <?php
                $category_query = $con->prepare("SELECT `category_name` FROM `categories` WHERE 1");
                $category_query->execute();

                if ($category_query->rowCount() > 0) {
                    while ($row = $category_query->fetch(PDO::FETCH_ASSOC)) {
                        echo '<option value="' . $row['category_name'] . '"> ' . $row['category_name'] . ' </option>';
                    }
                }
                ?>
            </select> <br><br><br>

            <input type="submit" name="edit-article" value="Edit" class="btn btn-post"><br><br>
        </form>
    </div>
    <!-- Our Requireds Files -->
    <script src="assets/js/jQuery.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/loader.js"></script>
    <script>
        var article_title = $("#title");
        article_title.click(function() {
            article_title.addClass("heading");
        });

        var description = $("#desc");
        description.click(function() {
            description.addClass("description");
        });

        $("#notes").on("click", function() {
            $("#notes").addClass("description");
        });

        const myDescription = document.getElementById("desc");
        const remainingCharsText = document.getElementById("remaining-chars");
        const MAX_CHARS = 400;

        myDescription.addEventListener("input", () => {
            const remaining = MAX_CHARS - myDescription.value.length;
            const color = remaining < MAX_CHARS * 0.1 ? 'red' : null;

            remainingCharsText.textContent = `${remaining} characters remaining`;
            remainingCharsText.style.color = color;
        });
    </script>
    <script type="text/javascript">
        new WMDEditor({
            input: "notes",
            button_bar: "notes-button-bar",
            preview: "notes-preview",
            output: "copy_html",
            buttons: "bold italic link  ol ul  heading",
            modifierKeys: false,
            autoFormatting: false
        });
    </script>
</body>

</html>