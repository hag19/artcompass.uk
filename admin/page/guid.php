<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include("../includes/bdd.php");

if(isset($_POST["date"]) && isset($_POST["time"])){
$sql = 'SELECT username from users where role = :role and username NOT IN (SELECT username FROM visits WHERE date = :date AND time = :time)';
$stmt = $pdo->prepare($sql);
    $stmt->execute([
        "role" => "guide",
        "date" => $_POST["date"],
        "time" => $_POST["time"]
    ]);
    $guide = $stmt->fetchAll(PDO::FETCH_ASSOC);
if(!empty($guide)){
    echo json_encode($guide);
}else{
    echo json_encode("no guide available");
}
}
?>