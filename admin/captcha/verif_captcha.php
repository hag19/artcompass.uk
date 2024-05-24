<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include('../includes/bdd.php');
if(isset($_GET['action'])){
    if($_GET['action'] == 'delete'){
        $sql = 'DELETE FROM CAPTCHA WHERE id_captcha = :id';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            "id" => $_GET['id']
        ]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        header('Location: captcha.php');
        exit;
    }

}
if(isset($_POST['question']) && isset($_POST['answer'])){
    $sql = 'INSERT INTO CAPTCHA (questions, goodAnswer) VALUES (:question, :answer)';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        "question" => $_POST['question'],
        "answer" => $_POST['answer']
    ]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if($result){
        echo "captcha added";
    }else{
        echo "You are a robot";
    }
}