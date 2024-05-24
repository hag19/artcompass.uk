<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include('../includes/bdd.php');
if (isset($_POST['date']) && isset($_POST['time']) && isset($_POST['places'])) {
    // Storing only id_product and places in the session
    if($_POST['places'] <= 0){
        header('Location: tours.php?message='.$_POST['name']);
        exit;
    }
    $id = htmlspecialchars($_POST['time'], ENT_QUOTES, 'UTF-8');
    $sql="SELECT places FROM visits WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id]);
    $placesL = $stmt->fetch(PDO::FETCH_ASSOC);
    if($placesL['places'] < $_POST['places']){
        header('Location: visite.php?message=not_enough_places');
        echo 'places lefted for this tour' . $placesL['places'];
        exit;
    }
    $placesL = null;
    $places = htmlspecialchars($_POST['places'], ENT_QUOTES, 'UTF-8');
    $tourData = array(
        "places" => $places, 
        "id" => $id,
        "placesL" => $placesL
    );
    // Storing tour data in an array within session
    if(isset($_SESSION['product'])) {
        $_SESSION['product'][] = $tourData;
    } else {
        $_SESSION['product'] = array($tourData);
    }
    // Check if product is successfully stored in session
    if(isset($_SESSION['product'])) {
        header('Location: cart.php'); 
        exit;
    } else {
        // Handle error if product is not stored
        echo "Error: Product could not be added to cart.";
    }
}else{
    header('Location: tours.php?message='. $_GET['id'].'&error=missing_fields');
}
?>
