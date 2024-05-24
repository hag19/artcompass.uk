<?php 
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        session_start();
        include('../includes/bdd.php');
        include('../includes/header.php'); 
$sql = "SELECT token from orders where id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $_GET['id']]);
$token = $stmt->fetch(PDO::FETCH_ASSOC);
if($_GET['token'] == $token['token'] ){
    $id = $_GET['id'];
    $token = $_GET['token'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>Document</title>
</head>
<body>
<form action="verif_maike.php" method="post">
    <input type="text" name="name" placeholder="youre name">
    <input type="number" min="1" max="5" name="stars" placeholder="stars">
    <input type="text" name="comment" placeholder="youre comment">
    <input type="hidden" name="id" value="<?php echo $id; ?>">
    <input type="hidden" name="token" value="<?php echo $token; ?>">
    <input type="submit" value="send">
    </form>
</body>
</html>

    <?php
}
else {
    header('Location: ../../admin/users/smart.html');
    exit;
}