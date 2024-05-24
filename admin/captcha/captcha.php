<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include ('../includes/smart_check.php');
include ('../includes/bdd.php');
require '../../lang.php';
$q = 'SELECT * FROM CAPTCHA';
$req = $pdo->prepare($q);
$req->execute();
$result = $req->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <link rel="icon" href="/accuel/images/favicon.png">
    <title>Add captcha</title>
</head>

<body>
    <?php
    include ('../includes/header.php');
    ?>
    <h1>Add a Captcha</h1>
    <form method="post" action="verif_captcha.php">
        <label>Question</label>
        <input type="text" name="question" placeholder="Question" id="message"></input>
        <label>Answer</label>
        <input type="text" name="answer" placeholder="Answer" id="captcha"></input>
        <input type="submit" value="submit">
    </form>
    <section>
        <h1>All Captchas</h1>

        <?php
        echo '<table>
    <tr><th>Questions</th><th>Answers</th><th>delet</th></tr>';
        foreach ($result as $row) {
            echo '<tr>';
                   echo '<td><form action="verif_captcha.php?action=edit" method="post" enctype="multipart/form-data">
                   <input type="hidden" name="id" value="' . $row['id_captcha'] . '">
                     <input type="text" name="question" placeholder="question" value="'.$row['questions'].'" class="inp">
                <input type="text" name="answer" placeholder="answer description" value='.$row['goodAnswer'].' class="inp">
                   <input type="submit" value="edit">
               </form></td>';
            echo '<td><a href="verif_captcha.php?id=' . $row['id_captcha'] . '&action=delete">delete</a></td>';
            echo '</tr>';
        }
        ?>
        </table>
    </section>
</body>

</html>