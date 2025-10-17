<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require '../connect.php';
session_start();
if(isset($_SESSION["user_name"]))
{
	$id = $_GET['id'];
	
	$delete = mysqli_query($con, "DELETE FROM truck_details WHERE id = '$id' ") or die(mysqli_error($con).'Line 11');	
	header("Location:list.php?success");
}
else
	header("Location:../index.php");																				?>