<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('../includes/smart_check.php');
include('../includes/header.php');
include('../includes/bdd.php');
require '../../lang.php';
// Ensure $name is set
$id = isset($_GET["message"]) ? $_GET["message"] : "";

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/style.css" rel="stylesheet">
    <title>schedule visits</title>
</head>
<body>
    <h1 class="addprod">
    schedule visits
    <div class="addForm">
        <form action="visits_check.php" method="post" id="form" require>
            <input type="number" name="places" placeholder="set places" required>
            <input type="date" name="date" id="date" min="<?php echo date('Y-m-d'); ?>" placeholder="chose the date" required>
            <input type="time" name="time" id="time" placeholder="set the time" required>
            <p id="guide"></p>
            <input type="hidden" name="name" value="<?php echo $id;?>" placeholder="name of the tour" required>
            <input type="submit" value="Add" name="add">
        </form>
    </div>
    <script src="main.js?message=<?php echo $id;?>"></script>
</body>
</html>
