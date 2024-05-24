<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('../includes/bdd.php');
if(isset($_POST['i'])){
    $i = $_POST['i'];
    if(isset($_SESSION['product'][$i])){
        unset($_SESSION['product'][$i]);
        echo "success";
    }
}