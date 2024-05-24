<?php

include ('../../accuel/includes/bdd.php');
include ('../blog/blog_class.php');
if (isset($_GET["name"]) && !empty($_GET['name'])) {
    $friends = new friends($pdo);
        $name = htmlspecialchars($_GET['name'], ENT_QUOTES, 'UTF-8');
    if ($result = $friends->getFriends($name)) {
        echo '<div class="pfpRow">';
            foreach ($result as $row) {
                echo '<div class="pfpRowSpacing">';
                    echo '<div class="indexBlackFont">' . $row['username'] . '</div>';
                    echo '<a href="profile_f.php?id=' . $row['id'] . '" class="tourPage">';
                        echo '<img src="../../accuel/upload/' . $row['image'] . '" alt="user_image" height="64rem" width="64rem" class="userRecommendation">';
                    echo '</a>';
                echo '</div>';
            }
        echo '<div>';
    }
}