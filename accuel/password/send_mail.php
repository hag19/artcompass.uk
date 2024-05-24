<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

use PHPMailer\PHPMailer\PHPMailer;

require '../vendor/phpmailer/phpmailer/src/Exception.php';
require '../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '../vendor/phpmailer/phpmailer/src/SMTP.php';
// Подключаем PHPMailer
require '../vendor/autoload.php';
// Получаем email из формы
$email = $_POST["email"];
// Генерируем токен сброса пароля
$token = bin2hex(random_bytes(16));
$token_hash = hash("sha256", $token);
$expiry = date("Y-m-d H:i:s", time() + 60 * 60);
// Проверяем, существует ли пользователь с таким email
include('../includes/bdd.php');
$stmt = $pdo->prepare('SELECT id FROM users WHERE email = :email');
$stmt->execute(['email' => $email]);
$results = $stmt->fetchAll();
// Если пользователь существует, отправляем письмо для сброса пароля
if(empty($results)){
    header("location:forgot_password.php?message=this email address does not exist");
    exit;
}
else {
    // Обновляем информацию о токене сброса пароля в базе данных
    $sql = "UPDATE users
                SET reset_token_hash = ?,
                    reset_token_expires_at = ? 
                WHERE email = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$token_hash, $expiry, $email]);
}
if ($stmt->rowCount()) {
    try { 
        $mail =  new PHPMailer(true);
        $mail->isSMTP();
        $mail->SMTPAuth = false;
        $mail->Port = 25; // Укажите порт SMTP
        $mail->isHTML(true);
        $mail->setfrom("noreply@artcompass.uk");
        $mail->addAddress($email);
        $mail->Subject = 'Resetting Your Password';
        $mail->Body = 'Click <a href="https://artcompass.uk/accuel/password/new_password.php?token=' . $token . '">here</a> to reset your password.';
        $mail->send();
    } catch (Exception $e) {
        echo "mail not sent mail error: " . $e->getMessage();
    }
    if ($mail->send()) {
        echo 'please check your mail box';
    }
}

