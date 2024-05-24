<?php

ini_set('display_errors', 1);

include ('../includes/bdd.php');

if (isset($_GET["name"]) && !empty($_GET['name'])) {

    $req = $pdo->prepare('SELECT * FROM users WHERE username LIKE ?');

    $success = $req->execute([
        "%" . $_GET['name'] . "%"
    ]);

    if ($success) {
        $result = $req->fetchAll(PDO::FETCH_ASSOC);
        $counter = 1;
        foreach($result as $row){
            if($counter % 2 == 0) {
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
                if($row['email'] != 'admin@gmail.com') {
                    if ($row['role'] == 'admin') {
                        $adminStatus = ["demoteAdmin","Demote admin"];
                    } else {
                        $adminStatus = ["makeAdmin","Make admin"];
                    }
                    if ($row['role'] == 'guide') {
                        $guideStatus = ["demoteGuide","Demote guide"];
                    } else {
                        $guideStatus = ["makeGuide","Make guide"];
                    }
                    if ($row['ban'] == 1) {
                        $banStatus = ["unbanUser","Unban user"];
                    } else {
                        $banStatus = ["banUser","Ban user"];
                    }
                echo '<div class="uCard3"><button onclick="' . $guideStatus[0] . '(' . $row['id'] . ')" class="uCard2">' . $guideStatus[1] .'</button>';
                echo '<button onclick="' . $adminStatus[0] . '(' . $row['id'] . ')" class="uCard2">' . $adminStatus[1] .'</button>';
                echo '<button onclick="' . $banStatus[0] . '(' . $row['id'] . ')" class="uCard2">' . $banStatus[1] .'</button>';
                echo '<button onclick="deleteAccount(' . $row['id'] . ')" class="uCard2 deleteAcc">Delete account</button></div>';
                } else {
                    echo '<button onclick="breakLock()" class="headAdmin">Cannot manage this head administrator.</div>';
                }
            echo '</div>';
            $counter = $counter + 1;
        }
    }
} else {
    $sql = 'SELECT username, id, fname, lname, role, ban, email FROM users';
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $counter = 1;
    foreach($result as $row){
        if($counter % 2 == 0) {
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
            if($row['email'] != 'admin@gmail.com') {
                if ($row['role'] == 'admin') {
                    $adminStatus = ["demoteAdmin","Demote admin"];
                } else {
                    $adminStatus = ["makeAdmin","Make admin"];
                }
                if ($row['role'] == 'guide') {
                    $guideStatus = ["demoteGuide","Demote guide"];
                } else {
                    $guideStatus = ["makeGuide","Make guide"];
                }
                if ($row['ban'] == 1) {
                    $banStatus = ["unbanUser","Unban user"];
                } else {
                    $banStatus = ["banUser","Ban user"];
                }
            echo '<div class="uCard3"><button onclick="' . $guideStatus[0] . '(' . $row['id'] . ')" class="uCard2">' . $guideStatus[1] .'</button>';
            echo '<button onclick="' . $adminStatus[0] . '(' . $row['id'] . ')" class="uCard2">' . $adminStatus[1] .'</button>';
            echo '<button onclick="' . $banStatus[0] . '(' . $row['id'] . ')" class="uCard2">' . $banStatus[1] .'</button>';
            echo '<button onclick="deleteAccount(' . $row['id'] . ')" class="uCard2 deleteAcc">Delete account</button></div>';
            } else {
                echo '<button onclick="breakLock()" class="headAdmin">Cannot manage this head administrator.</div>';
            }
        echo '</div>';
        $counter = $counter + 1;
    }
    }
