<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require '../../lang.php';
include ("../includes/ACMheader.php");
//include('../includes/user_not.php');
include ("../../accuel/includes/bdd.php");
include ("blog_class.php");
$blogHandler = new BlogHandler($pdo);
?>

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

        <?php

        if (isset($_GET['id'])) {
            $sql = 'SELECT * FROM blog where id = :id';
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['id' => $_GET['id']]);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($result as $row) {

                $words = explode(' ', $row['title']);
                $name = implode('', $words);
                $dir = '../../admin/img/' . $name . '_' . $row['id_user'];
                if (is_dir($dir) && is_readable($dir)) {
                    // Attempt to scan the directory
        
                    $scandir = scandir($dir);

                    // Check if scandir was successful and if there are files in the directory
                    if ($scandir !== false && count($scandir) > 2) { // Count is > 2 to account for . and ..
                        echo '<div class="blogPage">';
                        echo '<h3 class="blogPostTitle indexBlackFont">';
                        echo $row['title'];
                        echo '</h3>';
                        echo '<center>';
                        foreach ($scandir as $path) {
                            if ($path != '.' && $path != '..') {
                                echo '<img id="pfp" src="' . $dir . '/' . $path . '" alt="Image" width="400px" height="325px" class="blogPostImg">';
                            }
                        }
                        echo '</center>';
                        echo '<div class="blogPostDescription indexBlackFont">';
                        echo $row['description'];
                        echo '</div>';
                        echo '<div class="blogPostDate">';
                        echo $row['date'];
                        echo '</div>';
                    } else {
                        // Directory is empty or contains only . and ..
                        echo '<div class="blogPage">';
                        echo '<h3 class="blogPostTitle indexBlackFont">';
                        echo $row['title'];
                        echo '</h3>';
                        echo '<center>';
                        echo '<img id="pfp" src="/accuel/images/main.jpg" alt="Image" width="400px" height="325px" class="blogPostImg">';
                        echo '</center>';
                        echo '<div class="blogPostDescription indexBlackFont">';
                        echo $row['description'];
                        echo '</div>';
                        echo '<div class="blogPostDate">';
                        echo $row['date'];
                        echo '</div>';
                    }
                } else {
                    // Directory does not exist or is not readable
                    echo '<div class="blogPage">';
                    echo '<h3 class="blogPostTitle indexBlackFont">';
                    echo $row['title'];
                    echo '</h3>';
                    echo '<center>';
                    echo '<img id="pfp" src="/accuel/images/main.jpg" alt="Image" width="400px" height="325px" class="blogPostImg">';
                    echo '</center>';
                    echo '<div class="blogPostDescription indexBlackFont">';
                    echo $row['description'];
                    echo '</div>';
                    echo '<div class="blogPostDate">';
                    echo $row['date'];
                    echo '</div>';
                }
                echo '</div>';

                if (isset($_SESSION['id'])) {
                echo '<form id="like" class="like">
                        <button onclick="like(' . $row['id'] . ', "likes_b", "blog","id_b")">❤️</button> <p id="likes">
                           ' . $row['likes'] . '</p>
                    </form>';
                }

                $comments = $blogHandler->getComments($row['id'], 'comments_b', 'id_blog');
                echo '<div class="blogCommentCol">';
                foreach ($comments as $comment) {
                    $sql = 'SELECT username FROM users WHERE id = :id';
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute(['id' => $comment['id_user']]);
                    $user = $stmt->fetch(PDO::FETCH_ASSOC);
                    echo '<div class="blogCommentUsername indexBlackFont">' . $user['username'] . ': ' . $comment['comment'];
                    // delete button
                    if((isset($SESSION['id']) &&$comment['id_user'] == $_SESSION['id'] )|| (isset($_SESSION['user']) && $_SESSION['user'] == 'admin'))
                    echo '<a href="/ACM/friend/delete_comment.php?id=' . $comment['id'] . '&table=comments_b&id_post='.$row['id'].'"><img src="/accuel/images/bin.png" width="20px"></a></div>';
                    echo '<div class="blogCommentDate">';
                    echo $comment['created_at'];
                    echo '</div>';
                }
                echo '</div>';

                if (isset($_SESSION['id'])) {// send the name of table for wich will change the likes and the id of the blog
                    ?>

                    <form action="add_comment.php" id="commentForm" method="post" class="postcomment">
                        <div class="comment">
                            <input type="text" id="commentInput" name="comment" placeholder="comment" class="commentWrite">
                            <input type="hidden" name="id_blog" value="<?php echo $row['id']; ?>">
                            <input type="submit" placeholder=">"></button>
                        </div>
                    </form>
                <?php }
                echo '</div>';
            }
        }
        ?>
    </main>
    <script src="../main.js"></script>
</body>

</html>