<?php

function pr($arr){
	echo '<pre>';
	print_r($arr);
}

function prx($arr){
	echo '<pre>';
	print_r($arr);
	die();
}

function get_safe_value($con,$str){
	if($str!=''){
		$str=trim($str);
		return mysqli_real_escape_string($con,$str);
	}
}

function get_product($con,$limit='',$cat_id='',$product_id='',$search_str='',$sort_order='',$is_best_seller='',$sub_categories=''){
	$sql="select product.*, categories.categories, admin_users.username as seller_name 
	      from product 
	      INNER JOIN categories ON product.categories_id=categories.id
	      LEFT JOIN admin_users ON product.added_by=admin_users.id 
	      where product.status=1 ";
	
	if($cat_id!=''){
		$sql.=" and product.categories_id=$cat_id ";
	}
	if($product_id!=''){
		$sql.=" and product.id=$product_id ";
	}
	if($sub_categories!=''){
		$sql.=" and product.sub_categories_id=$sub_categories ";
	}
	if($is_best_seller!=''){
		$sql.=" and product.best_seller=1 ";
	}
	if($search_str!=''){
		$sql.=" and (product.name like '%$search_str%' or product.description like '%$search_str%') ";
	}
	if($sort_order!=''){
		$sql.=$sort_order;
	}else{
		$sql.=" order by product.id desc ";
	}
	if($limit!=''){
		$sql.=" limit $limit";
	}
	//echo $sql;
	$res=mysqli_query($con,$sql);
	$data=array();
	while($row=mysqli_fetch_assoc($res)){
		$data[]=$row;
	}
	return $data;
}
function wishlist_add($con,$uid,$pid){
	$added_on=date('Y-m-d h:i:s');
	mysqli_query($con,"insert into wishlist(user_id,product_id,added_on) values('$uid','$pid','$added_on')");
}

function productSoldQtyByProductId($con,$pid){
	$sql="select sum(order_detail.qty) as qty from order_detail,`order` where `order`.id=order_detail.order_id and order_detail.product_id=$pid and `order`.order_status!=4 and ((`order`.payment_type='mtm momo' and `order`.payment_status='Success') or (`order`.payment_type='COD' and `order`.payment_status!=''))";
	$res=mysqli_query($con,$sql);
	$row=mysqli_fetch_assoc($res);
	return $row['qty'];
}

function productQty($con,$pid){
	$sql="select qty from product where id='$pid'";
	$res=mysqli_query($con,$sql);
	$row=mysqli_fetch_assoc($res);
	return $row['qty'];
}

function sentInvoice($con,$order_id){
	$res=mysqli_query($con,"select distinct(order_detail.id) ,order_detail.*,product.name,product.image from order_detail,product ,`order` where order_detail.order_id='$order_id' and order_detail.product_id=product.id");

	$user_order=mysqli_fetch_assoc(mysqli_query($con,"select `order`.*, users.name,users.email  from `order`,users where users.id=`order`.user_id and `order`.id='$order_id'"));

	$coupon_details=mysqli_fetch_assoc(mysqli_query($con,"select coupon_value from `order` where id='$order_id'"));
	$coupon_value=$coupon_details['coupon_value'];

	$total_price=0;

	
	
	
	
// 	// Function to send invoice (you'll need to implement the actual logic)
// 	function sentInvoice($con, $order_id) {
// 		// Implement your invoice sending logic here
// 		// This could involve fetching order details from the database
// 		// and sending an email to the customer
		
// 		// Example:
// 		$order_details = get_order_details($con, $order_id);
// 		// Use order details to generate and send an invoice
// 		// You might want to use a library like PHPMailer for sending emails
// 	}
	
// 	// Function to get order details
// 	function get_order_details($con, $order_id) {
// 		$order_id = get_safe_value($con, $order_id);
// 		$query = "SELECT * FROM `order` WHERE id = '$order_id'";
// 		$res = mysqli_query($con, $query);
// 		return mysqli_fetch_assoc($res);
// 	}
	
// 	// Function to update order status
// 	function update_order_status($con, $order_id, $payment_status, $order_status) {
// 		$order_id = get_safe_value($con, $order_id);
// 		$payment_status = get_safe_value($con, $payment_status);
// 		$order_status = get_safe_value($con, $order_status);
		
// 		$query = "UPDATE `order` SET payment_status='$payment_status', order_status='$order_status' WHERE id='$order_id'";
// 		return mysqli_query($con, $query);
// 	}
	
// 	// Function to log MTN MoMo API responses (for debugging)
// 	function log_momo_response($order_id, $reference_id, $status, $response) {
// 		$log_file = 'momo_api_log.txt';
// 		$log_message = date('Y-m-d H:i:s') . " - Order ID: $order_id, Ref ID: $reference_id, Status: $status, Response: " . json_encode($response) . "\n";
// 		file_put_contents($log_file, $log_message, FILE_APPEND);
// 	}
// 	// Add this new function
// function log_momo_response($order_id, $reference_id, $status, $response) {
//     $log_file = 'momo_api_log.txt';
//     $log_message = date('Y-m-d H:i:s') . " - Order ID: $order_id, Ref ID: $reference_id, Status: $status, Response: " . json_encode($response) . "\n";
//     file_put_contents($log_file, $log_message, FILE_APPEND);
// }
	
}
?>