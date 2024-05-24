<?php
include ('../includes/smart_check.php');
include ("../includes/bdd.php");
require '../../lang.php';
$q = 'SELECT * FROM transferts';
$req = $pdo->prepare($q);
$req->execute();
$result = $req->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/style.css" rel="stylesheet">
    <link rel="icon" href="/accuel/images/favicon.png">
    <title>Add a transport</title>
</head>

<body>
    <?php
    session_start();
    include ('../includes/smart_check.php');
    include ("../includes/header.php");
    ?>
    <main>
        <h1 class="addProd">Add a transport</h1>
        <div class="addForm">
            <form action="check_transfert.php?action=add" method="post" enctype="multipart/form-data">
                <input type="text" name="name" placeholder="Name" class="inp">
                <input type="text" name="description" placeholder="Short description" class="inp">
                <input type="number" name="price" placeholder="Set price" class="inp">
                <input type="submit" value="Add" class="add">
        </div>

        <h1 class="addProd">Remove a transport</h1>
        <div class="addForm">
            <?php
            foreach ($result as $row) {
                echo '<label>Name</label>';
                echo '<form action="check_transfert.php?action=edit" method="post" enctype="multipart/form-data">
                   <input type="hidden" name="transfert" value="' . $row['id'] . '">
                     <input type="text" name="name" placeholder="Name" value="' . $row['name'] . '" class="inp">
                    <label>Description</label>
                <input type="text" name="description" placeholder="Short description" value=' . $row['description'] . ' class="inp">
                <label>Price</label>
                <input type="number" name="price" placeholder="Set price" value="' . $row['price'] . '" class="inp">
                   <input type="submit" value="edit">
               </form>';
                echo '<form action="check_transfert.php?action=delete" method="post" enctype="multipart/form-data">
                   <input type="hidden" name="transfert" value="' . $row['id'] . '">
                   <input type="submit" value="delete">
               </form>';
            }
            ?>
        </div>
    </main>
</body>

</html>