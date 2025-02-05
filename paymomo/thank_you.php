<?php
require('./top.php');
require('functions.inc.php');

if(!isset($_SESSION['ORDER_ID'])){
    header('location:index.php');
    exit();
}

$order_id = $_SESSION['ORDER_ID'];
$order_details = get_order_details($con, $order_id);

unset($_SESSION['ORDER_ID']);
?>

<div class="thank-you-section">
    <h1>Thank You for Your Order!</h1>
    <p>Your order (ID: <?php echo $order_id; ?>) has been successfully placed and paid for.</p>
    <p>Total Amount: <?php echo $order_details['total_price']; ?></p>
    <p>We've sent a confirmation email to your registered email address.</p>
    <a href="index.php" class="btn">Continue Shopping</a>
</div>

<?php
require('footer.php');
?>