<?php
       error_reporting(E_ALL);
       ini_set('display_errors', 1);
       session_start();
       include("../../accuel/includes/bdd.php");
       include("blog_class.php");
         $blogHandler = new BlogHandler($pdo);
            if(isset($_POST['add'])){
                $title = htmlspecialchars($_POST['title'], ENT_QUOTES, 'UTF-8');
                $description = htmlspecialchars($_POST['description'], ENT_QUOTES, 'UTF-8');
                $image = $_FILES['image'];
                if($blogHandler->create_post($title, $description, 'posts', $image, '../img/')){
                    header('Location: blog.php?message=success');
                    exit;
                }else{
                    header('Location: blog.php?message=error');
                    exit;
                }
            }
