<?php
//var_dump($_FILES);
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
use PHPMailer\PHPMailer\PHPMailer;
require '../vendor/phpmailer/phpmailer/src/Exception.php';
require '../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '../vendor/phpmailer/phpmailer/src/SMTP.php';
// Подключаем PHPMailer
require '../vendor/autoload.php';
include('../includes/bdd.php');
if (isset($_POST["lname"]) && !empty($_POST["lname"])){
    setcookie("lname",$_POST["lname"],time() + 30*24*3600);
}

if (isset($_POST["fname"]) && !empty($_POST["fname"])){
    setcookie("fname",$_POST["fname"],time() + 30*24*3600);
}

if (isset($_POST["email"]) && !empty($_POST["email"])){
    setcookie("email",$_POST["email"],time() + 30*24*3600);
}
if (empty($_POST["email"]) || empty($_POST["password"]) || empty($_POST["lname"]) || empty($_POST["fname"])){
    header("location:registration.php?message=Please fill in all fields");
    exit;
}
if(!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)){
    header("location:registration.php?message=wrong email address");
    exit;
}
if (strlen($_POST["password"]) > 64 || strlen($_POST["password"]) < 8 ){
    header("location:registration.php?message=password has to be between 8 and 64");
    exit;
}
$email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
$username = htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8');

$sql1 = "SELECT email, username FROM users WHERE email = :email OR username = :username";
$stmt1=$pdo->prepare($sql1);
$stmt1->execute([
    'email'=>$email,
    'username'=>$username
]);
$results1 = $stmt1->fetchAll(PDO::FETCH_ASSOC);
foreach ($results1 as $result) {
    if ($result['email'] === $_POST['email']) {
        header("Location: registration.php?message=An account with this email address already exists.");
        exit;
    }
    if ($result['username'] === $_POST['username']) {
        header("Location: registration.php?message=An account with this username already exists.");
        exit;
    }
}
$answer = htmlspecialchars($_POST['answer'],ENT_QUOTES, 'UTF-8');  
if ($answer !== $_SESSION['goodAnswer']) {
   header("Location:registration.php?message=wrong answer");
   exit;
}
$lname = htmlspecialchars($_POST['lname'],ENT_QUOTES, 'UTF-8');
$fname = htmlspecialchars($_POST['fname'],ENT_QUOTES, 'UTF-8');
$sql2 = "INSERT INTO users (email,password,lname,fname, username) VALUES(:email,:password,:lname,:fname, :username)";

$stmt2=$pdo->prepare($sql2);
$password_hash= hash("sha256", $_POST['password']);
$stmt2->execute([
    "email"=>$email,
    "password"=>$password_hash,
    "lname"=>$lname,
    "fname"=>$fname,
    "username"=>$pseudo
]);
if(!$stmt2){
    header("location:registration.php?message=error with registration.");
    exit;
}
if(isset($_FILES["image"]) && $_FILES["image"]["error"] != 4){
    //vérifier le type de fichier
    $acceptable = ["image/jpeg","image/gif","image/png", "image/pdf"];
    if(!in_array($_FILES["image"]["type"],$acceptable)){
        header('location:registration.php?message=wrong file type');
        exit;
    }
    //verifier le poids du fichier
    $maxSize = 1024*1024*6; //6 Mo
    if($_FILES["image"]["size"]> $maxSize){
        header("location:registration.php?message=file bigger then 6M");
        exit; 
    }
    //créer un dossier
    if(!file_exists('../upload')){ 
        mkdir('../upload');
    }
    $sql = "SELECT id from users WHERE email =:email";
    $req = $pdo->prepare($sql);
    $req->execute([
        'email' => $_POST['email']
    ]);
    $results = $req->fetch(PDO::FETCH_ASSOC);
    $from = $_FILES['image']['tmp_name']; 
    $array = explode('.',$_FILES['image']['name']);
    $ext = end($array);
    $filename = 'image' . $results['id'] . '.' . $ext;
    $to = '../upload/' . $filename;
    move_uploaded_file($from,$to);
    $sql3 = 'UPDATE users SET image = :image WHERE email = :email';
    $stmt3=$pdo->prepare($sql3);
    $stmt3->execute([
    "image" => $filename,
    "email" => $_POST['email']
]);
}
if(!$stmt2){
    header("location:registration.php?message=error with registration.");
    exit;
}else if($stmt2->rowCount()){
    $code_email = rand();
    $email = $_POST['email'];
    $sql = "UPDATE users
    SET code_email = ?
    WHERE email = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    $code_email,$email
]);
try { 
    $mail =  new PHPMailer(true);
    $mail->isSMTP();
    $mail->SMTPAuth = false;
    $mail->Port = 25; // Укажите порт SMTP
    $mail->isHTML(true);
    $mail->setFrom("ValidEmail@artcompass.uk");
    $mail->addAddress($email);
    $mail->Subject = 'Valid your account';
    $mail->Body = 'Click <a href="https://artcompass.uk/accuel/registr/confirm.php">here</a> to validate your account. Your code is: ' . $code_email;
    $mail->send();
    echo 'Please check your email address';
} catch (Exception $e) {
    echo "Mail not sent. Error: " . $e->getMessage();
}
}
?>