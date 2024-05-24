<?php error_reporting(E_ALL);
ini_set('display_errors', 1);
include ("../includes/smart_check.php");
require '../../lang.php'; ?>

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
        <form action="add_blog.php" method="post">
            <center>
                <input type="submit" name="add" value="add posts" class="adminAddPost">
            </center>
        </form>
        <?php

        include ("../includes/header.php");

        include ("../includes/bdd.php");
        $sql = 'SELECT * FROM blog';
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($result as $row) {
            echo '<div class="adminBlogPost">';
            $name = $row['title'];
            echo '<div class="adminBlogContent">';
            echo "<h2 class='adminBlogTitle'>";
            echo $row['title'];
            echo '</h2>';
            echo '<div class="adminBlogDesc">';
            echo $row['description'];
            echo '</div>';
            echo "<div class='adminBlogDate'>";
            echo $row['date'];
            echo '</div>';
            echo '</div>';
            echo '<div class="adminBlogImage">';
            $words = explode(' ', $row['title']);
            $name = implode('', $words);
            $dir = '../img/' . $name . '_' . $row['id_user'];
            if (is_dir($dir) && is_readable($dir)) {
                $scandir = scandir($dir);
                if (!empty($scandir)) {
                    $firstImage = $scandir[2]; // Index 0 and 1 are . and ..
                    $path = $dir . '/' . $firstImage;
                    echo '<img id="pfp" src="' . htmlspecialchars($path, ENT_QUOTES, 'UTF-8') . '" alt="Image" width="450px" height="300px">';
                } else {
                    // Le répertoire n'existe pas ou n'est pas accessible en lecture
                    echo '<div>';
                    echo '<a href="post.php?id=' . $row['id'] . '"><img id="pfp" src="/accuel/images/main.jpg" alt="Image" width="450x" height="300px" class="blogImg"></a>';
                    echo '</div>';
                }
            } else {
                // Le répertoire n'existe pas ou n'est pas accessible en lecture
                echo '<div>';
                echo '<a href="post.php?id=' . $row['id'] . '"><img id="pfp" src="/accuel/images/main.jpg" alt="Image" width="450px" height="300px" class="blogImg"></a>';
                echo '</div>';
            }
            echo '</div>';
            ?>
            <form action="edit_blog.php?id=<?php echo $row['id']; ?>" method="post">
                <input type="submit" name="edit" value="Modifier cet article" class="adminAddPost">
                <input type="submit" name="delete" value="Supprimer cet article" class="adminAddPost">
            </form>
            </div>
        <?php } ?>
    </main>

</body>

</html>