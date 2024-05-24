<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
try{
    $pdo = new PDO("mysql:host=localhost:3306;dbname=site","root","fan_club",
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
} catch(Exception $e){
    die("Erreur: " . $e->getMessage());
}
$sql1 = "SELECT email FROM news";
$stmt1=$pdo->prepare($sql1);
$stmt1->execute();
$result1 = $stmt1->fetchAll(PDO::FETCH_ASSOC);
    if ($result['email'] === $_POST['newsletter']) {
        header("Location: ../../index.php?message=you are already subscribed.");
        exit;
    }
if (isset($_POST['newsletter']) && !empty($_POST['newsletter'])){
    $sql2 = "INSERT INTO news (email) VALUES(:email)";
$stmt2=$pdo->prepare($sql2);
$stmt2->execute([
    "email"=>$_POST["newsletter"]
]);
$result2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
