<?php
session_start();
include ('../includes/smart_check.php');
require '../../lang.php';
include ("../includes/bdd.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/style.css" rel="stylesheet">
    <script src="../page/main.js" defer></script>
    <link rel="icon" href="/accuel/images/favicon.png">
    <title>Users</title>
</head>

<body>
    <?php

    include ('../includes/header.php');

    $sql = 'SELECT username, id, fname, lname, role, ban, email FROM users  ORDER BY id ASC';
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo '<div class="userPanel">';
    echo '<form action="../FPDF/export.php", target="_blank">
            <center class="exportButton">
                <input type="submit" value="Export info to PDF">
            </center>
        </form>';
    echo '<center>
        <input id="search_input" type="text" oninput="search_users()" placeholder="Search a user..." class="searchBar">
    </center>';

    echo '<div id="html_result">';
    $counter = 1;
    foreach ($result as $row) {
        if ($counter % 2 == 0) {
            $oddeven = "even";
        } else {
            $oddeven = "odd";
        }
        echo '<div class="usercard ' . $oddeven . '">
                    <div class="uCard1">' . $row['id'] . '</div>
                    <div class="uCard1">' . $row['username'] . '</div>
                    <div class="uCard1">' . $row['fname'] . '</div>
                    <div class="uCard1">' . $row['lname'] . '</div>
                    <div class="uCard1">' . $row['email'] . '</div>';
        if ($row['email'] != 'admin@gmail.com') {
            if ($row['role'] == 'admin') {
                $adminStatus = ["demoteAdmin", "Demote admin"];
            } else {
                $adminStatus = ["makeAdmin", "Make admin"];
            }
            if ($row['role'] == 'guide') {
                $guideStatus = ["demoteGuide", "Demote guide"];
            } else {
                $guideStatus = ["makeGuide", "Make guide"];
            }
            if ($row['ban'] == 1) {
                $banStatus = ["unbanUser", "Unban user"];
            } else {
                $banStatus = ["banUser", "Ban user"];
            }
            echo '<div class="uCard3"><button onclick="' . $guideStatus[0] . '(' . $row['id'] . ')" class="uCard2">' . $guideStatus[1] . '</button>';
            echo '<button onclick="' . $adminStatus[0] . '(' . $row['id'] . ')" class="uCard2">' . $adminStatus[1] . '</button>';
            echo '<button onclick="' . $banStatus[0] . '(' . $row['id'] . ')" class="uCard2">' . $banStatus[1] . '</button>';
            echo '<button onclick="deleteAccount(' . $row['id'] . ')" class="uCard2 deleteAcc">Delete account</button></div>';
        } else {
            echo '<div class="headAdmin">Cannot manage this head administrator.</div>';
        }
        echo '</div>';
        $counter = $counter + 1;
    }
    echo '</div>';
    echo '</div>';

    // ---------------------------------------------
    // MAKE GUIDE
    if (explode("_", $_GET["message"])[0] == "g") {
        $id = explode("_", $_GET["message"])[1];
        $sql = 'UPDATE users SET role = "guide" WHERE id = ' . $id . '';
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
    }
    if (explode("_", $_GET["message"])[0] == "dg") {
        $id = explode("_", $_GET["message"])[1];
        $sql = 'UPDATE users SET role = NULL WHERE id = ' . $id . '';
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
    }
    // 
    // ---------------------------------------------
    // MAKE ADMIN
    if (explode("_", $_GET["message"])[0] == "a") {
        $id = explode("_", $_GET["message"])[1];
        $sql = 'UPDATE users SET role = "admin" WHERE id = ' . $id . '';
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        header('location:users.php');
        exit;
    }
    if (explode("_", $_GET["message"])[0] == "da") {
        $id = explode("_", $_GET["message"])[1];
        $sql = 'UPDATE users SET role = "guide" WHERE id = ' . $id . '';
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        header('location:users.php');
        exit;
    }
    // 
    // ---------------------------------------------
    // BAN USER
    if (explode("_", $_GET["message"])[0] == "b") {
        $id = explode("_", $_GET["message"])[1];
        $sql = 'UPDATE users SET ban = 1 WHERE id = ' . $id . '';
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $sql = 'UPDATE users SET role = NULL WHERE id = ' . $id . '';
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        header('location:users.php');
        exit;
    }
    if (explode("_", $_GET["message"])[0] == "ub") {
        $id = explode("_", $_GET["message"])[1];
        $sql = 'UPDATE users SET ban = 0 WHERE id = ' . $id . '';
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        header('location:users.php');
        exit;
    }
    // 
    // ---------------------------------------------
    // DELETE USER
    if (explode("_", $_GET["message"])[0] == "d") {
        $id = explode("_", $_GET["message"])[1];
        $sql = 'DELETE FROM users WHERE id = :id';
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        header('location:users.php');
        exit;
    }
    // 
    // ---------------------------------------------
    
    ?>
    <script src="users.js"></script>
</body>

</html>