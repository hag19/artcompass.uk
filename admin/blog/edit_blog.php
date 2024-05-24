<?php
// Enable error reporting for debugging purposes
error_reporting(E_ALL);
ini_set('display_errors', 1);
include("../includes/smart_check.php");
include("../includes/header.php");
include("../includes/bdd.php");
include('../../ACM/blog/blog_class.php');
require '../../lang.php';
$blog = new BlogHandler($pdo);
//$id = filter_input(INPUT_GET, 'message', FILTER_SANITIZE_STRING);
if (isset($_GET["id"])) {
    $id = $_GET['id'];
    $sql = 'SELECT * FROM blog WHERE id = :id';
    $stmt = $pdo->prepare($sql);
    $stmt->execute(["id" => $id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if(isset($_POST["delete"])) {
       if($blog->deletePost($id,$result['title'], 'blog', 'comments_b', 'id_blog', '../img/')){
        header('Location: blog.php');
        exit;
    }
}
    if ($result) {
            // Display tour details
            echo "<h1 class='text-center p-2'>";
            echo $result['title'];
            echo '</h1>';
            echo '</a>';
            echo '<div class="ms-5">';
            $words = explode(' ', $result['title']);
            $title_n = implode('', $words);           
                $dir = '../img/' . $title_n.'_'. $_SESSION['id'];
                if (is_dir($dir) && is_readable($dir)) {
                $scandir = scandir($dir);
                foreach ($scandir as $row) {
                    if ($row !== '.' && $row !== '..'){
                    echo '<img id="pfp" src="' . $dir .'/' . $row . '" alt="Image" width="1320px" height="500px" class="imgt">';
                    echo "$dir/$row";
                }
            }
        }
            echo '<p class="mt-5">';
            echo $result['description'];
            echo '</p>';
            echo '</div>';
       
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/style.css" rel="stylesheet">
    <title>Edit page</title>
</head>
<body>
    <div class="addForm">
        <!-- Form for editing tour details -->
        <form action="check_blog.php" method="post" enctype="multipart/form-data">
            <input type="text" name="title" placeholder="Title" value="<?php echo $result['title']?>" require>
            <input type="text" name="description" placeholder="Short description" value="<?php echo $result['description']?>" require>
            <input type="file" name="image" accept="image/jpeg image/png image/gif"> <br>
            <input type="text" name="ph" placeholder="photo you want to delete">
            <input type="hidden" name="id" value="<?php echo $result['id'] ?>">
            <input type="submit" value="Add">
            <button type="submit" name="delete" class="deletePhoto"> Delete photo</button>
        </form>
    </div>
</body>
</html>
<?php
 } else {
    echo "post not found.";
}
} else {
    echo "id not provided.";
}