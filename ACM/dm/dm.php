<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('../blog/blog_class.php');
include('../../accuel/includes/bdd.php');
include('../includes/user_not.php');
$dm = new dm($pdo);
$dms = $dm->getDm();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="icon" href="../../accuel/images/favicon.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="/admin/css/style.css" media='screen'>
</head>
<body>
    <header>
        <?php include ('../includes/ACMheader.php'); ?>
    </header>
    <main>
        <h1 class="text-center p-5">Messages</h1>
        <div class="card col-8 mx-auto shadow p-3 mb-5 bg-body-tertiary rounded">
            <div class="card-body">
                <h5 class="card-title">Messages</h5>
                <p class="card-text">
                    <?php foreach($dms as $dm){ ?>
                    <a href="messages.php?id=<?= $dm['id'] ?>" class="btn btn-primary"><?= $dm['username'] ?></a>
                    <br>
                    <br>
                    <?php } ?>
                </p>
            </div>
        </div>
</body>
</html>