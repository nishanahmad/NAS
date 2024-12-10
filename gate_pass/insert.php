<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require '../connect.php';

session_start();
if(isset($_SESSION["user_name"]))
{
	$token_no = $_POST['token'];
	$sqlDate = date("Y-m-d", strtotime($_POST["date"])); 
	$sl_no = $_POST['sl_no'];
	$vehicle = $_POST['vehicle'];
	$order_no = $_POST['order_no'];
	$time = $_POST['time'];
	$consignor_id = $_POST['consignor'];
	$from_godown = $_POST['from_godown'];
	$delivery_at = $_POST['delivery_at'];
	$driver = $_POST['driver'];
	$driver_phone = $_POST['driver_phone'];
	$driver_license_no = $_POST['driver_license_no'];
	$ut_qty = $_POST['ut_qty'];	
	$super_qty = $_POST['super_qty'];

	$entered_by = $_SESSION["user_id"];
	$entered_on = date('Y-m-d H:i:s');
		

	if(empty($token_no))
		$token_no = null;			
	if(empty($sl_no))
		$sl_no = null;	
	if(empty($order_no))
		$order_no = null;				
									
	$insert = mysqli_query($con,"INSERT into gate_pass (token_no, sl_no, date, order_no, consignor_id, from_godown, time, vehicle,
														delivery_at, driver, driver_phone, driver_license_no, ut_qty, super_qty, entered_by, entered_on)
								 VALUES
								(".var_export($token_no, true).", ".var_export($sl_no, true).",'$sqlDate', ".var_export($order_no, true).",
								'$consignor_id', '$from_godown', '$time', '$vehicle', '$delivery_at', '$driver', '$driver_phone', '$driver_license_no', $ut_qty, $super_qty, 
								'$entered_by', '$entered_on')") or die(mysqli_error($con));

	header('Location: list.php?success');
}
else
	header( "Location: ../index/home.php" );
?>