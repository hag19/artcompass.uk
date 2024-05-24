<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include('../includes/bdd.php');
$sql = "SELECT token from orders where id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $_POST['id']]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);
    if($_POST['token'] == $result['token'] ){
        if (isset($_POST['name']) && isset($_POST['stars']) && isset($_POST['comment'])){
            $name = $_POST['name'];
            $stars = $_POST['stars'];
            $comment = $_POST['comment'];
            $id = $_POST['id'];
            $sql = "INSERT INTO reviews (name, stars, comment, id_order) VALUES (?,?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$name,$stars,$comment,$id]);
            if ($stmt->rowCount()) {
                $sql = "UPDATE orders SET token = NULL, review = 1 WHERE id = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(['id' => $id]);
                if ($stmt->rowCount()) {
                    echo 'review added';
                    header('Location: ../../admin/users/smart.html');
                    exit;
                }
            }
        }
    }else {
        header('Location: ../../admin/users/smart.html');
        exit;
    }