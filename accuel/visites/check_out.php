<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require '../../lang.php';
include('../includes/bdd.php');

if(isset($_POST["checkout"]) && isset($_SESSION['product']) && is_array($_SESSION['product'])){
    foreach($_SESSION['product'] as $i => $product) {
        if(isset($product['id'])) {
            $sql = "SELECT visits.places FROM visits WHERE visits.id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['id' => $product['id']]);
            $result = $stmt->fetch();
            if($result['places'] < $product['places'])
                $product['placesL'] = $result['places'];
        } else {
            echo "ID not set for product at index $i<br>";
        }
    }
    foreach($_SESSION['product'] as $product) {
        if(isset($product['placesL']) && $product['placesL'] < $product['places']){
            header('Location: cart.php?message=not_enough_places');
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Checkout</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" href="/accuel/images/favicon.png">
        <link rel="stylesheet" type="text/css" href="/accuel/css/style.css?v=<?php echo time(); ?>" media='screen' />
    </head>
    <body>
        <?php 
            include("../includes/header.php");
        ?> 
        <h1 class="checkoutTitle indexBlackFont">
            Checkout
        </h1>
        <form action="chek_outf.php" method="post">
            <div class="checkout checkoutName indexBlueDark">
                <input type="text" name="name" placeholder="Name" required class="indexWhiteDark">
                <input type="text" name="lname" placeholder="Surname" required class="indexWhiteDark">
            </div>
            <div class="checkout checkoutCoords indexBlueDark">
                <input type="email" name="email" placeholder="Email" required class="indexWhiteDark">
                <input type="tel" name="phone" placeholder="Phone" pattern="[+][0-9]{6-15}" required class="indexWhiteDark">
            </div>
            <div class="checkout checkoutConfirm indexBlueDark noBoxShadow">
                <input type="submit" name="confirm" value="Confirm" class="indexBlueDarker">
            </div>
        </form>

        <?php 
            include("../includes/footer.php")
        ?>
    </body>
</html>
