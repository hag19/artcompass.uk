<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
if (!isset($_POST['email']) || empty($_POST['email'])) {
    header('location:profile_edit.php?id=' . $_POST['id'] . '&message=Email input is empty');
    exit;
}
// Verifier email valable
if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
    header("location:profile_edit.php?id=" . $_POST['id'] . "&message=wrong email address format");
    exit;
}
// Verifier si mot de passe change
$resetPassword = isset($_POST["password"]) && !empty($_POST['password']);
// Si oui -> verification du mot de passe
if ($resetPassword) {
    if (strlen($_POST["password"]) > 64 || strlen($_POST["password"]) < 8) {
        header("location:registration.php?message=password has to be between 8 and 64 characters");
        exit;
    }
}



// Si fichier ok -> suppression de l'ancien fichier et enregistrement du nouveau
// Mettre a jour les donnees 
include ("../includes/bdd.php");
$email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
$id = htmlspecialchars($_POST['id'], ENT_QUOTES, 'UTF-8');
$params = [
    'email' => $email,
    'id' => $id
];

if (isset($_POST['username']) && !empty($POST['username'])){
    $username = htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8');
    $q="UPDATE users SET username = :username";
    $req = $pdo->prepare($q);
    $result = $req->execute([
        'id' => $id,
        'username'=>$username
    ]);
}

if(isset($_FILES["image"]) && $_FILES["image"]["error"] != 4){

    $acceptable = ["image/jpeg","image/gif","image/png"];
    if(!in_array($_FILES["image"]["type"],$acceptable)){
        header('location:connexion.php?message=type de fichier incorrect');
        exit;
    }

    $maxSize = 1024*1024*6; //6 Mo
    if($_FILES["image"]["size"]> $maxSize){
        header("location:connexion.php?message=Le fichier ne doit pas dépasser 6 Mo");
        exit; 
    }


    if(!file_exists('uploads')){ 
        mkdir('uploads');

    }

    $from = $_FILES['image']['tmp_name'];

    $array = explode('.',$_FILES['image']['name']);
    $ext = end($array);

    $filename = 'image-' . time() . '.' . $ext;

    $to = 'uploads/' . $filename;
    move_uploaded_file($from,$to);
    $q = 'UPDATE users SET image = :image WHERE id = :id';
    $req = $pdo->prepare($q);
    $result = $req->execute([
        'id' => $id,
        'image' => $filename
    ]);
}

if ($resetPassword) {
    $q = "UPDATE users SET email = :email, password= :password WHERE id = :id";
    $password_hash = hash("sha256", $_POST['password']);
    $params['password'] = $password_hash;
} else
    $q = "UPDATE users SET email = :email WHERE id = :id";

$req = $pdo->prepare($q);
$result = $req->execute($params);

if (!$result) {
    header('location: profile_edit.php?id=' . $_POST['id'] . '&message=Error');
    exit;
}else{
header('location: profile_edit.php?id=' . $_POST['id'] . '&message=Successfully changed profile !');
exit;
}

