<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include ('../includes/smart_check.php');
require '../../lang.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="/admin/css/style.css">
    <link rel="icon" href="/accuel/images/favicon.png">
    <title>Edit page</title>
</head>

<body>
    <main>
        <?php

        include ("../includes/header.php");
        include ("../includes/bdd.php");

        $sql = 'SELECT * FROM tours';
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($result as $row) {
            $id = $row['id_product'];
            echo '<div class="editTour">';
            echo '<div class="editTourGroup">';
            echo '<h1>';
            echo $row['title'];
            echo '</h1>';
            $dir = '../../accuel/images/' . $row['tourname'];
            $scandir = scandir($dir);
            foreach ($scandir as $file) {
                if (!in_array($file, array(".", ".."))) {
                    echo '<img id="pfp" src="' . $dir . '/' . $file . '" alt="Image" width="375px" height="250px" class="editTourImg">';
                    break;
                }
            }
            echo '<div class="editTourDesc">';
            echo $row['description'];
            echo '</div>';
            echo '<div class="editTourPrice">';
            echo $row['price'] . '€';
            echo '</div>';
            echo '</div>';
            ?>

            <div class="editTourForm">
                <form action="visits.php?message=<?php echo $id ?>" method="post">
                    <input type="submit" name="visits" value="Schedule a tour">
                </form>
                <form action="edit.php" method="post">
                    <input type="hidden" name="id_product" value="<?php echo $id; ?>">
                    <input type="submit" name="edit" value="Edit this page">
                    <input type="hidden" name="tourname" value="<?php echo $row['tourname']; ?>">
                    <input type="submit" name="deleteALL" value="Delete this page">
                </form>
            </div>
            <?php
            echo '</div>';
        } ?>
    </main>
</body>

</html>