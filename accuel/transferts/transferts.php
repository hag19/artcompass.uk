<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);
session_start();
require '../../lang.php';
include ("../includes/bdd.php");

$q = "SELECT * FROM transferts";
$req = $pdo->prepare($q);
$req->execute();
$result = $req->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= lang('Transferts')?></title>
    <link rel="icon" href="/accuel/images/favicon.png">
    <link rel="stylesheet" type="text/css" href="/accuel/css/style.css?v=<?php echo time(); ?>" media='screen' />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body onload="initialize()">
    <?php include ("../includes/header.php") ?>

    <main>
        <h1 class="popular indexBlackFont"><?= lang('Transferts')?></h1>
        <div class="card container shadow p-3 mb-5 bg-body-tertiary rounded col-sm-4">
            <form action="transferts_check.php" method="post" class="p-2 indexWhiteDark">
                <label for="transport"><?= lang('Choose a transport')?>:</label>
                <select name="transport" id="transport" class="form-control mb-2">
                    <option value="no" class="form-control mb-2"><?= lang('Transport')?></option>
                    <?php
                    foreach ($result as $row) {
                        echo '<option value="' . $row['name'] . '">' . $row['name'] . '</option>';
                    }
                    ?>
                    <p id="price_transfer"></p>
                </select>

                <label><?= lang('Date')?></label>
                <input type="date" name="date" min="<?php  echo date('Y-m-d'); ?>" class="form-control">
                <label><?= lang('Time')?></label>
                <input type="time" name="time" class="form-control">
                <label><?= lang('Duration')?></label>
                <input type="number" name="duration" class="form-control">
                <input type="submit" value="<?= lang('Submit')?>" class="d-flex mx-auto m-4 btn btn-primary loginButton">
            </form>
        </div>
    </main>

    <?php include ("../include/footer.php") ?>
</body>

</html>