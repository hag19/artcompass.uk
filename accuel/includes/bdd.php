<?php 
try {
    $pdo = new PDO("mysql:host=localhost:3306;dbname=site", "root", "fan_club", [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
} catch (Exception $e) {
    die("Erreur: " . $e->getMessage());
}
return $pdo;
