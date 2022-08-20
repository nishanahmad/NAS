<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require '../connect.php';

session_start();
if(isset($_SESSION["user_name"]))
{
	$sheetId = $_GET['sheet_id'];

	$updateQuery = "UPDATE sheets SET ignore_dup = 1 WHERE id = $sheetId";
	$update = mysqli_query($con, $updateQuery) or die(mysqli_error($con));							
			
	header('Location: list.php');
}
else
	header( "Location: ../index/home.php" );
?>