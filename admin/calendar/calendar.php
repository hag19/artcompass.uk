<?php
include ('../includes/smart_check.php');
include ("../includes/bdd.php");
require '../../lang.php';
$q = 'SELECT * FROM users WHERE role= :role';
$stmt = $pdo->prepare($q);
$stmt->execute([
    'role' => 'guide',
]);
$guide = $stmt->fetchAll(PDO::FETCH_ASSOC);

$q1 = 'SELECT * FROM visits INNER JOIN tours ON tours.id_product = visits.id_product ORDER BY date';
$stmt1 = $pdo->prepare($q1);
$stmt1->execute();
$tours = $stmt1->fetchAll(PDO::FETCH_ASSOC);
$q2 = 'SELECT * FROM orders inner join tours on tours.id_product = orders.id_product';
$stmt2 = $pdo->prepare($q2);
$stmt2->execute();
$orders = $stmt2->fetchAll(PDO::FETCH_ASSOC);
$q3 = 'SELECT * FROM admin_message';
$stmt3 = $pdo->prepare($q3);
$stmt3->execute();
$messages = $stmt3->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendar</title>
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <link rel="icon" href="/accuel/images/favicon.png">
</head>

<body>
    <?php include ('../includes/header.php') ?>
    <center>
        <h1>Calendar</h1>
    </center>

<center><h1>All tour guides</h1></center>
    <div class="adminPanel">
        
        <div class="adminSection">

            <table>
                <tr>
                    <th>Username</th>
                    <th>First name</th>
                    <th>Last name</th>
                </tr>
                <?php
                foreach ($guide as $row) {
                    echo '<div>';
                    echo '<tr>';
                    echo '<td>' . $row['username'] . '</td>';
                    echo '<td>' . $row['fname'] . '</td>';
                    echo '<td>' . $row['lname'] . '</td>';
                    echo '</tr>';
                    echo '</div>';
                } ?>
            </table>
        </div>
    </div>

    
<center><h1>All orders</h1></center>
    <div class="adminPanel">
        
        <div class="adminSection">

            <table>
                <tr>
                    <th>lname</th>
                    <th> email</th>
                    <th> message</th>
                    <th> date</th>
                    <th> youre response</th>
                    <th> send</th>

                </tr>
                <?php
                foreach ($messages as $row) {
                    echo '<div>';
                    echo '<tr>';
                    echo '<td>' . $row['name'] . '</td>';
                    echo '<td>' . $row['email'] . '</td>';
                    echo '<td>' . $row['message'] . '</td>';
                    echo '<td>' . $row['date'] . '</td>';
                    ?>

                    <form method="post" action="../../accuel/news/message.php">
                        <td><input type="text" name="response" placeholder="response"></td>
                        <td><input type="submit" name="send_email" value="send"></td>
                        <input type="hidden" name="id" value="<?php echo $row['id'] ?>">
                    <?php

                    echo '</tr>';
                    echo '</div>';
                } ?>
            </table>
        </div>
    </div><center><h1>messages from users to admin</h1></center>
    <div class="adminPanel">
        
        <div class="adminSection">

            <table>
                <tr>
                    <th>fname</th>
                    <th>lname</th>
                    <th> email</th>
                    <th> nomber</th>
                    <th> tourname</th>
                    <th> total_price</th>

                </tr>
                <?php
                foreach ($orders as $row) {
                    echo '<div>';
                    echo '<tr>';
                    echo '<td>' . $row['name'] . '</td>';
                    echo '<td>' . $row['lastname'] . '</td>';
                    echo '<td>' . $row['email'] . '</td>';
                    echo '<td>' . $row['phone'] . '</td>';
                    echo '<td>' . $row['tourname'] . '</td>';
                    echo '<td>' . $row['total_price'] . '</td>';
                    echo '</tr>';
                    echo '</div>';
                } ?>
            </table>
        </div>
    </div>

<center><h1>All visits</h1></center>
    <div class="adminPanel">
        
        <div class="adminSection">
            <table>
                <tr>
                    <th>Tour name</th>
                    <th>Guide Name</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Places bought</th>
                </tr>
                <?php
                foreach ($tours as $row) {
                    $currentDate = date('Y-m-d');
                    $visitDate = $row['date'];

                    if ($visitDate >= $currentDate) {
                        echo '<tr>';
                        echo '<td class="calendarC">' . $row['title'] . '</td>';
                        echo '<td class="calendarC">' . $row['username'] . '</td>';
                        echo '<td class="calendarC">' . $row['date'] . '</td>';
                        echo '<td class="calendarC">' . $row['time'] . '</td>';
                        if ($row['places_b'] == null) {
                            echo '<td class="calendarC">None</td>';
                        } else {
                            echo '<td class="calendarC">' . $row['places_b'] . '</td>';
                        }
                        echo '</tr>';
                    }
                }
                ?>


            </table>
        </div>
    </div>
</body>

</html>