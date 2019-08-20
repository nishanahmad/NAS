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
	$delivered_by = $_GET['driver'];
	$user = $_SESSION['user_id'];
	$date = date('Y-m-d');

	$updateQuery = mysqli_query($con,"UPDATE sheets SET date ='$date' ,status ='delivered', delivered_by ='$delivered_by', qty = $qty WHERE id=$id ") or die(mysqli_error($con));			 
	
	
	/********************				UPDATE SHEETS IN HAND FOR THE USER				********************/

	$selectUser = mysqli_query($con,"SELECT * FROM sheets_in_hand WHERE user=$user ") or die(mysqli_error($con));
	
	if(mysqli_num_rows($selectUser) > 0)
	{
		$update = mysqli_query($con,"UPDATE sheets_in_hand SET qty =qty - $qty WHERE user=$user ") or die(mysqli_error($con));		
	}
	else
	{
		$insert = mysqli_query($con,"INSERT INTO sheets_in_hand (user, qty) VALUES ($user, -$qty)") or die(mysqli_error($con));
	}
	
	header( "Location: requests.php" );

}
else
	header( "Location: ../index.php" );
?> 