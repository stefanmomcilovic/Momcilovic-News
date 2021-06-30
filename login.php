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
    <title>Login - <?php echo $GetData->getSiteName($con); ?></title>
</head>

<body>
    <nav class="navbar navbar-light bg-light mx-auto w-auto justify-content-center">
        <a class="navbar-brand" href="index.php"><img src="assets/images/computer.png" alt="logo"><?php echo $GetData->getSiteName($con); ?></a>
    </nav>
    <div class="loginForm">
        <form action="#" method="POST" class="forml">
            <div class='successReg' style='color:green; text-align: center;'>
                <?php
                if (isset($_POST['login'])) {
                    if (empty($errors_array)) {
                        echo "Your Login Is Successful!";
                        $_SESSION['email'] = $email;
                        header("Location: index.php?login_successful");
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
            <label class="ml-2">Email</label><br>
            <input type="email" name="email" class="email" placeholder="Enter Your Email.." value="<?php echo $email; ?>" autocomplete="off" autofocus required><br><br>
            <label class="ml-2">Password</label><br>
            <input type="password" name="password" class="password" placeholder="Enter Your Password.." autocomplete="off" autofocus required><br><br>
            <a href="register.php" class="register">Doesn't Have Account Yet?</a><br><br>
            <input type="submit" value="Login" name="login" class="login btn btn-primary btn-block">
        </form>
    </div>
</body>

</html>