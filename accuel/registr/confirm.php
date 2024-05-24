<!DOCTYPE html>
<html lang="en">

<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" href="../images/favicon.png">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
                integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
                crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="../css/style.css">
        <title>Verification</title>
</head>

<body>
        <h1 class="text-center p-3">S'inscrire</h1>
        <form action="confirm_code.php" method="post">
                <input type="text" name="code" placeholder=" Code" required>
                <input type="email" name="email" placeholder=" Email" required
                        value="<?= isset ($_COOKIE['email']) ? $_COOKIE['email'] : ''; ?>">
                <button type="submit" class="d-flex mx-auto p-2 m-4 btn btn-primary">Confirm</button>
        </form>
</body>

</html>