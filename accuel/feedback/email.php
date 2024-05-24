<?php
// Error reporting and session start
error_reporting(E_ALL);
ini_set('display_errors', 0); // Set to 0 for production
session_start();
// Include necessary files
include('/var/www/html/accuel/includes/bdd.php');
use PHPMailer\PHPMailer\PHPMailer;
require '/var/www/html/accuel/vendor/autoload.php'; 
// Select orders with no reviews
$sql = "SELECT email, id FROM orders WHERE review IS NULL";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Loop through results and send emails
foreach ($results as $row) {
    $token = bin2hex(random_bytes(16));
    // Update token in the database
    $sql = "UPDATE orders SET token = :token WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        "token" => $token,
        "id" => $row['id']
    ]);
    try {
        // Send email to the customer
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->SMTPAuth = false;
        $mail->Port = 25; // Change if necessary
        $mail->isHTML(true);
        $mail->setFrom("noreply@artcompass.uk", "Art Compass");
        $mail->addAddress($row['email']);
        $mail->Subject = 'Your Feedback is Valued';
        $mail->Body = '
        <!DOCTYPE html>
        <html>
        <head>
            <title>Your Feedback is Valued</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background-color: #f5f5f5;
                    margin: 0;
                    padding: 0;
                }
                .container {
                    max-width: 600px;
                    margin: 20px auto;
                    background-color: #ffffff;
                    padding: 20px;
                    border-radius: 5px;
                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                }
                .logo {
                    text-align: center;
                    margin-bottom: 20px;
                }
                .logo img {
                    max-width: 200px;
                    height: auto;
                }
                .message {
                    text-align: center;
                    margin-bottom: 20px;
                }
                .button {
                    display: inline-block;
                    background-color: #007bff;
                    color: #ffffff;
                    padding: 10px 20px;
                    text-decoration: none;
                    border-radius: 5px;
                }
                .button:hover {
                    background-color: #0056b3;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="logo">
                    <img src="https://artcompass.uk/accuel/images/main.jpg" alt="Art Compass Logo">
                </div>
                <div class="message">
                    <p>We would appreciate your feedback.</p>
                    <p>Click the button below to leave your review.</p>
                </div>
                <div class="button-container">
                    <a class="button" href="https://artcompass.uk/accuel/feedback/maike.php?token=' . $token . '&id=' . $row['id'] . '">Leave a Review</a>
                </div>
            </div>
        </body>
        </html>';
        $mail->send();
        echo 'Email sent to ' . $row['email'] . '<br>';
    } catch (Exception $e) {
        echo "Mail not sent. Error: " . $e->getMessage();
    }
}

// Close PHP tag if no HTML/PHP mix needed below
