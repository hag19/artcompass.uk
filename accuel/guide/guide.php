<?php
require '../../lang.php';
session_start();
include ("../includes/bdd.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guide panel</title>
    <link rel="stylesheet" type="text/css" href="/admin/css/style.css">
    <link rel="icon" href="/accuel/images/favicon.png">
</head>

<body>

    <?php include ("../includes/header.php"); ?>

    <main>
        <?php

        $q = 'SELECT * FROM users WHERE id = :id';
        $req = $pdo->prepare($q);
        $req->execute([
            'id' => $_SESSION['id'],
        ]);
        $result = $req->fetch(PDO::FETCH_ASSOC);
        $user = $result[0];

        ?>
        <h1>
            <center>Welcome <?= $_SESSION['name'] ?> !</center>
        </h1>
        <div class="adminSection">

            <h1>Number of tours done: </h1><?php $q = 'SELECT COUNT(id) as count FROM visits WHERE username = :username ';
            $req = $pdo->prepare($q);
            $req->execute([
                'username' => $_SESSION['name'],
            ]);
            $result = $req->fetch(PDO::FETCH_ASSOC);
            echo 'Total: ' . $result['count'];
            ?>

        </div>

        <div class="adminSection">
            <h1>Money made in total: </h1>
            <?php

            $q = 'SELECT * FROM visits INNER JOIN tours ON tours.id_product = visits.id_product WHERE username = :username';
            $stmt = $pdo->prepare($q);
            $stmt->execute([
                'username' => $_SESSION['name'],
            ]);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($result as $row) {
                $pay += $row['places_b'] * $row['price'];
            }

            if ($pay == null) {
                echo 'Overall: 0€, What are you waiting for ?';
            } else
                echo 'Overall: ' . $pay . '€';


            foreach ($result as $row) {
                echo '<div>';
                echo $row['title'] . ': ' . $row['places_b'] * $row['price'] . '€';
                echo '</div>';
            }


            ?>
        </div>

        <div class="adminSection">
            <h1>Upcoming tours:</h1>
            <?php
            $q = 'SELECT * FROM visits INNER JOIN tours ON tours.id_product = visits.id_product WHERE username = :username';
            $req = $pdo->prepare($q);
            $req->execute([
                'username' => $_SESSION['name'],
            ]);
            $result = $req->fetchAll(PDO::FETCH_ASSOC);

            echo '<table>';
            echo '<tr>';
            echo '<th>Place</th>';
            echo '<th>Date</th>';
            echo '<th>Time</th>';
            echo '</tr>';
            foreach ($result as $row) {
                $currentDate = date('Y-m-d');
                $visitDate = $row['date'];

                if ($visitDate >= $currentDate) {
                    echo '<tr>';
                    echo '<td>' . $row['title'] . '</td>';
                    echo '<td>' . $row['date'] . '</td>';
                    echo '<td>' . $row['time'] . '</td>';
                    echo '</tr>';
                }
            }


            if (empty($result)) {
                echo '<p>No tour at the moment </p>';
            }

            echo '</table>';

            ?>
        </div>
    </main>

    <?php include ("../includes/footer.php"); ?>
</body>

</html>