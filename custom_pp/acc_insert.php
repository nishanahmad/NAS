<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require '../connect.php';
session_start();
if(isset($_SESSION["user_name"]))
{
	$year = $_POST['year'];
	$month = $_POST['month'];
	$arId = $_POST['ar'];
	$points = $_POST['points'];

 	$insert ="INSERT INTO custom_point_perc (year, month, ar, percentage)
		 VALUES
		 ('$year', '$month', '$arId' , '$points')";

	$result = mysqli_query($con, $insert) or die(mysqli_error($con));				 
		
	header( "Location: list.php?success" ); 
}
else
{
	header( "Location: ../index.php" );
}	
?> 