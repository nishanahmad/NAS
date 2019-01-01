<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require '../connect.php';
session_start();
if(isset($_SESSION["user_name"]))
{
	$sqlDate = date("Y-m-d");
	$id = $_GET['id'];
	$delivered_by = $_SESSION['user_name'];

	$updateQuery = mysqli_query($con,"UPDATE sheets SET status ='cancelled' WHERE id=$id ") or die(mysqli_error($con));
	header( "Location: index.php" );
}
?>	