<?php
            error_reporting(E_ALL);
            ini_set('display_errors', 1);
            include("../includes/smart_check.php");
            include("../includes/bdd.php");
            include('../../ACM/blog/blog_class.php');
            $blog = new BlogHandler($pdo);
            if(isset($_POST["add"])) {
                if($blog->create_post($_POST["title"], $_POST["description"],'blog', $_FILES["image"])) {
                   header("Location: ../../ACM/blog/blog.php");
                   exit;
                } else {
                    var_dump($_POST["title"]);
                    var_dump($_POST["description"]);
                    var_dump($_FILES["image"]);
                    echo "Error";
                    exit;
                } 
            }