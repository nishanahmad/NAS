<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require '../connect.php';
session_start();
if(isset($_SESSION["user_name"]))
{
	$sqlDate = date("Y-m-d");
	$id = $_GET['id'];
	$qty = $_GET['qty'];
	$delivered_by = $_SESSION['user_name'];
	$date = date('Y-m-d');

	$updateQuery = mysqli_query($con,"UPDATE sheets SET date ='$date' ,status ='delivered', delivered_by ='$delivered_by', qty = '$qty' WHERE id=$id ") or die(mysqli_error($con));			 
	
	header( "Location: index.php" );

}
else
	header( "Location: ../index.php" );
?> 