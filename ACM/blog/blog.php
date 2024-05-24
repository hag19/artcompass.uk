<?php
session_start();
require '../../lang.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="/admin/css/style.css">
    <link rel="icon" href="/accuel/images/favicon.png">
    <title>Our Blog</title>
</head>

<body>
    <main>
        <?php


        include ("../includes/ACMheader.php"); ?>
        <h1 class="blogPageTitle">
            <center class="indexBlackFont">
                Our Blog
            </center>
        </h1>
        <?php


        include ("../../accuel/includes/bdd.php");
        //include('../includes/user_not.php');
        $sql = 'SELECT * FROM blog';
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($result as $row) {
            $words = explode(' ', $row['title']);
            $name = implode('', $words);
            $dir = '../../admin/img/' . $name . '_' . $row['id_user'];
            // Check if the directory exists and is readable
            echo '<div class="blog">';
            echo '<div class="blogDesc">';
            echo "<h1 class='indexBlackFont'>";
            echo $row['title'];
            echo '</h1>';
            echo '<div class="blogDescription indexBlackFont">';
            echo $row['description'];
            echo '</div>';
            echo "<div class='blogDate'>";
            echo $row['date'];
            echo '</div>';
            echo '</div>';
            if (is_dir($dir) && is_readable($dir)) {
                // Attempt to scan the directory
                $scandir = scandir($dir);
                // Check if scandir was successful and if there are files in the directory
                if ($scandir !== false && count($scandir) > 2) { // Count is > 2 to account for . and ..
                    echo '<div>';
                    foreach ($scandir as $path) {
                        if ($path != '.' && $path != '..') {
                            echo '<a href="post.php?id=' . $row['id'] . '"><img id="pfp" src="' . $dir . '/' . $path . '" alt="Image" width="450px" height="300px" class="blogImg"></a>';
                            break;
                        }
                    }
                    echo '</div>';
                } else {
                    // Directory is empty or contains only . and ..
                    echo '<div>';
                    echo '<a href="post.php?id=' . $row['id'] . '"><img id="pfp" src="/accuel/images/main.jpg" alt="Image" width="450px" height="300px" class="blogImg"></a>';
                    echo '</div>';
                }
            } else {
                // Directory does not exist or is not readable
                echo '<div>';
                echo '<a href="post.php?id=' . $row['id'] . '"><img id="pfp" src="/accuel/images/main.jpg" alt="Image" width="450x" height="300px" class="blogImg"></a>';
                echo '</div>';
            }
            echo '</div>';
        }

        ?>
    </main>
</body>

</html>