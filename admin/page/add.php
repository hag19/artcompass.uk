<?php
session_start();
include ('../includes/smart_check.php');
require '../../lang.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/style.css" rel="stylesheet">
    <link rel="icon" href="/accuel/images/favicon.png">
    <title>Add a tour</title>
</head>

<body>
    <?php

    include ("../includes/header.php");
    ?>
    <main>
        <h1 class="addProd">Add a product</h1>
        <div class="addForm">
            <form action="add_check.php" method="post" enctype="multipart/form-data">
                <input type="text" name="name" placeholder="Name" class="inp">
                <input type="text" name="title" placeholder="Title" class="inp">
                <input type="text" name="description" placeholder="Short description" class="inp">
                <input type="number" name="price" placeholder="Set price" class="inp">
                <input type="text" name="mape" placeholder="Map" class="inp">
                <input type="file" name="image" accept="image/jpeg image/png image/gif image/pdf" requier> <br>
                <input type="submit" value="Add" class="add">
        </div>
    </main>
</body>

</html>