<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require '../connect.php';
session_start();
if(isset($_SESSION["user_name"]))
{
	$sqlDate = date("Y-m-d",strtotime($_POST['date']));
	$name = $_POST['name'];
	$phone = $_POST['phone'];
	$area = $_POST['area'];
	$bags = (int)$_POST['bags'];
	$requested_by = $_SESSION['user_name'];

	$sql="INSERT INTO sheets (date, name, phone, bags, area, requested_by, status)
		 VALUES
		 ('$sqlDate', '$name', '$phone', $bags, '$area', '$requested_by', 'requested')";

	$result = mysqli_query($con, $sql) or die(mysqli_error($con));				 

	header( "Location: new.php" );

}
else
	header( "Location: ../index.php" );
?> 