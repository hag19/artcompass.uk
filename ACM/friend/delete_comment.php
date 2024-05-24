<?php
session_start();
include("../includes/user_not.php");
include("../../accuel/includes/bdd.php");
include("../blog/blog_class.php");
$blog = new BlogHandler($pdo);
if(isset($_GET['id']) && (isset($_GET['table']) && $_GET['table'] == 'comments_p') && isset($_GET['id_profile'])){
    $id = $_GET['id'];
    $table = $_GET['table'];
    if($blog->deleteComment($id, $table)) {
        header('location:profile.php?message=comment deleted&id='.$_GET['id_profile']);
        exit;
    }
} else if(isset($_GET['id']) && (isset($_GET['table']) && $_GET['table'] == 'comments_p') && isset($_GET['id_profile_f'])){
    $id = $_GET['id'];
    $table = $_GET['table'];
    if($blog->deleteComment($id, $table)) {
        header('location: profile_f.php?id='.$_GET['is_post'].'&message=comment deleted');
        exit;
    }
}else if(isset($_GET['id']) && (isset($_GET['table']) && $_GET['table'] == 'comments_p')){
    $id = $_GET['id'];
    $table = $_GET['table'];
    if($blog->deleteComment($id, $table)) {
        header('location:main.php?message=comment deleted');
        exit;
    }
} else if(isset($_GET['id']) && (isset($_GET['table']) && $_GET['table'] == 'comments_b')){
    $id = $_GET['id'];
    $table = $_GET['table'];
    if($blog->deleteComment($id, $table)) {
        header('location: ../blog/post.php?id='.$_GET['id_post'].'&message=comment deleted');
        exit;
    }
}else{
    header('location:main.php?message=comment not deleted');
    exit;
}