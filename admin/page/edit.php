<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('../includes/smart_check.php');
require '../../lang.php';
include("../includes/header.php");
include("../includes/bdd.php");

if(isset($_POST['id_product'])) {
    $id = $_POST['id_product'];
    
    if(isset($_POST["deleteALL"])) {
        $name = $_POST['tourname'];
        $dir = '../../accuel/images/' . $name;
        if(file_exists($dir)) {
            $scandir = scandir($dir);
            foreach($scandir as $file){
                if (!in_array($file, array(".", ".."))) {
                    unlink($dir . "/" . $file);
                }
            }
            rmdir($dir);
            $sql = 'DELETE FROM tours WHERE id_product = :id';
            $stmt = $pdo->prepare($sql);
            $stmt->execute(["id"=> $id]);
            header("location: edit_page.php");
            exit;
        } else {
            header("location: edit_page.php?message=directory not found");
            exit;
        }
    }
    try {
        $sql = 'SELECT * FROM tours WHERE id_product = :id';
        $stmt = $pdo->prepare($sql);
        $stmt->execute(["id" => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            echo '<center class="editPage">';
                echo '<h1 class="editPageTitle">';
                    echo $result['title'];
                echo '</h1>';
                // Check if $image is not null before using explode
                $image = $result['image'];
                    $dir = '../../accuel/images/' . $result['tourname'];
                    $scandir = scandir($dir);
                    foreach ($scandir as $row) {
                        if ($row !== '.' && $row !== '..'){
                            echo '<img id="pfp" src="' . $dir .'/' . $row . '" alt="Image" width="475px" height="325px" class="editPageImage">';
                        }
                    }
                
                echo '<div class="editPageDescription">';
                    echo $result['description'];
                echo '</div>';
        
                echo '<div class="editPagePrice">';
                    echo $result['price'] . '€';
                echo '</div>';
            echo '</center>';
        } else {
            echo "Tour not found.";
        }
    } catch (Exception $e) {
        die("Error: " . $e->getMessage());
    } 
} else {
    header("location: edit_page.php?message=page not found pls write the correct one");
    exit;
}
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
        <form action="edit_check.php?" method="post" enctype="multipart/form-data">
            <input type="text" name="name" placeholder="Name" value="<?php echo $result['tourname'] ?>">
            <input type="text" name="title" placeholder="Title" value="<?php echo $result['title']?>">
            <input type="text" name="description" placeholder="Short description" value="<?php echo $result['description']?>">
            <input type="number" name="price" placeholder="Set price" value="<?php echo $result['price']?>">
            <input type="file" name="image" accept="image/jpeg image/png image/gif" > <br>
            <input type="text" name="ph" placeholder="photo you want to delete">
            <input type="hidden" name="id_product" value="<?php echo $result['id_product'];?>">
            <input type="submit" value="Add"> 
            <button type="submit" name="delete" class="deletePhoto"> Delete photo</button>
        </form>
    </div>
</body>
</html>
