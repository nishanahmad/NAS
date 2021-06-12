<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require '../connect.php';
session_start();
if(isset($_SESSION["user_name"]))
{
	$id = $_GET['id'];
	$remarks = $_GET['remarks'];
	$date = date('Y-m-d H:i:s');

	$updateQuery = mysqli_query($con,"UPDATE sheets SET driver_remarks = '$remarks', driver_remarks_dt = '$date' WHERE id=$id ") or die(mysqli_error($con));
	header( "Location: requests.php" );
}
?>	