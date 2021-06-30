<?php
    class GetCategory{
        public function checkValidCategory($con){
            $id = $_GET['category_id'];
            $name = $_GET['category_name'];

            $query = $con->prepare("SELECT `id`,`category_name` FROM `categories` WHERE `id`=:passedId AND `category_name`=:passedName");
            $query->bindParam(":passedId", $id);
            $query->bindParam(":passedName", $name);
            $query->execute();
            if ($query->rowCount() > 0) {
                $query_row = $query->fetch(PDO::FETCH_ASSOC);
                return $query_row;
            }
        }

        public function getNumberOfPostsOnCategory($con){
            $name = $_GET['category_name'];
            $num_posts_query = $con->prepare("SELECT `category_name`,`num_of_posts` FROM `categories` WHERE `category_name`=:catName");
            $num_posts_query->bindParam(":catName", $name);
            $num_posts_query->execute();

            if ($num_posts_query->rowCount() > 0) {
                $num_posts_result = $num_posts_query->fetch(PDO::FETCH_ASSOC);
                return $num_posts_result;
            }
        }

        public function getCategoryPosts($con){
            $post_query = $con->prepare("SELECT * FROM `posts` WHERE `category`=:cname ORDER BY `id`");
            $name = $_GET['category_name'];
            $post_query->bindParam(":cname", $name);
            $post_query->execute();
            if ($post_query->rowCount() > 0) {
                $all_results = $post_query->fetchAll(PDO::FETCH_ASSOC);
                return $all_results;
            }
        }
      
        
    }
?>