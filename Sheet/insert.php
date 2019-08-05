<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
date_default_timezone_set("Asia/Kolkata");		

require '../connect.php';
session_start();
if(isset($_SESSION["user_name"]))
{
	$sqlDate = date("Y-m-d",strtotime($_POST['date']));
	$name = $_POST['name'];
	$phone = $_POST['phone'];
	$area = $_POST['area'];
	$shop = $_POST['shop'];
	$remarks = $_POST['remarks'];
	$bags = (int)$_POST['bags'];
	$requested_by = $_SESSION['user_name'];
	$created_on = date('Y-m-d H:i:s');
	
	$sql="INSERT INTO sheets (date, name, phone, bags, area, shop, remarks, requested_by, status, created_on)
		 VALUES
		 ('$sqlDate', '$name', '$phone', $bags, '$area', '$shop', '$remarks', '$requested_by', 'requested', '$created_on')";

	$result = mysqli_query($con, $sql) or die(mysqli_error($con));				 

	
	header( "Location: requests.php" );

}
else
	header( "Location: ../index.php" );
?> 