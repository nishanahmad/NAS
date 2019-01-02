<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
if(isset($_SESSION["user_name"]))
{
	require '../connect.php';
	if(count($_POST)>0) 
	{	
		$id = $_POST['id'];
		$name = $_POST['name'];
		$phone = $_POST['phone'];
		$area = $_POST['area'];
		$qty = (int)$_POST['qty'];
		
		$sql = "UPDATE sheets SET name='$name', phone='$phone',qty=$qty,area='$area' WHERE id=$id";
		$query = mysqli_query($con,$sql) or die(mysqli_error($con));	
			  
		$url = 'index.php';
		header( "Location: $url" );
	}
}	
