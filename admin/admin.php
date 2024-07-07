<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
     include('includes/smart_check.php');
    if($_SESSION["user"] != "admin"){
        header("location: /admin/users/smart.php?message=Thought_you_were_smart_there,_huh?");
        exit;
    }
    require '../lang.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="icon" href="/accuel/images/favicon.png">
    <title>Admin panel</title>
</head>

<body onload="loadLog()">
    <script src="page/main.js"></script>
    <?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    include ("includes/header.php");
    include ("includes/bdd.php");
    $q = 'SELECT * FROM tours';
    $req = $pdo->prepare($q);
    $req->execute();
    $result_tours = $req->fetchAll(PDO::FETCH_ASSOC);

    $sql = 'SELECT sum(total_price) as sum FROM orders where id_product = :id_product';
    $req = $pdo->prepare($sql);
    $req->execute([
        'id_product' => 99999
    ]);
    $result = $req->fetch(PDO::FETCH_ASSOC);
    ?>
    <main>
        <h1>
            <center>
                Art Compass Tours Dashboard
            </center>
        </h1>
            <section class="adminSection">
                <h3>Tour statistics</h3>
                <div>
                    <?php
                    echo 'Sold transfers: ' . $result['sum'] . '€';
                    echo '<div class="tourColumn">';
                        foreach ($result_tours as $row) {
                            echo '<div class="tourSpacing">';
                                echo '<h4>' . $row['title'] . '</h4>';
                                $q = 'SELECT SUM(places_b) as bought FROM visits WHERE id_product = :id_product';
                                $req = $pdo->prepare($q);
                                $req->execute([
                                    'id_product' => $row['id_product']
                                ]);
                                $result = $req->fetchAll(PDO::FETCH_ASSOC);
                                foreach($result as $bought){
                                    echo '<div>Places sold: ' . $bought['bought'] . '</div>';
                                }
                                echo '<div>Profits grossed: ' . $bought['bought'] * $row['price'] . '€</div>';
                            echo '</div>';
                        }
                    echo '</div>';
                    $q_bought = 'SELECT SUM(places_b) as bought FROM visits INNER JOIN tours ON visits.id_product = tours.id_product';
                    $req_bought = $pdo->prepare($q_bought);
                    $req_bought->execute();
                    $result_bought = $req_bought->fetch(PDO::FETCH_ASSOC);
                    echo '<div>Total places sold: ' . $result_bought['bought'] . '</div>';
                    ?>
                </div>
            </section>
            <div class="adminPanel">
            <section class="adminSection">
                <h3>Users</h3>
                <p>
                    <?php
                    $q = 'SELECT * FROM users';
                    $req = $pdo->prepare($q);
                    $req->execute();
                    $res = $req->fetchAll(PDO::FETCH_ASSOC);
                    echo '<div class="userCols">';
                    foreach($res as $row) {
                        echo '<div class="userInfo">';
                            echo '<div class="userName">' . $row['username'] . '</div>';
                            echo '<div class="userFirstName">' . $row['fname'] . '</div>';
                            echo '<div>' . $row['lname'] . '</div>';
                        echo '</div>';
                    }
                    echo '</div>';
                    ?>
                </p>
            </section>
        </div>
        <div class="adminPanel">
            <section class="adminSection">
                <h3>Logs</h3>
                <button onclick="loadLog()">reload</button>
                <p id="log" class="log"></p>
            </section>
        </div>
    </main>
</body>
</html>