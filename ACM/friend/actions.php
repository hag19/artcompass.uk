<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
include ('../../accuel/includes/bdd.php');
include('../blog/blog_class.php');
$dm = new dm($pdo);
$blog = new blogHandler($pdo);
if(!isset($_SESSION['id'])){
    header('Location: /ACM/friend/profile_f.php?id='.$id.'&error=1');
    exit;
}
if(isset($_POST['create'])){
    if(isset($_GET['action']) && $_GET['action'] == 'open chat'){
        $id = htmlspecialchars($_POST['id'], ENT_QUOTES, 'UTF-8');
        header('Location: /ACM/dm/messages.php?id='.$id);
        exit;
    }
    $id = htmlspecialchars($_POST['id'], ENT_QUOTES, 'UTF-8');
    $result = $dm->createDm($id);
    if($result){
        $id = $result['id'];
        header('Location: /ACM/dm/messages.php?id='.$id);
        exit;
    } else {
        header('Location: /ACM/friend/profile_f.php?id='.$id.'&error=1');
        exit;
    }
}
if(isset($_GET['action']) && $_GET['action'] == 'add_m'){
    $id = htmlspecialchars($_POST['id'], ENT_QUOTES, 'UTF-8');
    $message = htmlspecialchars($_POST['message'], ENT_QUOTES, 'UTF-8');
    $result = $dm->sendMessage($id, $message);
    if($result){
        header('Location: /ACM/dm/messages.php?id='.$id);
        exit;
    } else {
        header('Location: /ACM/friend/profile_f.php?id='.$id.'&error=1');
        exit;
    }
}
if(isset($_POST['add'])){
    $id = htmlspecialchars($_POST['id'], ENT_QUOTES, 'UTF-8');
$friends = new friends($pdo);
if($friends->add_remove_Friend($id)){
    header('Location: /ACM/friend/profile_f.php?id='.$id);
    exit;
} else {
    header('Location: /ACM/friend/profile_f.php?id='.$id.'&error=1');
    exit;
}
}
if(isset($_POST['edit'])){
$title = htmlspecialchars($_POST['titles'], ENT_QUOTES, 'UTF-8');
$description = htmlspecialchars($_POST['description'], ENT_QUOTES, 'UTF-8');
$id = htmlspecialchars($_POST['id_post'], ENT_QUOTES, 'UTF-8');
if($blog->update_post($id,$title,$description,'posts' , $_FILES['image'], '../img/')){
    header('Location: /ACM/friend/profile_f.php?id='.$_SESSION['id']);
    exit;
} else {
    header('Location: /ACM/friend/profile_f.php?id='.$_SESSION['id'].'&error=1');
    exit;
}
}