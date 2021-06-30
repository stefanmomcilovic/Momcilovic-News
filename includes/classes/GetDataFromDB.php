<?php
    class GetDataFromDB{

        public function getSiteName($con){
            $sitename_query = $con->prepare("SELECT * FROM `sitename` WHERE 1 LIMIT 1");
            $sitename_query->execute();
            while($row = $sitename_query->fetch(PDO::FETCH_ASSOC)){
                return $row['name'];
            }
        }

        public function getFullName($con){
            if (isset($_SESSION['email'])) {
                $email = $_SESSION['email'];
                $getFullName_query = $con->prepare("SELECT `fname`,`lname` FROM `users` WHERE `email`=:em");
                $getFullName_query->bindParam(":em", $email);
                $getFullName_query->execute();
                while ($row = $getFullName_query->fetch(PDO::FETCH_ASSOC)) {
                    $fullName = $row['fname'] . " " . $row['lname'];
                    return $fullName;
                }
            }
        }

        public function getDateJoined($con){
            if (isset($_SESSION['email'])) {
                $email = $_SESSION['email'];
                $getDate = $con->prepare("SELECT `date_joined` FROM `users` WHERE `email`=:em");
                $getDate->bindParam(":em", $email);
                $getDate->execute();
                while ($row = $getDate->fetch(PDO::FETCH_ASSOC)) {
                    $dateJoined = $row['date_joined'];
                    return $dateJoined;
                }
            }
        }

        public function hasPosts($con){
            if (isset($_SESSION['email'])) {
                $email = $_SESSION['email'];
                $getPosts = $con->prepare("SELECT `hasPosts` FROM `users` WHERE `email`=:em");
                $getPosts->bindParam(":em", $email);
                $getPosts->execute();
                while ($row = $getPosts->fetch(PDO::FETCH_ASSOC)) {
                    $hasPosts = $row['hasPosts'];
                    return $hasPosts;
                }
            } 
        }
           
        public function hasComments($con){
            if (isset($_SESSION['email'])) {
                $email = $_SESSION['email'];
                $getComments = $con->prepare("SELECT `hasComments` FROM `users` WHERE `email`=:em");
                $getComments->bindParam(":em", $email);
                $getComments->execute();
                while ($row = $getComments->fetch(PDO::FETCH_ASSOC)) {
                    $hasComments = $row['hasComments'];
                    return $hasComments;
                }
            }   
        }

        public function isAdmin($con){
            if (isset($_SESSION['email'])) {
                $email = $_SESSION['email'];
                $isAdmin = $con->prepare("SELECT `isAdmin` FROM `users` WHERE `email`=:em");
                $isAdmin->bindParam(":em", $email);
                $isAdmin->execute();
                while ($row = $isAdmin->fetch(PDO::FETCH_ASSOC)) {
                    $hasAdmin = $row['isAdmin'];
                    return $hasAdmin;
                }
            }  
        }

        public function getNavbar($con){
            $get_nav = $con->prepare("SELECT `id`,`category_name` FROM `categories` WHERE 1 LIMIT 8");
            $get_nav->execute();
            if($get_nav->rowCount() > 0){
                $result = $get_nav->fetchAll();
                foreach ($result as $row) {
                    echo '<a href="selected_category.php?category_id=' . $row['id'] . '&category_name='. $row['category_name'] . '" target="_self">' . $row['category_name'] . '</a>';
                }
            }
        }        
        
        public function getSideBar($con){
            $get_nav = $con->prepare("SELECT `id`,`category_name` FROM `categories` WHERE 1");
            $get_nav->execute();
            if ($get_nav->rowCount() > 0) {
                $result = $get_nav->fetchAll();
                foreach ($result as $row) {
                    echo '<a href="selected_category.php?category_id=' . $row['id'] . '&category_name=' . $row['category_name'] . '" target="_self">' . $row['category_name'] . '</a>';
                }
            }
        }
    }
?>