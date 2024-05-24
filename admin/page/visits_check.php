<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);
include("../includes/bdd.php"); 

if (isset($_POST['add'])){
    $sql = 'INSERT INTO visits (places, date, time, username, id_product) VALUES (:places, :date, :time, :username, :id_product)';
    $stmt = $pdo->prepare($sql);
    // Execute the statement, and handle potential errors
    if ($stmt->execute([
        ":places" => $_POST['places'],
        ":date" => $_POST['date'],
        ":time" => $_POST['time'],
        ":username" => $_POST['guide'],
        ":id_product" => $_POST['name']
    ])) { 
        // If the execution is successful, redirect
        header("Location: visits.php?message=" . urlencode($_POST['name']));
        exit;
    } else {
        // If there's an error, handle it (e.g., log it, display an error message)
        echo "An error occurred while executing the query.";
    }
} else {
    header("Location: visits.php?message=no");
}
?>
