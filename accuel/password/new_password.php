<?php
$token = $_GET["token"];
$token_hash = hash('sha256', $token);
include('../includes/bdd.php');
$sql = "SELECT * FROM users
WHERE reset_token_hash = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$token_hash]);
$result = $stmt->fetchAll();
if ($result === null) {
    die("not found token");
}
$tokenData = $result[0];
//var_dump($tokenData);
$current_time = strtotime(time());
$token_expiry_time = strtotime($tokenData["reset_token_expires_at"]);
if ($token_expiry_time <= $current_time) {
    die("Token has expired");
    $sql = "UPDATE users
        SET reset_token_expires_at = NULL,
        reset_hash_token = NULL
        WHERE id = ?";
$stmt = $pdo->prepare($sql);
$result = $stmt->execute([$tokenData["id"]]);
}
echo 'token is valid';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
</head>
<body>
    <form action="process_new_password.php" method="post">
        <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
        <input type="password" id="password" name="password">
        <input type="password" id="password_conf" name="password_conf">
        <input type="submit" value="submit">
    </form>
</body>

</html>

