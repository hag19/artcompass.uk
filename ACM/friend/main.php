<?php
session_start();
include ('../../accuel/includes/bdd.php');
include ('../blog/blog_class.php');
include ('../includes/user_not.php');
require '../../lang.php';
$friends = new friends($pdo);
$posts = $friends->getMainPage();
$blog = new BlogHandler($pdo);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Art Compass Media</title>
    <link rel="icon" href="../../accuel/images/favicon.png">
    <link rel="stylesheet" type="text/css" href="/admin/css/style.css?v=<?php echo time(); ?>" media='screen' />
</head>

<body>
    <?php include ("../includes/ACMheader.php") ?>

    <main>
        <h1 class="acmTitle indexBlackFont">
            Art Compass Media
        </h1>
        <input id="search_input" type="text" oninput="search()" placeholder="Search users..."
            class="searchBar communitySearchBar indexWhiteDark indexBlackFont">
        <section class="tourPageGroup indexBlackFont">
            <figure id="html_result">
            </figure>
        </section>
        <?php

        $cols = 0;
        foreach ($posts as $postGroup) {
            foreach ($postGroup as $post) {
                $words = explode(' ', $post['title']);
                $title_n = implode('', $words);
                $dir = "../img/" . $title_n . '_' . $post['id_user'];
                $scandir = scandir($dir);

                if ($cols % 3 == 0) {
                    echo '<div class="acmPostsRow">';
                }
                $sql = 'SELECT image,username, id FROM users WHERE id = :id';
                $req = $pdo->prepare($sql);
                $req->execute([
                    'id' => $post['id_user'],
                ]);
                $user = $req->fetch();
                ?>
                <div class="acmFriendPost">
                    <div class="acmFriendPostTitle indexBlackFont">
                        <?php echo $user['username']; ?>
                        <a href="profile_f.php?id=<?php echo $user['id'] ?>"><img
                                src="../../accuel/upload/<?= $user['image'] ?>" alt="profil" class="mainpfp" height="64rem"
                                width="64px"></a>
                        <?php echo $post['title']; ?>
                        <?php 
                        echo '<div class=friendPostImgGroup>';
                            foreach($scandir as $path) {
                                if($path != '.' && $path != '..') {
                                    $img = $dir . '/' . $path;
                                echo' <img src="'. $img .'" alt="Image" width="250px" height="175px" class="acmPostImg">';
                                }
                            }
                        echo '</div>';
                        ?>
                        <?php echo $post['description']; ?>
                    </div>
                    <button onclick="like('<?php echo $post['id']; ?>', 'likes_p','posts','id_p')"
                        class="indexWhiteDark">❤️️️</button>
                    <div class="acmFriendLikes">
                        <p id="likes" class="indexBlackFont">
                            <?php echo $post['likes']; ?>
                    </div>
                    <h2 class="indexBlackFont">Comments</h2>
                    <div class="comments">
                        <?php
                        $comments = $blog->getComments($post['id'], 'comments_p', 'id_post');
                        echo '<div class="acmPostComments indexWhiteDark">';
                        foreach ($comments as $comment) { 
                            $sql = 'SELECT username, id FROM users WHERE id = :id';
                            $req = $pdo->prepare($sql);
                            $req->execute([
                                'id' => $comment['id_user'],
                            ]);
                            $user = $req->fetch();
                            echo '<div class="acmPostComment indexBlackFont">';
                            echo '<a href="profile_f.php?id=' . $user['id'] . '" class="friendMainCommentSent indexBlackFont">' . $user['username'] . ':</a> ' . $comment['comment'];

                            //delete button
                            if($comment['id_user'] == $_SESSION['id'] || (isset($_SESSION['user']) && $_SESSION['user'] == 'admin'))
                            echo '<a href="/ACM/friend/delete_comment.php?id=' . $comment['id'] . '&table=comments_p"><img src="/accuel/images/bin.png" width="20px"></a>';      
                            echo '</div>';
                            echo '<div class="friendMainCommentDate">'
                                . $comment['created_at'] .
                                '</div>';
                        } ?>
                    </div>
                    <form action="../blog/add_comment.php" id="commentForm" method="post">
                    <div class="comment">
                    <input type="text" id="commentInput" name="comment" placeholder="comment" class="commentWrite indexWhiteDark indexBlackFont">
                    <input type="hidden" name="id_post" value="<?=$post['id']?>">
                    <input type="submit" value=">" class="indexWhiteDark">
                    </div>
                    </form>
                </div>
                </div>
                <?php
                if ($cols % 3 == 2) {
                    echo '</div>';
                }
                ++$cols;
            }
        }
        ?>
        <script src="../main.js"></script>
    </main>
    <?php include ('../../accuel/includes/footer.php'); ?>
</body>

</html>