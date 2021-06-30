<?php
require "includes/config.php";
require 'includes/classes/GetDataFromDB.php';
require "includes/PHPMailer/PHPMailerAutoload.php";
require "includes/html2text.php";

$GetDataFromDB = new GetDataFromDB;
$error_array = array();

if (isset($_POST['send'])) {
    if (!empty($_POST['message']) && !empty($_POST['name']) && !empty($_POST['last']) && !empty($_POST['email'])) {
        $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);

        $subject = "New Message Momcilovic-News - " . strip_tags($_POST['name']) . " " . strip_tags($_POST['last']);
        $body = strip_tags($_POST['message']);
        $sender_email = "From: " . strip_tags($email);
        $mail = new PHPMailer();
        $mail->Host = "51.195.47.200"; //Mail Server
        $mail->Port = 587; //Port za email
        $mail->SMTPAuth = true; //SMTP Autetifikacija
        $mail->SMTPSecure = "tls"; //Bezbednost 
        $mail->setFrom(strip_tags($email), strip_tags($_POST['name']) . " " . strip_tags($_POST['last'])); //Od koga se dobija poruka
        $mail->addAddress("stefan.momcilovic001@gmail.com"); //Emal na kojem se salju poruke
        $mail->addReplyTo("stefan.momcilovic001@gmail.com", "Momcilovic-News"); //ReplyTo Da da odgovor na ovaj email
        $mail->isHTML(true); //Da se koristi HTML u emailu
        $mail->Subject = $subject; //Tema Emaila
        $mail->Body = $body; //Poruka Emaila
        $mail->AltBody = Html2Text::convert($mail->Body);
        if (!$mail->send()) {
            array_push($error_array, "Faild To Send Email On Your Address, Please Try Again Later or Contact Support.");
        }
    } else {
        array_push($error_array, "Fields Can't Be Empty!");
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Contact Us - <?php echo $GetDataFromDB->getSiteName($con); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Web Icon -->
    <link rel="icon" href="assets/images/computer.png">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/96a76b11be.js" crossorigin="anonymous"></script>
    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/work.css">
    <link rel="stylesheet" href="assets/css/loader.css">
</head>

<body>
    <?php require "includes/loader.php"; ?>

    <nav class="navbar navbar-light bg-light mx-auto w-auto justify-content-center">
        <a class="navbar-brand" href="index.php"><img src="assets/images/computer.png" alt="logo"> <?php echo $GetDataFromDB->getSiteName($con); ?></a>
    </nav>

    <div class="workwithus">
        <form method="POST" class="work">
            <input type="text" placeholder="Your Name.." name="name" required>
            <input type="text" placeholder="Your Last Name.." name="last" required>
            <input type="email" placeholder="Your Email.." name="email" required>
            <textarea name="message" id="" cols="30" rows="5" placeholder="Please Enter Your Message Why We Would Pick You?" required></textarea>
            <input type="submit" value="SEND" name="send" class="btn btn-success workbtn">
            <?php
            if (isset($_POST['send'])) {
                if (!empty($error_array)) {
                    foreach ($error_array as $error) {
                        echo "<p class='text-center' style='background: #fff;
                                                            color: #f00;
                                                            padding: 10px;
                                                            margin-top: 10px;'>$error</p>";
                    }
                } else {
                    echo "<p class='text-center' style='color: #007b00;
                                                        background: #fff;
                                                        margin-top: 10px;
                                                        padding: 10px;'>Message Sent!</p>";
                }
            }
            ?>
        </form>
    </div>

    <script src="assets/js/loader.js"></script>
</body>

</html>