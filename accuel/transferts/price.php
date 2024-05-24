<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);
session_start();
include ("../includes/bdd.php");
$sql = "SELECT price FROM transferts where name = :name";
$stmt = $pdo->prepare($sql);
$stmt->execute(['name' => $_POST['transport']]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);
echo $result['price'];