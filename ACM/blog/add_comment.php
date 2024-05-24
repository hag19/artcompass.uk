<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require '../../lang.php';
include("../../accuel/includes/bdd.php");
include("blog_class.php");
$blogHandler = new BlogHandler($pdo);

if(isset($_POST['comment']) && isset($_POST['id_blog']) && isset($_SESSION['id'])){
    $id = htmlspecialchars($_POST['id_blog'], ENT_QUOTES, 'UTF-8');
    $comement = htmlspecialchars($_POST['comment'], ENT_QUOTES, 'UTF-8');
    if($blogHandler->submitComment($id, $comment,'comments_b', 'id_blog')){
        header('Location: post.php?id='.$id);
        exit;
    }
}
if(isset($_POST['comment']) && isset($_POST['id_post']) && isset($_SESSION['id'])){
    $id = htmlspecialchars($_POST['id_blog'], ENT_QUOTES, 'UTF-8');
    $comement = htmlspecialchars($_POST['comment'], ENT_QUOTES, 'UTF-8');
    if($blogHandler->submitComment($id, $comment, 'comments_p', 'id_post')){
        header('Location: ../friend/main.php');
        exit;
    }
}
if(isset($_POST['id']) && isset($_POST['name'])){
    $id = htmlspecialchars($_POST['id'], ENT_QUOTES, 'UTF-8');
    $likeTable = htmlspecialchars($_POST['like'], ENT_QUOTES, 'UTF-8');
    $mainTable = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
    $column = htmlspecialchars($_POST['column'], ENT_QUOTES, 'UTF-8');
    if($blogHandler->likeUnlike($id,$likeTable,$mainTable,$column)){
       echo 'success';
    } else {
        echo 'error';
    }

}
?>