<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require '../connect.php';
session_start();
if(isset($_SESSION["user_name"]))
{
	if(isset($_POST['id']))
		$id = $_POST['id'];
	$date = $_POST['sheetDate'];
	$sqlDate = date("Y-m-d", strtotime($date));
	$customer_name = $_POST['sheet_customer_name'];
	$customer_phone = $_POST['sheet_customer_phone'];
	$mason_name = $_POST['sheet_mason_name'];	
	$mason_phone = $_POST['sheet_mason_phone'];
	$bags = $_POST['sheet_bags'];
	$area = $_POST['sheet_area'];
	$shop = $_POST['sheet_shop'];		
	$remarks = $_POST['sheet_remarks'];	
	$site = $_POST['site'];	
	$requested_by = $_SESSION['user_name'];
	$created_on = date('Y-m-d H:i:s');	
	
	if(isset($id))
	{
		$sql = "UPDATE sheets SET date = '$sqlDate', customer_name = '$customer_name', customer_phone = '$customer_phone', 
								  mason_name = '$mason_name', mason_phone = '$mason_phone', bags = $bags, area = '$area', shop = '$shop', remarks = '$remarks'
				WHERE id = $id";
	}
	else
	{
		$sql="INSERT INTO sheets (date, customer_name, customer_phone, mason_name, mason_phone, bags, area, shop, remarks, site, requested_by, status, created_on)
			 VALUES
			 ('$sqlDate', '$customer_name', '$customer_phone', '$mason_name', '$mason_phone', $bags, '$area', '$shop', '$remarks', $site , '$requested_by', 'requested', '$created_on')";
	}

	$result = mysqli_query($con, $sql) or die(mysqli_error($con));				 

	header( "Location: edit.php?sales_id=$site&list=".$_POST['clicked_from']);

}
else
	header( "Location: ../index.php" );
?>