<?php
// Error reporting and session start
error_reporting(E_ALL);
ini_set('display_errors', 1); // Set to 1 for debugging, set to 0 for production
session_start();
require '../../lang.php';
// Include necessary files
include('/var/www/html/accuel/includes/bdd.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= lang('Your cart')?></title>
    <link rel="icon" href="../images/favicon.png">
    <link rel="stylesheet" type="text/css" href="../css/style.css?v=<?php echo time(); ?>" media='screen' />
</head>
<body>
    <header>
        <?php include('../includes/header.php'); ?>
    </header>
    <main>
        <?php
        if((isset($_SESSION['product']) && is_array($_SESSION['product'])) || (isset($_SESSION['transfer']) && is_array($_SESSION['transfer']))){
            $total = 0;
            echo '<center class="articleLine">';
            $i = 0;
            if(isset($_SESSION['product'])){
                foreach($_SESSION['product'] as $i => $product) {
                    if(isset($product['id'])) {
                        $id = $product['id'];
                        $sql = "SELECT visits.id, visits.time, visits.date, tours.tourname, tours.image, tours.price, visits.places
                                FROM visits 
                                INNER JOIN tours ON visits.id_product = tours.id_product 
                                WHERE visits.id = :id";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute(['id' => $id]);
                        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        foreach($result as $row) {
                            if($i % 2 == 0) {
                                echo '<div class="article odd">';
                            } else {
                                echo '<div class="article even">';
                            }
                            echo '<img src="../images/' . $row['tourname'] . '/' . $row['image'] . '" alt="tour_image" height="189px" width="309px" class="imgt">';
                            echo '<h1 class="tourName indexBlackFont">' . $row['tourname'] .'</h1>';
                            echo '<figcaption class="indexBlackFont">' . $row['date'] . ' at ' . $row['time'] . '</figcaption>';
                            echo '<figcaption id="price" class="indexBlackFont"> ' . $row['price'] . '€ per person for ' . $product['places'] . ' people, totalling ' . $product['places'] * $row['price'] . '€.</figcaption>';
                            echo '<button onclick="del(' . $i . ')" class="indexBlackFont indexWhiteDark">Cancel order</button>';
                            echo '</div>';
                            $total += $product['places'] * $row['price'];
                            if(isset($_GET['message'])) {//recived from cart.php
                                if($_GET['message'] == 'not_enough_places') {
                                    echo '<center class="error">Not enough places available for the selected tour!</center>';
                                    echo '<center class="error">Please select a different tour or reduce the number of people.</center>';
                                    echo 'Remaining places for this tour: ' . $product['placesL'];
                                }
                            }
                            ++$i;
                        }
                    } else {
                        echo "ID not set for tours at index $i";
                    }
                }
            }
            $i = 0;
            if(isset($_SESSION['transfer'])){
                foreach($_SESSION['transfer'] as $transfer) {
                    if(isset($transfer['name'])) {
                        $sql = "SELECT * FROM transferts WHERE name = :name";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute(['name' => $transfer['name']]);
                        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        foreach($result as $row) {
                            if($i % 2 == 0) {
                                echo '<div class="article odd">';
                            } else {
                                echo '<div class="article even">';
                            }
                            echo '<img src="../images/' . $row['name'] . '/' . $row['image'] . '" alt="tour_image" height="189px" width="309px" class="imgt">';
                            echo '<h1 class="tourName indexBlackFont">' . $row['name'] .'</h1>';
                            echo '<figcaption class="indexBlackFont"> ' . $row['price'] . '€ per person for ' . $transfer['duration'] . ' hours, totalling ' . $transfer['duration'] * $row['price'] . '€.</figcaption>';
                            echo '<button onclick="del(' . $i . ')" class="indexBlackFont indexWhiteDark">Cancel order</button>';
                            echo '</div>';
                            $total += $transfer['duration'] * $row['price'];
                            ++$i;
                        }
                    } else {
                        echo "Name not set for transfer at index $i";
                    }
                }
            }
            echo '</center>';
            $_SESSION['total'] = $total;
            ?>
            <center>
                <form action="check_out.php" method="post">
                    <button type="submit" name="checkout" class="checkoutButton indexBlueDarker">Checkout</button>
                </form>
            </center>
        <?php
        } else {
            echo '<center class="emptyTitle indexBlackFont">'. lang('Your cart is empty').'!</center>';
            echo '<center class="emptySubtitle indexBlackFont">' . lang('Book a tour to view it here') . '!</center>';
            echo '<center class="emptyButtons">
                    <div class="emptyPage">
                        <a href="/accuel/visites/visite.php" class="indexBlueDark">'
                            . lang('Take a look at our products').'
                        </a>
                    </div>
                    <div class="emptyPage indexBlackFont emptyOr">'
                        . lang('or').'
                    </div>
                    <div class="emptyPage">
                        <a href="/index.php" class="indexBlueDark">'
                            .lang('Back to the main page').'
                        </a><br><br><br><br><br><br><br><br>
                    </div>
                </center>';
        }
        ?>

        <?php include('../includes/footer.php'); ?>
    </main>
</body>
</html>
