<?php
    
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    session_start();
    include('accuel/includes/bdd.php');
    require 'lang.php';
    $sql = 'SELECT * FROM tours';
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Art Compass Tours</title>
        <meta charset="utf-8">
        <meta name="description" content="Bienvenue sur Art Compass Tours !">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" href="/accuel/images/favicon.png">
        <link rel="stylesheet" type="text/css" href="/accuel/css/style.css?v=<?php echo time(); ?>" media='screen' />
    </head>
    <body>
        <?php 
            include("accuel/includes/header.php");
        ?> 
        <main>
            <section class="reserv noBoxShadow">
                <h1>
                    <a href="https://artcompass.uk/accuel/visites/visite.php" class="a_reserv"><?= lang('Click to book your tour')?>!</a>
                </h1>
            </section>
        <section id="reservation">
            <div class="offers indexBlackFont">
                <?= lang('Have a look at our best-sellers')?>!
            </div>
            <div class="best_sellers_group">
                <?php 
                    $stop = 0;
                    echo '<figure class="best_sellers">';
                        foreach ($result as $row) {
                            echo '<a href="accuel//visites/tours.php?message=' . $row['id_product'] . '" class="">';
                            echo '<img src="accuel/images/' . $row['tourname'] . '/' . $row['image'] . '" alt="tour_image" height="300px" width="495px">';
                            echo '<figcaption>' . $row['title'] . '</figcaption>';
                            echo '</a>';
                            $stop = $stop + 1;
                            if($stop >= 2) {
                                break;
                            }
                        }
                    echo '</figure>';
                        $stop = 0;
                        echo '<figure class="best_sellers">';
                        foreach ($result as $row) {
                            if($stop >= 2 && $stop < 4) {
                                echo '<a href="accuel//visites/tours.php?message=' . $row['id_product'] . '" class="">';
                                echo '<img src="accuel/images/' . $row['tourname'] . '/' . $row['image'] . '" alt="tour_image" height="300px" width="495px">';
                                echo '<figcaption>' . $row['title'] . '</figcaption>';
                                echo '</a>';
                            }
                            $stop = $stop + 1;
                            if($stop >= 4) {
                                break;
                            }
                        } 
                    echo '</figure>';
                ?>
             </div>
        </figure>
        </section>
        
        <section class="newsletter indexBlueDark">
            <h1>
                <?= lang('Subscribe to our newsletter')?> !
            </h1>
        <form action="accuel/news/news.php" method="post" enctype="multipart/form-data" class="p-2">
            <input type="email" name="newsletter" placeholder="<?= lang('Enter your email')?>" class="emailInput">
            <input type="submit" value="<?= lang('Subscribe')?>", class="subscribe indexBlueDarker">
        </form>
        </section>
        <form action="/accuel/news/message.php" method="post"class="p-2">
            <input type="text" name="name" placeholder="<?= lang('Enter your name')?>" class="nameInput">
            <input type="text" name="message" placeholder="<?= lang('Enter your message')?>" class="messageInput">
            <input type="email" name="email" placeholder="<?= lang('Enter your email')?>" class="emailInput">
            <input type="submit" name="send" value="<?= lang('Send')?>" class="send indexBlueDarker">
        </form>
        <section class="ratings">
            <h1 class="indexBlackFont">
                <?= lang('Feedback from our esteemed clients')?>
            </h1>
                <?php
                    $sql = "SELECT stars, name, comment, created_at FROM reviews ORDER BY stars DESC";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute();
                    $review = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    $i=0;
                    $j=0;
                    echo '<div class="reviewsBrief">';
                        foreach($review as $row){
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
                            $j++;
                        }
                    echo '</div>';
                ?>
            </div>
        </section>
        </main>
        <script defer src="https://app.fastbots.ai/embed.js" data-bot-id="cluoeuf6z03kxn8b0w992pfdu"></script>
        <?php include("accuel/includes/footer.php")?>
    </body>
</html>
