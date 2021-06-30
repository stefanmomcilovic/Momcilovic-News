<?php
    class GetPosts{
        public function getMainPosts($con){
            $single_query = $con->prepare("SELECT * FROM `posts` WHERE `section`=:sname ORDER BY `id` DESC LIMIT 1");
            $MainNews = "Main News";
            $single_query->bindParam(":sname", $MainNews);
            $single_query->execute();
            if($single_query->rowCount() > 0){
                $single_result = $single_query->fetch(PDO::FETCH_ASSOC);
                return $single_result;
            }
        }

        public function getLeftMainPosts($con){
            $single_query = $con->prepare("SELECT * FROM `posts` WHERE `section`=:sname ORDER BY `id` DESC LIMIT 1");
            $LeftMain = "New Left Section Main";
            $single_query->bindParam(":sname", $LeftMain);
            $single_query->execute();
            if ($single_query->rowCount() > 0) {
                $single_result = $single_query->fetch(PDO::FETCH_ASSOC);
                return $single_result;
            }
        }
        public function getAllSectionCenteredPosts($con){
            $post_query = $con->prepare("SELECT * FROM `posts` WHERE `section`=:sname ORDER BY `id` DESC LIMIT 6");
            $section = "New Section Centered";
            $post_query->bindParam(":sname", $section);
            $post_query->execute();
            if ($post_query->rowCount() > 0) {
                $all_results = $post_query->fetchAll(PDO::FETCH_ASSOC);
                return $all_results;
            }
        }        
        
        public function getAllSectionLeftPosts($con){
            $post_query = $con->prepare("SELECT * FROM `posts` WHERE `section`=:sname ORDER BY `id` DESC LIMIT 4");
            $section = "New Left Section";
            $post_query->bindParam(":sname", $section);
            $post_query->execute();
            if ($post_query->rowCount() > 0) {
                $all_results = $post_query->fetchAll(PDO::FETCH_ASSOC);
                return $all_results;
            }
        }        
        
        public function getAllOtherSectionPosts($con){
            $post_query = $con->prepare("SELECT * FROM `posts` WHERE `section`=:sname ORDER BY `id` DESC LIMIT 4");
            $section = "New Other";
            $post_query->bindParam(":sname", $section);
            $post_query->execute();
            if ($post_query->rowCount() > 0) {
                $all_results = $post_query->fetchAll(PDO::FETCH_ASSOC);
                return $all_results;
            }
        }
    }

?>