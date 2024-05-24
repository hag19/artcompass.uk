<?php 
session_start();
    if($_SESSION["user"] !== "admin" || (!isset($_SESSION["user"]))){
        header("location: /admin/users/smart.php?message=Thought_you_were_smart_there,_huh?");
        exit;
    }