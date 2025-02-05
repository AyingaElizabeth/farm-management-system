<?php
// Conditional session start
if (!defined('SESSION_STARTED')) {
    session_start();
    define('SESSION_STARTED', true);
}


// Database connection code here...
#Remote connection
$con=mysqli_connect("localhost","root","","ecom");

// Define constants only if they're not already defined
if (!defined('SERVER_PATH')) define('SERVER_PATH', $_SERVER['DOCUMENT_ROOT'].'/fyp2/');
if (!defined('SITE_PATH')) define('SITE_PATH', 'http://localhost/fyp2/');
if (!defined('PRODUCT_IMAGE_SERVER_PATH')) define('PRODUCT_IMAGE_SERVER_PATH', SERVER_PATH.'media/product/');
if (!defined('PRODUCT_IMAGE_SITE_PATH')) define('PRODUCT_IMAGE_SITE_PATH', SITE_PATH.'media/product/');

date_default_timezone_set("Africa/Kampala");