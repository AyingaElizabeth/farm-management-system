<?php
session_start();
require('./top.php'); // Assuming you have a database connection file
require('functions.inc.php'); // Assuming you have a file with helper functions

// Check if the order ID is provided
if(!isset($_GET['order_id'])) {
    die("Order ID not provided");
}

$order_id = get_safe_value($con, $_GET['order_id']);
$reference_id = $_SESSION['MOMO_REF_ID'] ?? '';

if(empty($reference_id)) {
    die("Reference ID not found");
}

// MTN MoMo API configuration
$momo_api_url = "https://sandbox.momodeveloper.mtn.com/collection/v1_0/requesttopay";
$momo_api_key = "3ad44dc9450242409cce79944b84a735"; // Replace with your actual API key
$momo_subscription_key = "640c59a54b6c415ab5261ccd785930da"; // Replace with your actual subscription key
// Initialize cURL session
$ch = curl_init($momo_api_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Authorization: Bearer ' . $momo_api_key,
    'X-Target-Environment: sandbox',
    'Ocp-Apim-Subscription-Key: ' . $momo_subscription_key
));

// Execute cURL request
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if($http_code == 200) {
    $result = json_decode($response, true);
    $status = $result['status'] ?? '';

    // Update the order status in the database
    if($status == 'SUCCESSFUL') {
        mysqli_query($con, "UPDATE `order` SET payment_status='success', order_status='2' WHERE id='$order_id'");
        // Additional actions for successful payment (e.g., send confirmation email)
        sentInvoice($con, $order_id);
        header("Location: thank_you.php");
        exit();
    } elseif($status == 'FAILED') {
        mysqli_query($con, "UPDATE `order` SET payment_status='failed' WHERE id='$order_id'");
        header("Location: payment_failed.php");
        exit();
    } elseif($status == 'PENDING') {
        // Payment is still pending, you might want to implement a retry mechanism or ask the user to check back later
        header("Location: https:/fyp2/paymomo/payment_pending.php");
        exit();
    } else {
        // Unexpected status
        error_log("Unexpected payment status: " . $status);
        header("Location: payment_error.php");
        exit();
    }
} else {
    // API request failed
    error_log("MTN MoMo API request failed. HTTP Code: " . $http_code);
    header("Location: payment_error.php");
    exit();
}
?>