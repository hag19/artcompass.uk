<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("../includes/bdd.php");

if(isset($_POST['id_product'])) {
    $id = $_POST['id_product'];
    $sql = 'SELECT tourname FROM tours WHERE id_product = :id';
    $stmt = $pdo->prepare($sql);
    $stmt->execute(["id" => $id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $name = $result['tourname'];
    if(isset($_POST["delete"])) { 
        $file = $_POST['ph']; 
        if(file_exists($file)) {
            unlink($file);
            header("location: edit.php?message=photo is deleted");
            exit;
        } else {
            header("location: edit.php?message=file not found");
            exit;
        }
    }
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] != 4) {
        $acceptable = ["image/jpeg", "image/gif", "image/png"];
        if (!in_array($_FILES["image"]["type"], $acceptable)) {
            header('location:add.php?message=wrong file type');
            exit;
        }
        $maxSize = 1024 * 1024 * 6; // 6 MB
        if ($_FILES["image"]["size"] > $maxSize) {
            header("location:add.php?message=file bigger than 6M");
            exit;
        }
        if (!file_exists('../../accuel/images/' . $name)) {
            mkdir('../../accuel/images/' . $name);
        }
        $from = $_FILES['image']['tmp_name'];
        $array = explode('.',$_FILES['image']['name']);
        $ext = end($array);
        $filename = $name . "_" . uniqid() . '.' . $ext; // Unique filename
        $to = '../../accuel/images/' . $name . '/' . $filename;
        move_uploaded_file($from, $to);
    }
 
    if(isset($_POST["description"]) && isset($_POST["title"]) && isset($_POST["price"]) && isset($_POST["name"])) {
        $i = 1;
        foreach($scandir as $row) {
            if ($row != '.' && $row != '..') {
                $array = explode('.', $row);
                $ext = end($array);
                $oldpath = $dir . '/' . $row;
                $newpath = $dir . '/' . $_POST['name'] . '_' . $i . '.' . $ext;
                rename($oldpath, $newpath);
                if (!rename($oldpath, $newpath)) {
                    header("location: edit.php?message=error renaming files");
                    exit;
                }
                $i++;
            }
        }
        $image = $dir . '/' . $filename; // Assuming $filename is the uploaded file
        $sql_update = 'UPDATE tours SET tourname = :name, image = :image, title = :title, description = :description, price = :price WHERE id_product = :id';
        $stmt_update = $pdo->prepare($sql_update);
        $stmt_update->execute([
            "name" => $_POST['name'],
            "image" => $image,
            "title" => $_POST['title'],
            "description" => $_POST['description'],
            "price" => $_POST['price'],
            "id" => $id
        ]);
        header("location: edit_page.php?message=changed with success");
        exit;
    }
}
?>
