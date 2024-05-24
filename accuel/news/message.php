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
session_start();
include('../includes/bdd.php');
if(isset($_POST['send'])){
    $sql = 'INSERT into admin_message (message,name,email,id_user) values (:message,:name,:email,1)';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        "message" => $_POST['message'],
        "name" => $_POST['name'],
        "email" => $_POST['email']
    ]);
        header('location: ../../index.php?message=valid');
        exit;
}
if(isset($_POST['send_email'])){
        try { 
            $mail =  new PHPMailer(true);
            $mail->isSMTP();
            $mail->SMTPAuth = false;
            $mail->Port = 25; // Укажите порт SMTP
            $mail->isHTML(true);
            $mail->setfrom("noreply@artcompass.uk");
            $mail->addAddress($_POST['email']);
            $mail->Subject = 'Resetting Your Password';
            $mail->Body = $_POST['response'];
            $mail->send();
        } catch (Exception $e) {
            echo "mail not sent mail error: " . $e->getMessage();
        }
        if ($mail->send()) {
            echo 'please check your mail box';
        }
}    