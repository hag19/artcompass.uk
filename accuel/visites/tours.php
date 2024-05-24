<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require '../../lang.php';
include('../includes/bdd.php');

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visites guidées</title>
    <link rel="icon" href="../images/favicon.png">
    <link rel="stylesheet" type="text/css" href="../css/style.css?v=<?php echo time(); ?>" media='screen' />
</head>

<body>
    <?php include ("../includes/header.php") ?>
    <main>
        <h1 class="bookTour indexBlackFont">
            Book your tour right now!
        </h1>
        <section class="tourPageGroup">
            <figure>
                <?php
                $sql = 'SELECT * FROM tours WHERE id_product = :id';
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    "id" => $_GET['message']
                ]);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                        echo '<center> ';
                        echo '<h1 class="indexBlackFont">' . $result['title'] . '</h1>';
                        echo '<img src="../images/' . $result['tourname'] . '/' . $result['image'] . '" alt="tour_image" height="310px" width="515px" class="imgt">';
                        echo '<div class="tourEverything indexBlueDark noBoxShadow">';
                        echo '<figcaption class="visitSpacing">' . $result['description'] . '</figcaption>';
                        echo '<figcaption id="price" class="visitSpacing"> ' . $result['price'] . '€ per person.</figcaption>';
                    
                ?> 
                <form method="post" action="check.php?id=<?php echo $result['id_product']; ?>">
                    <div class="visitSpacing tourParameters">
                        <input type="hidden" name="name" id="name" value="<?php echo $result['id_product']; ?>">
                        <input type="date" name="date" id="date" min="<?php echo date('Y-m-d'); ?>" class="indexWhiteDark indexBlackFont">
                        <input type="number" name="places" placeholder="Amount of people" min="1" id="N_places" class="tourAmount indexWhiteDark indexBlackFont">
                        <div id="total" class="tourTotal"></div>
                        <input type="submit" name="cart" value="Add to cart" class="indexWhiteDark indexBlackFont">
                    </div>
                    <div id="time" class="tourChoice"></div>
                </div>
                </form>
                <?php 
                    $sql = "SELECT reviews.name, stars, comment, created_at FROM reviews INNER JOIN orders ON orders.id_product = :id where orders.id_product = :id ORDER BY stars DESC";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([
                        "id" => $result['id_product']
                    ]);
                    $review = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    echo '<iframe src="'.$result['map'].'" class="tourIframe"></iframe>'; 
                    $i=0;
                    $j=0;
                    echo '<h1 class="reviewsSection indexBlackFont">Reviews</h1>'; 
                    echo '<div class="reviews">';
                        foreach($review as $row){
                            if($i == 0){
                                if($j % 2 == 0){
                                    echo '<div class="review odd">';
                                } else {
                                    echo '<div class="review even">';
                                }
                                echo '<div class="reviewRating">';
                                echo '<div class="reviewName indexBlackFont">' . $row['name'] . '</div>';
                                for($k = 0; $k < $row['stars']; ++$k) {
                                    echo '<div class=stars>★</div>';
                                }
                                echo '</div>';
                                echo '<div class="reviewComment indexBlackFont">' . $row['comment'] . '</div>';
                                echo '<div class="reviewDate">' . $row['created_at'] . '</div>';
                                echo '</div>';
                                $i++;
                                $j++;
                            } else {
                                $i++;
                                $j++;
                                if($i == 5){
                                    $i = 0;
                                }
                            }
                        }
                    echo '</div>';
                ?>
            </figure>
            <script type="text/javascript" src="visits.js"></script>
        </section>
    </main>
    <?php
    include ('../includes/footer.php');
    ?>
</body>

</html>