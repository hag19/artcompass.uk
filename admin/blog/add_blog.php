<?php
            error_reporting(E_ALL);
            ini_set('display_errors', 1);
            include("../includes/smart_check.php");
            include ("../includes/header.php");
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
    <div class="addForm">
        <!-- Form for editing tour details -->
        <form action="check_add.php" method="post" enctype="multipart/form-data">
            <input type="text" name="title" placeholder="Title">
            <input type="text" name="description" placeholder=" description">
            <input type="file" name="image" accept="image/jpeg image/png image/gif" > <br>
            <input type="submit" name="add" value="Add">
        </form>
    </div>
</main>
</body>

</html>
