<?php 
 error_reporting(E_ALL);
 ini_set('display_errors', 1);
session_start();
use PHPMailer\PHPMailer\PHPMailer;
require '../vendor/phpmailer/phpmailer/src/Exception.php';
require '../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '../vendor/phpmailer/phpmailer/src/SMTP.php';
// Подключаем PHPMailer
require '../vendor/autoload.php';
include('../../admin/includes/bdd.php');
function writeLogLine($success, $email)
{
    // ouvrir le fichier log.txt (flux)
    $log = fopen('/var/www/html/accuel/connex/log.txt', 'a+');

    // Création de la ligne à ajouter
    // AAAA/mm/jj - hh:mm:ss -  Tentative de connexion échouée de : {email}
    $line = date('Y/m/d - H:i:s') . ' - Tentative de connexion ' . ($success ? 'réussie' : 'échouée') . ' de : ' . $email . "\r";

    // Ajouter la ligne au flux
    fputs($log, $line);

    // Refermer le fichier (flux)
    fclose($log);
}

if (isset($_POST['email']) && !empty($_POST['email'])) {
    setcookie('email', $_POST['email'], time() + 60 * 60 * 24);
}
if (
    !isset($_POST['email'])
    && empty($_POST['email'])
    && !isset($_POST['password'])
    && empty($_POST['password'])
) {
    header('location: connexion.php?message=something went wrong!');
    writeLogLine(false, $_POST['email']);
    exit;
}
if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    header('location: connexion.php?message=wrong email adress!');
    writeLogLine(false, $_POST['email']);
    exit;
}
$sql = 'SELECT * FROM users WHERE email = :email && password = :password'; // забераем емейл из таблицы
$stmt = $pdo->prepare($sql); // подготовка
$psw_hash = hash('sha256', $_POST['password']);
$stmt->execute([ // комиляция 
    'email' => $_POST['email'],
    'password' => $psw_hash
]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);
if($result['role'] == 'admin'){
    $_SESSION["id"] = $result["id"]; 
    $_SESSION['user'] =  'admin';
    header('location: ../../admin/admin.php');
    exit;
}

if($result['role'] == 'guide'){
    $_SESSION["id"] = $result["id"];
    $_SESSION["name"] = $result["username"];
    $_SESSION['user'] =  'guide';
    header('location: ../../index.php');
    exit;
}
 if(empty($result)){
    header("location: connexion.php?message=Wrong password or email");
    writeLogLine(false, $_POST['email']);
    exit;
 }
 if ($result["code_email_valid"] == 0){
    var_dump($result['code_email_valid']);
    exit;
    $code_email = rand();
    $email = $_POST['email'];
    $sql = "UPDATE users
    SET code_email = ?
    WHERE email = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    $code_email,$email
]);
try { 
    $mail =  new PHPMailer(true);
    $mail->isSMTP();
    $mail->SMTPAuth = false;
    $mail->Port = 25; // Укажите порт SMTP
    $mail->isHTML(true);
    $mail->setFrom("ValidEmail@artcompass.uk");
    $mail->addAddress($email);
    $mail->Subject = 'Validate your account';
    $mail->Body = 'Click <a href="https://artcompass.uk/accuel/registr/confirm.php">here</a> to validate your account. Your code is: ' . $code_email;
    $mail->send();
    echo 'Please check your email address';
} catch (Exception $e) {
    echo "Mail not sent. Error: " . $e->getMessage();
    writeLogLine(false, $_POST['email']);
}
 }else if ($result['code_email_valid'] == 1){
    writeLogLine(true, $_POST['email']);
    $_SESSION["id"] = $result["id"];
    header("location: ../../index.php?message=Welcome!");
    exit;
 }
 
 ?>