<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require '../connect.php';
require '../functions/sms.php';
session_start();
if(isset($_SESSION["user_name"]))
{
	$date = $_POST['date'];
	$sqlDate = date("Y-m-d", strtotime($date));
	$arId = $_POST['ar'];
	$engId = $_POST['engineer'];
	$truck = $_POST['truck'];
	$order_no = $_POST['order_no'];
	$product = $_POST['product'];
	$qty = $_POST['qty'];
	$return = $_POST['return'];	
	$discount = $_POST['bd'];	
	$remarks = $_POST['remarks'];
	$bill = $_POST['bill'];
	$customerName = $_POST['customerName'];
	$customerPhone = $_POST['customerPhone'];
	$address1 = $_POST['address1'];
	$address2 = $_POST['address2'];
	$entered_by = $_SESSION["user_name"];
	$entered_on = date('Y-m-d H:i:s');	
	
	if(empty($discount))
		$discount = null;	
	if(empty($engId))
		$engId = null;	
	if(empty($return))
		$return = null;
	if(empty($order_no))
		$order_no = null;	
	

	$sql="INSERT INTO nas_sale (entry_date, ar_id, eng_id, truck_no, order_no, product, qty, return_bag, discount, remarks, bill_no, customer_name, customer_phone, address1, address2,entered_by,entered_on)
		 VALUES
		 ('$sqlDate', '$arId', ".var_export($engId, true).", '$truck', ".var_export($order_no, true).",'$product', '$qty', ".var_export($return, true).", ".var_export($discount, true).", '$remarks', '$bill', '$customerName', '$customerPhone', '$address1', '$address2', '$entered_by', '$entered_on')";

	$result = mysqli_query($con, $sql) or die(mysqli_error($con));				 

		
	header( "Location: new.php" );

}
else
	header( "Location: ../index.php" );
?> 