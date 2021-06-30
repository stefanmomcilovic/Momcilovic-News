<?php
require 'includes/config.php';
require 'includes/classes/FormSanitizer.php';
require 'includes/formHandlers/formRegLog.php';
require "includes/classes/GetDataFromDB.php";
$GetData = new GetDataFromDB;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Web Icon -->
    <link rel="icon" href="assets/images/computer.png">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/96a76b11be.js" crossorigin="anonymous"></script>
    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/login.css">
    <title>Register - <?php echo $GetData->getSiteName($con); ?></title>
</head>

<body>
    <nav class="navbar navbar-light bg-light mx-auto w-auto justify-content-center">
        <a class="navbar-brand" href="index.php"><img src="assets/images/computer.png" alt="logo"><?php echo $GetData->getSiteName($con); ?></a>
    </nav>
    <div class="registerForm">
        <form action="#" method="POST" class="formr">
            <div class='successReg' style='color:green; text-align: center;'>
                <?php
                if (isset($_POST['register'])) {
                    if (empty($errors_array)) {
                        echo "Your Registration Is Successful!";
                        header("Location: login.php?registration_successful");
                    }
                }
                ?>
            </div>
            <div class="errorMessages" style="color: red; text-align: center;">
                <?php
                foreach ($errors_array as $error) {
                    echo $error . "<br>";
                }
                ?>
            </div>
            <label class="ml-2">First Name</label><br>
            <input type="text" name="fname" class="fname" placeholder="Enter Your First Name.." value="<?php echo $fname; ?>" autocomplete="off" autofocus required>
            <br><br>
            <label class="ml-2">Last Name</label><br>
            <input type="text" name="lname" class="lname" placeholder="Enter Your Last Name.." value="<?php echo $lname; ?>" autocomplete="off" autofocus required>
            <br><br>
            <label class="ml-2">Email</label><br>
            <input type="email" name="email" class="email" placeholder="Enter Your Email.." value="<?php echo $email; ?>" autocomplete="off" autofocus required>
            <br><br>
            <label class="ml-2">Confirm Email</label><br>
            <input type="email" name="cemail" class="cemail" placeholder="Confirm Your Email.." autocomplete="off" autofocus required>
            <br><br>
            <label class="ml-2">Password</label><br>
            <input type="password" name="password" class="password" placeholder="Enter Your Password.." autocomplete="off" autofocus required>
            <br><br>
            <label class="ml-2">Confrim Password</label><br>
            <input type="password" name="cpassword" class="cpassword" placeholder="Confirm Your Password.." autocomplete="off" autofocus required><br><br>
            <a href="login.php" class="register">Already Have Account?</a><br><br>
            <input type="submit" value="Register" name="register" class="registerbtn btn btn-primary btn-block">
        </form>
    </div>
</body>

</html>