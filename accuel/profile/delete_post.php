<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);
include ('../includes/bdd.php');
include ('../../ACM/blog/blog_class.php');
$blog = new BlogHandler($pdo);

session_start();
if (!isset($_SESSION['id'])) {
    header('location:../connex/connexion.php?message=Please log in or create an account');
    exit;
}
if(isset($_POST['delete'])){
$title = $_POST['title'];
$id = $_POST['id_post'];
if($blog->delete_post($id, $title,'posts', 'comments_p', 'id_post', '../../ACM/img/', 'likes_p', 'id_p')){
    header('location: profile.php?message=Post deleted');
    exit;
}else{
    header('location: profile.php?message=Post not deleted');
    exit;
}
}