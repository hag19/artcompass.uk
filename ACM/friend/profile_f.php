<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include ('../../accuel/includes/bdd.php');
include ('../blog/blog_class.php');
require('../includes/user_not.php');
require '../../lang.php';
// if(!isset($_SESSION['id'])) {
//     header('Location: /accuel/connex/connexion.php?message=please login or create an account');
//     exit;
// }
$id = $_GET['id'];
$friends = new friends($pdo);
$dm = new dm($pdo);
$profile = $friends->getProfile($id);
$posts = $friends->getPost($id);
$check = $friends->check_friend($id);
$check_dm = $dm->checkDm($id);
$blog = new BlogHandler($pdo);
if ($check_dm) {
    $dm_re = 'open chat';
} else {
    $dm_re = 'create chat';
}
if ($check) {
    $ad_re = 'remove';
} else {
    $ad_re = 'add';
}
if($_SESSION['id'] == $_GET['id']){
    header('location:/accuel/profile/profile.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../../accuel/images/favicon.png">
    <link rel="stylesheet" type="text/css" href="/admin/css/style.css" media='screen'>
    <title><?= lang('Profile')?></title>
</head>

<body>
    <?php include ('../includes/ACMheader.php'); ?>
    <main>
        <div class="friendUI">
            <div class="friendPage">
                <h1 class="friendProfile indexBlackFont"><?php echo $profile['username'] . "'s " ?>Profile</h1>
                <div class="friendHeader">
                    <div class="pfp">
                        <label>
                            <img src="../../accuel/upload/<?= $profile['image'] ?>" alt="profil" width="64px">
                        </label>
                    </div>
                    <h2 class="friendUsername indexBlackFont">
                        <?= $profile['username'] ?>
                    </h2>
                    <div class="friendInteraction">
                        <form id="add" action="actions.php" method="post" class="end">
                            <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
                            <button type="submit" name="add" value="<?php echo $ad_re ?>" class="indexBlueDarker"><?php echo $ad_re ?></button>
                        </form>
                        <form action="actions.php?action=<?php echo $dm_re ?>&id_dm=<?php echo $check_dm ?>" method="post" class="end">
                            <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
                            <button type="submit" name="create" value="<?php echo $dm_re ?>" class="indexBlueDarker"><?php echo $dm_re ?></button>
                        </form>
                    </div>
                </div>
                <div class="friendName indexBlackFont">
                    <?= $profile['fname'] ?> <?= $profile['lname'] ?>
                </div>
                <div class="friendNumber">
                    <label class="indexBlackFont">Phone number : </label>
                    <?= $profile['number'] ?>
                </div>
            </div>
            <?php $cols = 0;
            if($posts != null){
            foreach ($posts as $post) { 
                
                if($cols % 3 == 0) {
                    echo '<div class="postsRow">';
                }
                ?>
                    <div class="friendPost">
                        <p class="friendPostTitle indexBlackFont">
                            <?= $post['title'] ?>
                        </p>
                        <?php 
                        echo '<div class=friendPostImgGroup>';
                        $dir = "../img/" . $post['title'] . '_' . $post['id_user'];
                        $scandir = scandir($dir);
                        foreach($scandir as $path) {
                            if($path != '.' && $path != '..') {
                                $img = $dir . '/' . $path;
                                echo' <img src="'. $img .'" alt="Image" width="250px" height="175px" class="friendPostImg">';
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
                            <button onclick="like(<?php echo $post['id']; ?>, 'likes_p', 'posts', 'id_p', event)" class="indexWhiteDark">❤️</button>
                        </form>
                        <h2 class="indexBlackFont">Comments</h2>
                        <div class="comments">
                            <?php
                            $comments = $blog->getComments($post['id'], 'comments_p', 'id_post');
                            echo '<div class="friendPostComments indexWhiteDark">';
                                echo '<form action="../blog/add_comment.php" id="commentForm" method="post">';
                                    echo '<div class="comment">';
                                        echo '<input type="text" id="commentInput" name="comment" placeholder="comment" class="commentWrite indexWhiteDark indexBlackFont">';
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
                                        if($comment['id_user'] == $_SESSION['id'] || (isset($_SESSION['user']) && $_SESSION['user'] == 'admin'))
                                        echo '<a href="/ACM/friend/delete_comment.php?id=' . $comment['id'] . '&table=comments_p&id_profile_f='.$_GET['id'].'"><img src="/accuel/images/bin.png" width="20px"></a>';
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
                if($cols % 3 == 2) {
                    echo '</div>';
                }
                ++$cols;
            }
        }else{
            echo'<div class="friendPost">';
            echo'<p class="friendPost
            Title indexBlackFont">No posts yet</p>';
            echo'</div>';
            
        }
            ?>
        </div>
    </main>
    <!-- <?php include ('/includes/footer.php'); ?> -->
</body>

</body>

</html>