<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

include('../includes/bdd.php');
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;

try {
    $pdo->beginTransaction();

    $orderDetails = '';

    // Process products in the session
    if (isset($_SESSION['product'])) {
        foreach ($_SESSION['product'] as $product) {
            $total = 0;
            $places_b = 0;
            $id = $product['id'];

            // Fetch product details
            $sql = "SELECT tours.id_product, tours.price, visits.places, tours.tourname FROM visits INNER JOIN tours ON visits.id_product = tours.id_product WHERE visits.id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['id' => $id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                // Calculate total price and update places
                $total = $product['places'] * $result['price'];
                $places = $result['places'] - $product['places'];
                $places_b += $product['places'];
                $id_product = $result['id_product'];

                // Insert order into database
                $sql = 'INSERT INTO orders (name, lastname, email, phone, id_product, id_visits, total_price, id_user, id_transfer) VALUES (:name, :lastname, :email, :phone, :id_product, :id_visits, :total_price, :id_user, :id_transfer)';
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    'name' => $_SESSION['name'],
                    'lastname' => $_SESSION['lname'],
                    'email' => $_SESSION['email'],
                    'phone' => $_SESSION['phone'],
                    'id_product' => $id_product,
                    'id_visits' => $id,
                    'total_price' => $total,
                    'id_user' => isset($_SESSION['user']) ? $_SESSION['user'] : 1,
                    'id_transfer' => 8
                ]);

                // Update places in visits table
                $sql = 'UPDATE visits SET places = :places, places_b = :places_b WHERE id = :id_visits';
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    'places' => $places,
                    'places_b' => $places_b,
                    'id_visits' => $id
                ]);
                $sql = 'SELECT date, time FROM visits WHERE id = :id';
                $stmt = $pdo->prepare($sql);
                $stmt->execute(['id' => $id]);
                $product_b = $stmt->fetch(PDO::FETCH_ASSOC);
                    
                // Append product details to orderDetails string
                $orderDetails .= "Product Name: " . $result['tourname'] . "\n";
                $orderDetails .= "Date: " . $product_b['date'] . "\n";
                $orderDetails .= "Time: " . $product_b['time'] . "\n";
                $orderDetails .= "Places: " . $product['places'] . "\n";
                $orderDetails .= "Total Price: " . $total . "€\n\n";
            } else {
                throw new Exception("Product ID not found in the database.");
            }
        }
    }

    // Process transfers in the session
    if (isset($_SESSION['transfer'])) {
        foreach ($_SESSION['transfer'] as $transfer) {
            $total = 0;
            $id = $transfer['name'];

            // Fetch transfer details
            $sql = "SELECT id, price FROM transferts WHERE name = :name";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['name' => $id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                $id_product = $result['id'];
                $total = $result['price'] * $transfer['duration'];

                // Insert order into database
                $sql = 'INSERT INTO orders (name, lastname, email, phone, id_product, id_visits, total_price, id_user, id_transfer) VALUES (:name, :lastname, :email, :phone, :id_product, :id_visits, :total_price, :id_user, :id_transfer)';
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    'name' => $_SESSION['name'],
                    'lastname' => $_SESSION['lname'],
                    'email' => $_SESSION['email'],
                    'phone' => $_SESSION['phone'],
                    'id_product' => 99999, // Use dummy product ID for transfers
                    'id_visits' => 99999, // Use dummy visit ID for transfers
                    'total_price' => $total,
                    'id_user' => isset($_SESSION['user']) ? $_SESSION['user'] : 1,
                    'id_transfer' => $id_product
                ]);

                // Append transfer details to orderDetails string
                $orderDetails .= "Transfer Name: " . $id . "\n";
                $orderDetails .= "Date: " . $transfer['date'] . "\n";
                $orderDetails .= "Time: " . $transfer['time'] . "\n";
                $orderDetails .= "Duration: " . $transfer['duration'] . " hours\n";
                $orderDetails .= "Total Price: " . $total . "€\n\n";
            } else {
                throw new Exception("Transfer name not found in the database.");
            }
        }
    }

    $pdo->commit();

    // Send email confirmation
    $sql = 'SELECT id FROM orders WHERE email = :email';
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['email' => $_SESSION['email']]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($order && (isset($_SESSION['product']) || isset($_SESSION['transfer']))) {
        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->SMTPAuth = false;
            $mail->Port = 25; // Specify the SMTP port
            $mail->isHTML(true);
            $mail->setFrom("sells@artcompass.uk");
            $mail->addAddress($_SESSION['email']);
            $mail->Subject = 'Order Confirmation - ArtCompass';
            $mail->Body = 'Thank you for your order! Here are the details of your purchase:<br><br>' . nl2br($orderDetails) . '<br>If you have any questions, feel free to contact us at sells@artcompass.uk or call us at +33 7 3825 96 81.';
            $mail->send();
            echo 'Please check your email address. Your order ID: ';
        } catch (Exception $e) {
            echo "Mail not sent. Error: " . $e->getMessage();
            // Consider handling logging properly
        }
        // Clear session variables after email is sent
        unset($_SESSION['product']);
        unset($_SESSION['transfer']);
        unset($_SESSION['total']);
        echo 'Your order has been placed, and an email has been sent to you';
    } else {
        // Redirect if no products or transfers
        header('location: ../../admin/users/smart.php');
        exit;
    } 
} catch (Exception $e) {
    $pdo->rollBack();
    echo "Failed: " . $e->getMessage();
}
?>
