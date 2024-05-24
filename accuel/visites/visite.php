<?php
session_start();
require '../../lang.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);
include ('../includes/bdd.php');
$sql = 'SELECT * FROM tours';
$stmt = $pdo->prepare($sql);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= lang('Guided tours')?></title>
    <link rel="icon" href="../images/favicon.png">
    <link rel="stylesheet" type="text/css" href="../css/style.css?v=<?php echo time(); ?>" media='screen' />
</head>

<body>
    <?php include ("../includes/header.php") ?>

    <main>
        <!--<select id="filter">
            <option value="1">Lowest prices</option>
            <option value="2">Fanciest tours</option>
            <option velue="3">Alphabetical order</option>
        </select> -->

        <form action="" method="GET">
            <label for="sort"><?= lang('Order by')?></label>
            <select name="sort" id="sort">
                <option value="tourname"><?= lang('Alphabetical order')?>
                </option>
                <option value="price DESC"><?= lang('Fanciest tours')?>
                </option>
                <option value="price">
                    <?= lang('Lowest prices')?></option>
            </select>
            <button type="submit"><?= lang('Sort')?></button>
        </form>
        <h1 class="popular indexBlackFont">
            <?= lang('Check out our best-sellers')?>!
        </h1>
        <center>
            <input id="search_input" type="text" oninput="search()" placeholder="<?= lang('Search a tour')?>..." class="searchBar">
        </center>

        <?php if (!isset($_GET['sort'])) {
            $sort = 'tourname';
            }else{
            if ($_GET['sort'] == 'tourname') {
                $sort = 'tourname';
            } elseif ($_GET['sort'] == 'price DESC') {
                $sort = 'price DESC';
            } elseif ($_GET['sort'] == 'price') {
                $sort = 'price';
            }
        }
            

        $q = 'SELECT * FROM tours ORDER BY ' . $sort;
        $req = $pdo->prepare($q);
        $req->execute();
        $result = $req->fetchAll(PDO::FETCH_ASSOC);

        echo '<section class="tourPageGroup indexBlackFont">
       <figure id="html_result">';
        foreach ($result as $row) {
            echo '<center><a href="tours.php?message=' . $row['id_product'] . '" class="tourPage">';
            echo '<img src="../images/' . $row['tourname'] . '/' . $row['image'] . '" alt="tour_image" height="300rem" width="495rem">';
            echo '<figcaption class="indexBlackFont">' . $row['title'] . '</figcaption>';
            echo '</a></center>';
        }
        echo '</figure>
</section>';

        ?>
        <script src="visits.js"></script>
    </main>
    <?php
    include ('../includes/footer.php');
    ?>
</body>

</html>