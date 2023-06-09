<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require '../connect.php';
session_start();
if(isset($_SESSION["user_name"]))
{
	$id = $_POST['dulpicateId'];

	$updateRequest = mysqli_query($con,"UPDATE sheets SET status ='duplicate' WHERE id=$id ") or die(mysqli_error($con));

	mysqli_commit($con);	
	header( "Location: requests.php" );
}
else
	header( "Location: ../index.php" );
?> 