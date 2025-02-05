<?php
// Error reporting (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('memory_limit', '256M');

require('connection.inc.php');
require('functions.inc.php');
require('vendor/autoload.php');

use Mpdf\Mpdf;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Start session only if one doesn't exist
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Authentication check
if (!isset($_SESSION['ADMIN_LOGIN']) && !isset($_SESSION['USER_ID'])) {
    header("Location: login.php");
    die("Unauthorized access");
}

// Get and validate order ID
$order_id = isset($_GET['id']) ? get_safe_value($con, $_GET['id']) : null;
if (!$order_id) {
    die("Invalid order ID");
}

// Fetch buyer's information and payment type from the `order` table
$order_query = "SELECT o.address, o.city, o.pincode, o.payment_type, u.name, u.email 
                FROM `order` o 
                JOIN users u ON o.user_id = u.id 
                WHERE o.id = ?";
$stmt = $con->prepare($order_query);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order_result = $stmt->get_result();
$order_details = $order_result->fetch_assoc();
$buyer_name = $order_details['name'] ?? 'N/A';
$buyer_email = $order_details['email'] ?? 'N/A';
$buyer_address = $order_details['address'] ?? 'N/A';
$buyer_city = $order_details['city'] ?? 'N/A';
$buyer_pincode = $order_details['pincode'] ?? 'N/A';
$payment_type = $order_details['payment_type'] ?? 'N/A';

// Fetch coupon value
$coupon_query = "SELECT coupon_value FROM `order` WHERE id = ?";
$stmt = $con->prepare($coupon_query);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$coupon_result = $stmt->get_result();
$coupon_details = $coupon_result->fetch_assoc();
$coupon_value = $coupon_details['coupon_value'] ?? 0;

// Load CSS files
$css = file_get_contents('css/bootstrap.min.css');
$css .= file_get_contents('style.css');

// Build Invoice HTML
$html = '
<div class="invoice">
    <h1>Invoice</h1>
    <div class="invoice-header">
        <div class="row">
            <div class="col-md-6">
                <h2>Seller Information</h2>
                <p>App Name: WEfarm</p>
                <p>Address: Kakiri-kiterede, Masulita road, Wakiso</p>
                <p>Email: support@WeFarm.com</p>
            </div>
            <div class="col-md-6 text-right">
                <h2>Buyer Information</h2>
                <p>Name: ' . htmlspecialchars($buyer_name) . '</p>
                <p>Address: ' . htmlspecialchars($buyer_address) . ', ' . htmlspecialchars($buyer_city) . '</p>
                <p>Pincode: ' . htmlspecialchars($buyer_pincode) . '</p>
                <p>Order ID: ' . htmlspecialchars($order_id) . '</p>
                <p>Date: ' . date("Y-m-d") . '</p>
                <p>Payment Type: ' . htmlspecialchars($payment_type) . '</p>
            </div>
        </div>
    </div>
    <hr>
    <h3>Order Details</h3>
    <div class="wishlist-table table-responsive">
       <table class="table table-bordered">
          <thead>
             <tr>
                <th>Product Name</th>
                <th>Product Image</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Total Price</th>
             </tr>
          </thead>
          <tbody>';

// Fetch order details and build table rows
$total_price = 0;
if (isset($_SESSION['ADMIN_LOGIN'])) {
    $query = "SELECT DISTINCT(od.id), od.*, p.name, p.image 
              FROM order_detail od
              JOIN product p ON od.product_id = p.id
              WHERE od.order_id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $order_id);
} else {
    $uid = $_SESSION['USER_ID'];
    $query = "SELECT DISTINCT(od.id), od.*, p.name, p.image 
              FROM order_detail od
              JOIN product p ON od.product_id = p.id
              JOIN `order` o ON od.order_id = o.id
              WHERE od.order_id = ? AND o.user_id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("ii", $order_id, $uid);
}

$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows == 0) {
    die("No order details found for Order ID: $order_id");
}

while ($row = $res->fetch_assoc()) {
    $pp = $row['qty'] * $row['price'];
    $total_price += $pp;
    $html .= '<tr>
        <td>' . htmlspecialchars($row['name']) . '</td>
        <td><img src="' . PRODUCT_IMAGE_SITE_PATH . htmlspecialchars($row['image']) . '" width="50"></td>
        <td>' . $row['qty'] . '</td>
        <td>' . $row['price'] . '</td>
        <td>' . $pp . '</td>
    </tr>';
}

if ($coupon_value > 0) {
    $html .= '<tr>
        <td colspan="3"></td>
        <td>Coupon Value</td>
        <td>' . $coupon_value . '</td>
    </tr>';
    $total_price -= $coupon_value;
}

$html .= '<tr>
    <td colspan="3"></td>
    <td>Total Price</td>
    <td>' . $total_price . '</td>
</tr>';

$html .= '</tbody></table></div>
<hr>
<div class="footer">
    <p>Thank you for your purchase! If you have any questions, contact us at support@WeFarm.com</p>
</div>
</div>';

// Create the invoices folder if it doesn't exist
$invoice_folder = 'invoices/';
if (!is_dir($invoice_folder)) {
    mkdir($invoice_folder, 0777, true); // Create directory with permissions
}

// Generate PDF with MPDF
try {
    $mpdf = new Mpdf(['mode' => 'utf-8', 'format' => 'A4']);
    $mpdf->WriteHTML($css, \Mpdf\HTMLParserMode::HEADER_CSS);
    $mpdf->WriteHTML($html, \Mpdf\HTMLParserMode::HTML_BODY);
    $file = $invoice_folder . 'Invoice_' . $order_id . '_' . time() . '.pdf';
    $mpdf->Output($file, 'F'); // Save the PDF file in the invoices folder
} catch (Exception $e) {
    echo "An error occurred: " . $e->getMessage();
    error_log("PDF Generation Error: " . $e->getMessage());
    die(); // Stop execution if the PDF generation fails
}

// Email the PDF using PHPMailer
$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'azilaginya@gmail.com'; // Your Gmail address
    $mail->Password   = 'cskx cglq pmll sdbg';    // App password from Google
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    // Recipients
    $mail->setFrom('azilaginya@gmail.com', 'WEfarm');
    $mail->addAddress($buyer_email, $buyer_name); // Send to the buyer's email address

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'Invoice for Your Order';
    $mail->Body    = 'Dear ' . htmlspecialchars($buyer_name) . ',<br><br>Your invoice is attached.<br><br>Regards,<br>WEfarm Team';

    // Attach the generated PDF
    $mail->addAttachment($file); // Attach the generated PDF file

    // Send the email
    $mail->send();
    
    // Display a styled success message
    echo '<div style="text-align: center; font-weight: bold; color: green; font-size: 24px; margin-top: 20px;">
            Email has been sent to ' . htmlspecialchars($buyer_email) . '
          </div>';
} catch (Exception $e) {
    echo '<div style="text-align: center; font-weight: bold; color: red; font-size: 24px; margin-top: 20px;">
            Message could not be sent. Mailer Error: ' . htmlspecialchars($mail->ErrorInfo) . '
          </div>';
}
?>
