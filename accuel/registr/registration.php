<?php
try {
    $pdo = new PDO(
        "mysql:host=localhost:3306;dbname=site",
        "root",
        "fan_club",
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (Exception $e) {
    die("Erreur: " . $e->getMessage());
}
session_start();
$request = $pdo->prepare('SELECT id_captcha, questions, goodAnswer FROM CAPTCHA order by RAND() LIMIT 1');
$request->execute();
$question = $request->fetch(PDO::FETCH_ASSOC);
$_SESSION['id_captcha'] = $question['id_captcha'];
$_SESSION['questions'] = $question['questions'];
$_SESSION['goodAnswer'] = $question['goodAnswer'];
require '../../lang.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../images/favicon.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <title><?= lang('Register')?></title>
</head>

<body>
    <?php
    include ("../includes/header.php"); ?>
    <main>
        <h1 class="text-center p-3"><?= lang('Register')?></h1>
        <div class="regist_form card container shadow p-3 mb-5 bg-body-tertiary rounded col-sm-4">
            <form action="check_registr.php?" method="post" enctype="multipart/form-data" class="p-2">
                <label class="form-label"><?= lang('Username')?></label>
                <input type="text" name="username" placeholder="<?= lang('Username')?>" required
                    value="<?= isset($_COOKIE['username']) ? $_COOKIE['username'] : ''; ?>" class="form-control mb-2">
                <label class="form-label"><?= lang('E-mail')?></label>
                <input type="email" name="email" placeholder="<?= lang('Email')?>" required
                    value="<?= isset($_COOKIE['email']) ? $_COOKIE['email'] : ''; ?>" class="form-control mb-2">
                <label class="form-label"><?= lang('Last name')?></label>
                <input type="text" name="lname" placeholder="<?= lang('Last name')?>" required
                    value="<?= isset($_COOKIE['lname']) ? $_COOKIE['lname'] : ''; ?>" class="form-control mb-2">
                <label class="form-label"><?= lang('First name')?></label>
                <input type="text" name="fname" placeholder="<?= lang('First name')?>" required
                    value="<?= isset($_COOKIE['fname']) ? $_COOKIE['fname'] : ''; ?>" class="form-control mb-2">
                <label class="form-label"><?= lang('Password')?></label>
                <div class="col-auto">
                    <span id="passwordHelpInline" class="form-text">
                        Must be 8-64 characters long.
                    </span>
                </div>
                <input type="password" name="password" placeholder=" <?= lang('Password')?>" required
                    aria-describedby="passwordHelpBlock" class="form-control mb-2">

                <label class="form-label"><?= lang('Profile picture')?></label>
                <input type="file" name="image" accept="image/jpeg image/png image/gif" required
                    class="form-control mb-3">
                <p><?= lang('Please answer to the following question')?>:</p>
                <?php echo $question['questions'] ?>
                <input type="text" name="answer" class="form-control mb-2">
                <input type="submit" value="<?= lang('Register')?>" class="d-flex mx-auto m-4 btn btn-primary loginButton">

                <p>
                    <?= lang('Already have an account')?> ?
                    <a href="../connex/connexion.php">
                        <?= lang('Click here')?>
                    </a>
                </p>
    </main>
    <?php include ('../includes/footer.php'); ?>
</body>

</html>