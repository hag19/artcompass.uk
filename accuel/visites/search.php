<?php

ini_set('display_errors', 1);

include ('../includes/bdd.php');
if(isset($_POST['filter'])){
$filter = $_POST['filter'];
    $validFilters = ['1', '2', '3']; // Define valid filter values


if (in_array($filter, $validFilters)) {
    
    switch ($filter) {
        case '1':
            $sql = 'SELECT * FROM tours ORDER BY price ASC';
            break;
        case '2':
            $sql = 'SELECT * FROM tours ORDER BY price DESC';
            break;
        case '3':
            $sql = 'SELECT * FROM tours ORDER BY tourname ASC';
            break;
    }
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($result as $row) {
        echo '<center><a href="tours.php?message=' . $row['tourname'] . '" class="tourPage">';
        echo '<img src="../images/' . $row['tourname'] . '/' . $row['image'] . '" alt="tour_image" height="300rem" width="495rem">';
        echo '<figcaption class="indexBlackFont">' . $row['title'] . '</figcaption>';
        echo '</a></center>';
    }
        }
} else if (isset($_GET["name"]) && !empty($_GET['name'])) {
    $name =htmlspecialchars($_GET["name"],ENT_QUOTES, 'UTF-8');
    $req = $pdo->prepare('SELECT * FROM tours WHERE title LIKE ?');

    $success = $req->execute([
        "%" . $name ."%"
    ]);
    
    if ($success) {
        $result = $req->fetchAll(PDO::FETCH_ASSOC);

        foreach ($result as $row) {
            echo '<center><a href="tours.php?message=' . $row['tourname'] . '" class="tourPage">';
            echo '<img src="../images/' . $row['tourname'] . '/' . $row['image'] . '" alt="tour_image" height="300rem" width="495rem">';
            echo '<figcaption class="indexBlackFont">' . $row['title'] . '</figcaption>';
            echo '</a></center>';
        }
    }
}else{
    $sql = 'SELECT * FROM tours';
$stmt = $pdo->prepare($sql);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($result as $row) {
    echo '<center><a href="tours.php?message=' . $row['tourname'] . '" class="tourPage">';
        echo '<img src="../images/' . $row['tourname'] . '/' . $row['image'] . '" alt="tour_image" height="300rem" width="495rem">';
        echo '<figcaption class="indexBlackFont">' . $row['title'] . '</figcaption>';
        echo '</a></center>';

}
}