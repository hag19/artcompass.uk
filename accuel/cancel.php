<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    include ("includes/header.php");

 $sql = "DELETE email from news WHERE email = :email";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    "email" => $_GET["email"]
]);
$result = $stmt->fetchALL(PDO::FETCH_ASSOC);
if($result){
    header("location: ../index.php");
    exit;
}