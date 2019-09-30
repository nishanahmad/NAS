<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require '../connect.php';
session_start();
if(isset($_SESSION["user_name"]))
{
	$sqlDate = date("Y-m-d");
	$id = $_GET['id'];
	$qty = (int)$_GET['qty'];
	$delivered_by = (int)$_GET['driver'];
	$date = date('Y-m-d');

	$updateQuery = mysqli_query($con,"UPDATE sheets SET delivered_on ='$date' ,status ='delivered', delivered_by =$delivered_by, qty = $qty WHERE id=$id ") or die(mysqli_error($con));
	
	
	/********************				UPDATE SHEETS IN HAND FOR THE USER				********************/

	$selectUser = mysqli_query($con,"SELECT * FROM sheets_in_hand WHERE user=$delivered_by ") or die(mysqli_error($con));
	
	if(mysqli_num_rows($selectUser) > 0)
	{
		$update = mysqli_query($con,"UPDATE sheets_in_hand SET qty =qty - $qty WHERE user=$delivered_by ") or die(mysqli_error($con));
	}
	else
	{
		$insert = mysqli_query($con,"INSERT INTO sheets_in_hand (user, qty) VALUES ($delivered_by, -$qty)") or die(mysqli_error($con));
	}
	$queryFrom = mysqli_query($con,"SELECT qty FROM sheets_in_hand WHERE user=$delivered_by ") or die(mysqli_error($con));
	$fromStock = mysqli_fetch_array($queryFrom,MYSQLI_ASSOC)['qty'];	

	$transferred_on = date('Y-m-d H:i:s');
	$transferred_by = $_SESSION['user_id'];		

	$insertLogs = mysqli_query($con, "INSERT INTO transfer_logs (user_from, qty, transferred_on, transferred_by, fromStock, site) VALUES ('$delivered_by', '$qty', '$transferred_on', '$transferred_by', '$fromStock', '$id')") or die(mysqli_error($con));
	
	header( "Location: requests.php" );

}
else
	header( "Location: ../index.php" );
?> 