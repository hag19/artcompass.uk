<?php require '../../lang.php';?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../images/favicon.png">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <title><?= lang('Login')?></title>
</head>

<body>
    <?php include ('../includes/header.php'); ?>
    <main>
        <?php
        if (isset($_GET['message']) && !empty($_GET['message'])) {
            echo '<p class="error">' . htmlspecialchars($_GET['message']) . '</p>';
        }
        ?>
        <h1 class="text-center p-5 indexBlackFont"><?= lang('Log in')?></h1>
        <div class="card container shadow p-3 mb-5 bg-body-tertiary rounded col-sm-4">
            <form action="check_conex.php" method="post" class="p-2 indexWhiteDark">
                <label class="form-label"><?= lang('Email')?></label>
                <input type="email" name="email" placeholder=" <?= lang('Email')?>" class="form-control mb-2"
                    value="<?= isset($_COOKIE['email']) ? $_COOKIE['email'] : ''; ?>">
                <label class="form-label"><?= lang('Password')?></label>
                <input type="password" name="password" placeholder=" <?= lang('Password')?>" class="form-control"
                    aria-describedby="passwordHelpBlock">
                <input type="submit" value="<?= lang('Login')?>" class="d-flex mx-auto m-4 btn btn-primary loginButton">
                <a href="../password/forgot_password.php" class="d-flex mb-2"><?= lang('Forgot your password')?> ?</a>
                <p><?= lang('Not registered')?> ? <a href="../registr/registration.php"><?= lang('Click here')?></a></p>
        </div>
    </main>
    <div class="footer">
        <?php include ('../includes/footer.php'); ?>
    </div>
</body>

</html>