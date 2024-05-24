<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

include ("../includes/bdd.php");

if ($_GET['action'] == 'delete' && $_POST['transfert']) {
    $q = 'DELETE FROM transferts WHERE id = :id';
    $stmt = $pdo->prepare($q);
    $stmt->execute(['id' => $_POST['transfert']]);
    header('location:add_transfert.php?message=deleted');
    exit;
}
if($_GET['action'] == 'add') {
 
    if (
        !isset($_POST['name']) ||
        !isset($_POST['description']) ||
        !isset($_POST['price'])
    ) {
        header('location:add_transfert.php?message=fill everything please!');
        exit;
    }
    $sql1 = 'SELECT name from transferts';
    $stmt1 = $pdo->prepare($sql1);
    $stmt1->execute();
    $result1 = $stmt1->fetchAll(PDO::FETCH_ASSOC);
    if (in_array($_POST['name'], $result1)) {
        header('location:add_transfert.php?message=this name already exists');
        exit;
    }
    
    $sql2 = 'INSERT into transferts (name, description, price) VALUES (:name, :description, :price)';
    $stmt2 = $pdo->prepare($sql2);
    $stmt2->execute([
        "name" => $_POST['name'],
        "description" => $_POST['description'],
        "price" => $_POST['price']
    ]);
    
    header('location:add_transfert.php?message=Transport added');
    exit;
}
if($_GET['action'] == 'edit'){
    // edit 
    if(
        !isset($_POST['name']) ||
        !isset($_POST['description']) ||
        !isset($_POST['price'])
    ){
        header('location:add_transfert.php?message=fill everything please!');
        exit;
    }
    $sql = 'UPDATE transferts SET name = :name, description = :description, price = :price WHERE id = :id';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'name' => $_POST['name'],
        'description' => $_POST['description'],
        'price' => $_POST['price'],
        'id' => $_POST['transfert']
    ]);
    header('location:add_transfert.php?message=edited');
}