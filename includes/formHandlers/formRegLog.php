<?php
    $errors_array = array();
    $fname = "";
    $lname = "";
    $email = "";
    
    if(isset($_POST['register'])){
        $formSanitizer = new FormSanitizer();

        $fname = $formSanitizer->sanitizeFormString($_POST['fname']);
        $lname = $formSanitizer->sanitizeFormString($_POST['lname']);
        $email = $formSanitizer->sanitizeFormEmail($_POST['email']);
        $cemail = $formSanitizer->sanitizeFormEmail($_POST['cemail']);
        $password = $formSanitizer->sanitizeFormPassword($_POST['password']);
        $cpassword = $formSanitizer->sanitizeFormPassword($_POST['cpassword']);

        if(empty($fname) && empty($lname)){
            array_push($errors_array, "First Name and Last Name Can't Be Empty..");
        }
        if(strlen($fname) < 2){
            array_push($errors_array, "First Name Must Be At Least 2 Characters Long..");
        }

        if(empty($email)){
            array_push($errors_array, "Email Addresses Fields Can't Be Empty..");
        }
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            array_push($errors_array, "Please Enter Validate Email Addresses..");  
        }
        if ($email !== $cemail) {
            array_push($errors_array, "Email Addresses Are Not The Same..");
        }
        $emailCheck = $con->prepare("SELECT `email` FROM `users` WHERE `email`=:email");
        $emailCheck->bindParam(":email", $email);
        $emailCheck->execute();
        if ($emailCheck->rowCount() > 0) {
            array_push($errors_array, "Email Address Already Exist Try a Different One..");
        }

        if(empty($password)){
            array_push($errors_array, "Passwords Fields Can't Be Empty..");
        }
        if(strlen($password) < 5){
            array_push($errors_array, "Passwords Cannot Be Less Than 5 Characters..");
        }
        if ($password !== $cpassword) {
            array_push($errors_array, "Passwords Are Not The Same..");
        }
        
        if(empty($errors_array)){
            $encrypted_password = password_hash($password,PASSWORD_BCRYPT);

            $query = $con->prepare("INSERT INTO `users`(`fname`, `lname`, `email`, `password`, `isAdmin`) VALUES(:fname,:lname,:email,:pw,'no')");
            $query->bindParam(":fname",$fname);
            $query->bindParam(":lname",$lname);
            $query->bindParam(":email",$email);
            $query->bindParam(":pw",$encrypted_password);
            $query->execute();
        }
    }

    if(isset($_POST['login'])){
        $formSanitizer = new FormSanitizer();

        $email = $formSanitizer->sanitizeFormEmail($_POST['email']);
        $password = $formSanitizer->sanitizeFormPassword($_POST['password']);

        $emailCheck = $con->prepare("SELECT `email` FROM `users` WHERE `email`=:email");
        $emailCheck->bindParam(":email", $email);
        $emailCheck->execute();

        if ($emailCheck->rowCount() == 0) {
            array_push($errors_array, "Email Address Doesn't Exist Try Again..");
        }
        if (empty($email)) {
            array_push($errors_array, "Email Address Fields Can't Be Empty..");
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            array_push($errors_array, "Please Enter Validate Email Address..");
        }

        if (empty($password)) {
            array_push($errors_array, "Password Fields Can't Be Empty..");
        }
        if (strlen($password) < 5) {
            array_push($errors_array, "Password Cannot Be Less Than 5 Characters..");
        }

        $passwordCheck = "SELECT `email`,`password` FROM `users` WHERE `email`=:email";
        $stmt = $con->prepare($passwordCheck);
        $stmt->bindParam(":email",$email);
        $stmt->execute();
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            if(!password_verify($password,$row['password'])){
                array_push($errors_array,"Password Is Incorrect..");
            }
        }
    }
?>