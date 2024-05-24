<?php
session_start();
include('../../ACM/includes/user_not.php');
require '../../lang.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="/accuel/css/style.css" media='screen'>
    <link rel="icon" href="/accuel/images/favicon.png"> 
    <title><?= lang('Edit profile')?></title>
</head>

<body>
    <?php include ('../includes/header.php') ?>

    <main>
        <h1 class="text-center p-5"><?= lang('Edit profile')?></h1>
        <p><a href="profile.php"><?= lang('Go back to profile')?></a></p>
        <?php

        include ('../includes/bdd.php');
        $q = 'SELECT * FROM users WHERE id = :id';
        $req = $pdo->prepare($q);
        $req->execute([
            'id' => $_SESSION['id'],
        ]);
        $result = $req->fetchAll(PDO::FETCH_ASSOC);
        $user = $result[0];

        if (!isset($_SESSION['id']) || !is_numeric($_SESSION['id'])) { ?>
            <div>Invalid ID</div>;
        <?php } else {

        } ?>
        <?php
        if (isset($_SESSION['id'])) {

            if (!$user) { ?>
                <?php
            } else { ?>
                <div class="regist_form card container shadow p-3 mb-5 bg-body-tertiary rounded col-sm-4">
                    <form action="profile_check.php" method="post" class="p-2">
                        <input class="form-control mb-2" type="hidden" name="id" value="<?php echo $user['id'] ?>">
                        <label class="form-label"><?= lang('Username')?></label>
                        <input class="form-control mb-2" type="text" name="username" placeholder="<?= lang('Username')?>" value="<?php echo $user['username'] ?>">
                        <label class="form-label"><?= lang('New') .' '. lang('email')?></label>
                        <input class="form-control mb-2" type="email" name="email" placeholder="<?= lang('Email')?>" value="<?php echo $user['email'] ?>">
                        <label class="form-label"><?= lang('New') .' '. lang('Profile picture')?>: </label>
                        <input class="form-control mb-2" type="file" name="image">
                        <label class="form-label"><?= lang('New') .' '. lang('Password')?>: </label>
                        <input class="form-control mb-2" type="password" name="password" placeholder="<?= lang('Password')?>" value="">
                        <button type="submit" class="d-flex mx-auto m-4 btn btn-primary loginButton"><?= lang('Save changes')?></button>
                    </form>
                </div>
            <?php }
        }
        ?>
    </main>

    <?php include ('../includes/footer.php') ?>
</body>

</html>