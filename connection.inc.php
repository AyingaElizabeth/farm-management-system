<?php
session_start();

#Remote connection
$con = mysqli_connect("localhost", "root", "", "ecom");

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}
define('SERVER_PATH',$_SERVER['DOCUMENT_ROOT'].'/fyp2');
define('SITE_PATH','http://localhost/fyp2/');

define('PRODUCT_IMAGE_SERVER_PATH',SERVER_PATH.'/media/product/');
define('PRODUCT_IMAGE_SITE_PATH',SITE_PATH.'/media/product/');
date_default_timezone_set("Africa/Kampala");

?>
