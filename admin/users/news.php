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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="/admin/css/style.css">
    <link rel="icon" href="/accuel/images/favicon.png">
    <title>Newsletter</title>
</head>

<body onload="logMail()">
    <?php
        include ("../includes/header.php");
    ?>
    <main>
        <h1 class="newsPageTitle">Newsletter</h1>
        <form action="send.php" method="post">
            <h4 class="newsCaption">Title</h4>
            <input type="text" name="subject" placeholder="Title of the newsletter" class="newsTitle">
            <h4 class="newsCaption">Contents</h4>
            <input type="text" name="message" placeholder="Paste the newsletter here..." class="newsletter">
            <center><button type="submit" class="newsletterSubmit">Send newsletter</button></center>
        </form>
        <h3>
            Newsletter history
        </h3>
        <?php
            $history = file_get_contents('/var/www/html/admin/users/log.txt');
            echo '<div class="newsletterHistory">' .$history . '</div>';
        ?>
    </main>
</body>
</html>