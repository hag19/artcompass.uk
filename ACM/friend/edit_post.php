<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include ('../includes/user_not.php');
include ("../includes/ACMheader.php");
include ('../../accuel/includes/bdd.php');
require '../../lang.php';
$sql = 'SELECT * FROM posts WHERE id = :id';
$req = $pdo->prepare($sql);
$req->execute([
    'id' => $_POST['id_post'],
]);
$result = $req->fetch();
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="/admin/css/style.css">
    <link rel="icon" href="/accuel/images/favicon.png">
    <title>Edit post</title>

</head>

<body>
    <main>
        <h1 class="blogPageTitle">
            <center>Add a post</center>
        </h1>
        <div class="card container shadow p-3 mb-5 bg-body-tertiary rounded col-sm-4">
            <form action="actions.php" method="post" enctype="multipart/form-data" class="p-2 indexWhiteDark">
                <label class="form-label">Post title</label>
                <input type="text" name="title" placeholder="title" required class="form-control mb-2"
                    value="<?php echo $result['title'] ?>">
                <label class="form-label">Description</label>
                <input type="text" name="description" placeholder="Description" required class="form-control mb-2"
                    value="<?php echo $result['description'] ?>">
                <input type="hidden" name="id_post" value="<?php echo $result['id'] ?>">
                <label class="form-label">Image</label>
                <input type="file" name="image" accept="image/jpeg, image/png, image/gif, application/pdf" required
                    class="form-control mb-2">
                <input type="submit" name="edit" value="edit the post"
                    class="d-flex mx-auto m-4 btn btn-primary loginButton">
            </form>
        </div>
    </main>
    <script src="main.js"></script>
</body>

</html>