<?php
ini_set("display_errors", 1);
error_reporting(E_ALL);
$token = $_POST["token"];
$token_hash = hash('sha256', $token);
include('../includes/bdd.php');
$sql = "SELECT * FROM users
WHERE reset_token_hash = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$token_hash]);
$result = $stmt->fetchAll();
if ($result === null) {
    die("not found token");
}
$tokenData = $result[0];
if (empty($_POST["password_conf"]) || empty($_POST["password"])){
    header("location:new_password.php?message=chalb{$token_hash}");
    exit;
} 
if (strlen($_POST["password"]) > 64 || strlen($_POST["password"]) < 8 ){
    header("location:new_password.php?message=password have to be betwwen 8 and 64{$_POST["token"]}");
    exit;
}
if ($_POST['password'] !== $_POST['password_conf']){
    header("location:new_password.php?message=haiuan{$_POST["token"]}");
    exit;
}

$password_hash = hash('sha256', $_POST["password"]);
$sql = "UPDATE users 
SET  password = ? , 
    reset_token_expires_at = NULL,
    reset_token_hash = NULL
    where id = ? ";
$stmt = $pdo->prepare($sql);
$stmt->execute([$password_hash, $tokenData["id"]]);
$result = $stmt->fetchAll();
if(!empty($results)){
    header("location:new_password.php?message=ma barif sho sar.{$_POST["token"]}}");
    exit;
}else {
    header("location:../connex/connexion.php?message=you can log in now with new passwod {$_POST['password']}!");
exit;
}
