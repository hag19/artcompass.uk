<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include ('../includes/bdd.php');
include ('../../ACM/blog/blog_class.php');

session_start();
include ('../../ACM/includes/user_not.php');
$friends = new friends($pdo);
$posts = $friends->getPost($_SESSION['id']);
$blog = new BlogHandler($pdo);
require '../../lang.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../images/favicon.png">
    <link rel="stylesheet" type="text/css" href="/admin/css/style.css" media='screen'>
    <title><?= lang('My profile')?></title>
</head>

<body>
    <?php include ('../../ACM/includes/ACMheader.php'); ?>
    <main>
        <?php
            $q = 'SELECT * FROM users WHERE id = :id';
            $req = $pdo->prepare($q);
            $req->execute([
                'id' => $_SESSION['id'],
            ]);
            $result = $req->fetch();
        ?>
        <div class="friendUI">
            <div class="friendPage">
                <h1 class="friendProfile indexBlackFont"><?= lang('My profile')?></h1>
                <div class="friendHeader">
                    <div class="pfp">
                        <label>
                            <img src="../upload/<?= $result['image'] ?>" alt="profil" width="64px" height="64px">
                        </label>
                    </div>
                    <h2 class="friendUsername indexBlackFont">
                        <?= $result['username'] ?>
                    </h2>
                    <div class="friendInteraction">
                        <form action="profile_edit.php" method="post" class="end">
                            <button type="submit" class="indexBlueDarker"><?= lang('Edit profile')?></button>
                        </form>
                    </div>
                </div>
                <div class="friendName indexBlackFont">
                    <?= $result['fname'] ?> <?= $result['lname'] ?>
                </div>
                <div class="friendNumber indexBlackFont">
                    <?= $result['email'] ?>
                </div>
            </div>
            <?php $cols = 0;
            if ($posts != null) {
                foreach ($posts as $post) {

                    if ($cols % 3 == 0) {
                        echo '<div class="postsRow">';
                    }
                    ?>
                    <div class="friendPost">
                        <p class="friendPostTitle indexBlackFont">
                            <?= $post['title'] ?>
                        </p>
                        <?php
                        echo '<div class=friendPostImgGroup>';
                        $dir = "../../ACM/img/" . $post['title'] . '_' . $post['id_user'];
                        $scandir = scandir($dir);
                        foreach ($scandir as $path) {
                            if ($path != '.' && $path != '..') {
                                $img = $dir . '/' . $path;
                                echo ' <img src="' . $img . '" alt="Image" width="250px" height="175px" class="friendPostImg">';
                            }
                        }
                        echo '</div>';


                        ?>
                        <p class="friendPostDesc indexBlackFont">
                            <?= $post['description'] ?>
                        </p>
                        <div class="friendPostDate">
                            <?= $post['date'] ?>
                        </div>
                        <div class="friendLikes indexBlackFont" id="likes">
                            <?= $post['likes'] ?>
                        </div>
                        <form id="delete">
                            <button onclick="like(<?php echo $post['id']; ?>, 'likes_p', 'posts', 'id_p', event)"
                                class="indexWhiteDark">❤️</button>
                        </form>
                        <h2 class="indexBlackFont"><?= lang('Comments')?></h2>
                        <div class="comments">
                            <?php
                            $comments = $blog->getComments($post['id'], 'comments_p', 'id_post');
                            echo '<div class="friendPostComments indexWhiteDark">';
                            echo '<form action="../blog/add_comment.php" id="commentForm" method="post">';
                            echo '<div class="comment">';
                            echo '<input type="text" id="commentInput" name="comment" placeholder="'. lang('Comment') . '" class="commentWrite indexWhiteDark indexBlackFont">';
                            echo '<input type="hidden" name="id_post" value="' . $post['id'] . '">';
                            echo '<input type="submit" value=">" class="indexWhiteDark">';
                            echo '</div>';
                            echo '</form>';
                            foreach ($comments as $comment) {
                                $sql = 'SELECT username, id FROM users WHERE id = ?';
                                $req = $pdo->prepare($sql);
                                $req->execute([$comment['id_user']]);
                                $user = $req->fetch();
                                ?>
                                <div class="acmPostComment indexBlackFont">
                                    <?php
                                    echo '<a href="profile_f.php?id=' . $user['id'] . '" class="friendMainCommentSent indexBlackFont">' . $user['username'] . ': </a>' . $comment['comment'];
                                    // delete button
                                    if ($comment['id_user'] == $_SESSION['id'] || (isset($_SESSION['user']) && $_SESSION['user'] == 'admin'))
                                        echo '<a href="/ACM/friend/delete_comment.php?id=' . $comment['id'] . '&table=comments_p&id_profile_f=' . $_GET['id'] . '"><img src="/accuel/images/bin.png" width="20px"></a>';
                                    echo '<div class="friendMainCommentDate">'
                                        . $comment['created_at'] .
                                        '</div>';
                                    echo '</div>';
                                    ?>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <?php
                    if ($cols % 3 == 2) {
                        echo '</div>';
                    }
                    ++$cols;
                }
            } else {
                echo '<div class="friendPost">';
                echo '<p class="friendPost
            Title indexBlackFont">No posts yet</p>';
                echo '</div>';

            }
            ?>
        </div>
    </main>
    <script src="../../ACM/main.js"></script>
    <?php include ('../includes/footer.php'); ?>
</body>