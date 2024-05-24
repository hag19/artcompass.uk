<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include("../includes/bdd.php"); 

if(isset($_POST['name']) && isset($_POST['date'])){
    $id_product = $_POST['name'];
    $date = $_POST['date'];
    $sql = "SELECT time,id,places FROM visits WHERE id_product = :id AND date = :date AND places > 0";
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute(["id" => $id_product, "date" => $date])) {
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);   
        $time = array();
        foreach ($result as $row) {
            $time[] = array(
                "time" => $row['time'],
                "id" => $row['id'],
                "places" => $row['places']
            );
        }
        // Output JSON only
        header('Content-Type: application/json');
        echo json_encode($time);
        exit; // Make sure to exit after sending JSON
    } else {
        // Handle database query error
        http_response_code(500); // Internal Server Error
        echo json_encode(["error" => "Database query failed"]);
        exit;
    }
} else {
    // Handle missing POST parameters
    http_response_code(400); // Bad Request
    echo json_encode(["error" => "Missing POST parameters"]);
    exit;
}
?>
