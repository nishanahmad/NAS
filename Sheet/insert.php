<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
date_default_timezone_set("Asia/Kolkata");

require '../connect.php';
session_start();
if(isset($_SESSION["user_name"]))
{
	$sqlDate = date("Y-m-d",strtotime($_POST['date']));
	$customer_name = $_POST['customer_name'];
	$customer_phone = $_POST['customer_phone'];
	$mason_name = $_POST['mason_name'];
	$mason_phone = $_POST['mason_phone'];
	$area = $_POST['area'];
	$driver_area = $_POST['driver_area'];
	$shop = $_POST['shop'];
	$remarks = $_POST['remarks'];
	$bags = (int)$_POST['bags'];
	$requested_by = $_SESSION['user_name'];
	$created_on = date('Y-m-d H:i:s');
	if(isset($_POST['priority']))
		$priority = 1;
	else	
		$priority = 0;
		
	if(isset($_POST['block']))
		$block = 1;
	else	
		$block = 0;		
	
	
	//  FETCH DRIVER TO ASSIGN
	$driverQuery = mysqli_query($con, "SELECT driver FROM sheet_area WHERE id = $driver_area") or die(mysqli_error($con));
	$driver = mysqli_fetch_array($driverQuery, MYSQLI_ASSOC)['driver'];

	
	$sql="INSERT INTO sheets (date, customer_name, customer_phone, mason_name, mason_phone, bags, area, driver_area, shop, remarks, requested_by, status, created_on, assigned_to, priority, coveringBlock)
		 VALUES
		 ('$sqlDate', '$customer_name', '$customer_phone', '$mason_name', '$mason_phone', $bags, '$area', '$driver_area', '$shop', '$remarks', '$requested_by', 'requested', '$created_on', '$driver', $priority, $block)";

	$result = mysqli_query($con, $sql) or die(mysqli_error($con));
	


	header( "Location:new.php?success" );
}
else
	header( "Location: ../index.php" );
?>