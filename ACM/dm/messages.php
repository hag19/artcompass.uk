<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('../blog/blog_class.php');
include('../../accuel/includes/bdd.php');
include('../includes/user_not.php');

$dm = new dm($pdo);
$dms = $dm->getMessages($_GET['id']);
$check = $dm->check_user($_GET['id']);
$friends = new friends($pdo);
foreach($dms as $dm){
        $id = $dm['id_user'];
        break;
}   
$friend = $friends->getProfile($id);
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

                    <?php
                    echo $friend['username'];
                    echo'<br>';
                    foreach($dms as $dm){ 
                        echo $dm['message'];
                        echo'<br>';?>
                    <br>
                    <br>
                    <?php } ?>
                    <form action="../friend/actions.php?action=add_m" method="post">
                        <input type="text" name="message" class="form-control" placeholder="Message">
                        <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
                        <button type="submit" class="btn btn-primary mt-3">Envoyer</button>
                </p>
            </div>
        </div>
    </main>
    
</body>
</html>