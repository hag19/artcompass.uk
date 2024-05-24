<?php
session_start();
if (!isset($_POST['transport']) && empty($_POST['transport'])){
    header('location:transferts.php?message=please select a transport');
}

if($_POST['transport'] == 'no'){
    header('location:transferts.php?message=please select a transport');
}

if(!isset($_POST['date']) && empty($_POST['date'])){
    header('location:transferts.php?message=please select a date');
}

if(!isset($_POST['time']) && empty($_POST['time'])){
    header('location:transferts.php?message=please select a time');
}

if(!isset($_POST['duration']) && empty($_POST['duration'])){
    header('location:transferts.php?message=please select a duration');
}
$name = htmlspecialchars($_POST['transport'], ENT_QUOTES, 'UTF-8');
$lname = htmlspecialchars($_POST['date'], ENT_QUOTES, 'UTF-8');
$email = htmlspecialchars($_POST['time'], ENT_QUOTES, 'UTF-8');
$phone = htmlspecialchars($_POST['duration'], ENT_QUOTES, 'UTF-8');
$transport= array(
    'name' => $name,
    'date' => $lname,
    'time' => $email,
    'duration' => $phone
);
if(isset($_SESSION['transfer'])) {
    $_SESSION['transfer'][] = $transport;
} else {
    $_SESSION['transfer'] = array($transport);
}
// Check if transfer is successfully stored in session
if(isset($_SESSION['transfer'])) {
    header('Location: ../visites/cart.php'); 
    exit;
} else {
    // Handle error if product is not stored
    echo "Error: Product could not be added to cart.";
}
?>