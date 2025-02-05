<?php
require('./top.php');
require('functions.inc.php');

if(!isset($_SESSION['ORDER_ID'])){
    header('location:index.php');
    exit();
}

$order_id = $_SESSION['ORDER_ID'];
$order_details = get_order_details($con, $order_id);
?>

<div class="payment-pending-section">
    <h1>Payment Pending</h1>
    <p>Your payment for order (ID: <?php echo $order_id; ?>) is currently being processed.</p>
    <p>Total Amount: <?php echo $order_details['total_price']; ?></p>
    <p>Please check back later for updates on your payment status.</p>
    <a href="index.php" class="btn">Return to Home</a>
</div>

<?php
require('footer.php');
?>