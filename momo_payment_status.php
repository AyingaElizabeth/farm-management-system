<?php



if (!isset($_SESSION['USER_LOGIN'])) {
    header('location:index.php');
    exit();
}

if (!isset($_SESSION['MOMO_REF_ID']) || !isset($_GET['order_id'])) {
    echo "Invalid request";
    exit();
}

$reference_id = $_SESSION['MOMO_REF_ID'];
$order_id = $_GET['order_id'];

// MTN MoMo API configuration
$momo_api_url = "https://sandbox.momodeveloper.mtn.com/collection/v1_0/requesttopay/{$reference_id}";
$momo_api_key = "3ad44dc9450242409cce79944b84a735"; // Replace with your actual API key
$momo_subscription_key = "640c59a54b6c415ab5261ccd785930da"; // Replace with your actual subscription key

// Initialize cURL session
$ch = curl_init($momo_api_url);

// Set cURL options
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Authorization: Bearer ' . $momo_api_key,
    'X-Target-Environment: sandbox',
    'Ocp-Apim-Subscription-Key: ' . $momo_subscription_key
));

// Execute cURL request
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

// Close cURL session
curl_close($ch);

if ($http_code == 200) {
    $result = json_decode($response, true);
    $status = $result['status'];
    
    // Update order status in the database
    if ($status == 'SUCCESSFUL') {
        mysqli_query($con, "UPDATE `order` SET payment_status='success', order_status='2' WHERE id='$order_id'");
        $payment_msg = "Payment successful!";
    } elseif ($status =='FAILED') {
        mysqli_query($con, "UPDATE `order` SET payment_status='failed' WHERE id='$order_id'");
        $payment_msg = "Payment failed. Please try again.";
    } elseif ($status == 'PENDING') {
        $payment_msg = "Payment is still pending. Please check back later.";
    } else {
        $payment_msg = "Unknown payment status. Please contact support.";
    }
} else {
    $payment_msg = "Error checking payment status. Please try again later.";
}

// Clear the session variable
unset($_SESSION['MOMO_REF_ID']);
?>

<div class="ht__bradcaump__area" style="background: rgba(0, 0, 0, 0) url(images/bg/5.jpg) no-repeat scroll center center / cover ;">
    <div class="ht__bradcaump__wrap">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="bradcaump__inner">
                        <nav class="bradcaump-inner">
                            <a class="breadcrumb-item" href="index.php">Home</a>
                            <span class="brd-separetor"><i class="zmdi zmdi-chevron-right"></i></span>
                            <span class="breadcrumb-item active">Payment Status</span>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="checkout-wrap ptb--100">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="checkout__inner">
                    <div class="accordion-list">
                        <div class="accordion">
                            <div class="accordion__title">
                                Payment Status
                            </div>
                            <div class="accordion__body">
                                <p><?php echo $payment_msg; ?></p>
                                <p>Order ID: <?php echo $order_id; ?></p>
                                <p>Transaction Reference: <?php echo $reference_id; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require('footer.php');
?>