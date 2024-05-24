<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include('../includes/bdd.php');
require_once('../includes/vendor/autoload.php');

if(isset($_POST['confirm'])) {
    // Assign session values to $_POST before overwriting them
    $name = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
    $lname = htmlspecialchars($_POST['lname'], ENT_QUOTES, 'UTF-8');
    $email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
    $phone = htmlspecialchars($_POST['phone'], ENT_QUOTES, 'UTF-8');
    $_SESSION['name'] = $name;
    $_SESSION['lname'] = $lname;
    $_SESSION['email'] = $email;
    $_SESSION['phone'] = $phone;
    $total = $_SESSION['total'];

    // Define the price locally
    $price = $total *100; // For example, you can set this to whatever price you want
  
    $stripe = new \Stripe\StripeClient('sk_test_51P5BfGErCgKKqlHdYAftPAdCOs9xIBiXJTRxiFNIRct2DglHv9umacqAyhEfF5BsdP7Ek069QgjH8uUH0QWJqKHq00mIS8etjy');

    try {
        $session = $stripe->checkout->sessions->create([
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'eur',
                        'unit_amount' => $price,
                        'product_data' => [
                            'name' => 'france for french)',
                        ],
                    ],
                    'quantity' => 1,
                ],
            ],
            'mode' => 'payment',
            'success_url' => 'https://artcompass.uk/accuel/visites/success.php',
        ]);

        // Redirect the user to the Stripe Checkout session URL
        header("Location: " . $session->url);
        exit();
    } catch (\Stripe\Exception\ApiErrorException $e) {
        // Handle any errors from the Stripe API
        echo "Error: " . $e->getMessage();
    }
}
?>
