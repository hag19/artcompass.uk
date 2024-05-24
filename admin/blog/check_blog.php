<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include("../includes/bdd.php");
include('../../ACM/blog/blog_class.php');
$blog = new BlogHandler($pdo);
if(isset($_POST['id'])) {
    $id = $_POST['id'];
    $sql = 'SELECT title FROM blog WHERE id = :id';
    $stmt = $pdo->prepare($sql);
    $stmt->execute(["id" => $id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if(isset($_POST["delete"])) { 
        $file = $_POST['ph']; 
        if(file_exists($file)) {
            unlink($file);
            header("location: edit_blog.php?id=" . $id . "&message=photo is deleted");
            exit;
        } else {
            header("location: edit_blog.php?id=" . $id . "&message=file not found");
            exit;
        }
    }

   if(isset($_POST['title']) && isset($_POST['description'])) {
if($blog->update_post($id, $_POST['title'], $_POST['description'], 'blog', $_FILES['image'], '../img/')) {
    header("location: edit_blog.php?id=" . $id . "&message=success");
    exit;
   } 
     
    header("location: blog.php?message=success");
}
}
?>
