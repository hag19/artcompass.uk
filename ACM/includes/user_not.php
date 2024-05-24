<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include ('../../accuel/includes/bdd.php');

$sql = 'SELECT id FROM users WHERE ban = 1';
$stmt = $pdo->prepare($sql);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Check if user is banned
$bannedUserIds = array_column($result, 'id');
if (!isset($_SESSION['id']) || (isset($_SESSION['id']) && in_array($_SESSION['id'], $bannedUserIds))) {
    header('Location: /accuel/connex/connexion.php?message=please login or create an account');
    exit;
}
?>
