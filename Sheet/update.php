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
		$sqlDate = date("Y-m-d",strtotime($_POST['date']));
		$name = $_POST['name'];
		$phone = $_POST['phone'];
		$area = $_POST['area'];
		$qty = (int)$_POST['qty'];
		$bags = (int)$_POST['bags'];
		$remarks = (int)$_POST['remarks'];
		
		$sql = "UPDATE sheets SET date='$sqlDate',name='$name', phone='$phone',qty=$qty,bags='$bags',area='$area',remarks='$remarks' WHERE id=$id";
		$query = mysqli_query($con,$sql) or die(mysqli_error($con));	
			  
		$url = 'index.php';
		header( "Location: $url" );
	}
}	
