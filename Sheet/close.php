<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require '../connect.php';
session_start();
if(isset($_SESSION["user_name"]))
{
	$sqlDate = date("Y-m-d");
	$id = $_GET['id'];
	$closed_by = (int)$_GET['driver'];

	$sheet = mysqli_query($con,"SELECT * FROM sheets WHERE id=$id ") or die(mysqli_error($con));
	$qty = (int)mysqli_fetch_array($sheet,MYSQLI_ASSOC)['qty'];


	$updateQuery = mysqli_query($con,"UPDATE sheets SET status ='closed', closed_on='$sqlDate', closed_by = $closed_by WHERE id=$id ") or die(mysqli_error($con));



	/********************				UPDATE SHEETS IN HAND FOR THE USER				********************/

	$selectUser = mysqli_query($con,"SELECT * FROM sheets_in_hand WHERE user=$closed_by ") or die(mysqli_error($con));
	
	if(mysqli_num_rows($selectUser) > 0)
	{
		$update = mysqli_query($con,"UPDATE sheets_in_hand SET qty =qty + $qty WHERE user=$closed_by ") or die(mysqli_error($con));		
	}
	else
	{
		$insert = mysqli_query($con,"INSERT INTO sheets_in_hand (user, qty) VALUES ($closed_by, $qty)") or die(mysqli_error($con));
	}

	header( "Location: deliveries.php" );
}
else
	header( "Location: ../index.php" );
?>