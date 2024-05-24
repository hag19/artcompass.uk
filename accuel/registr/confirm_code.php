<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    $email = htmlspecialchars($_POST["email"],ENT_QUOTES, 'UTF-8');
    $code = htmlspecialchars($_POST["code"],ENT_QUOTES, 'UTF-8');
   include('../includes/bdd.php');
    $stmt = $pdo->prepare('SELECT code_email FROM users WHERE email = :email');
    $stmt->execute(['email' => $email]);
    $result = $stmt->fetch();
 if($code == $result['code_email']){  
    $sql = "UPDATE users
    SET code_email = NULL,
    code_email_valid = 1
    WHERE email = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);
    header("Location:../connex/connexion.php?message=now you can login");
    exit; 
} else{
    header("Location:confirm.php?message= wrong code!!");
    exit;
}