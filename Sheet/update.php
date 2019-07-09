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
		
		$update = mysqli_query($con,"UPDATE sheets SET date='$sqlDate',name='$name', phone='$phone',qty=$qty,bags='$bags',area='$area',remarks='$remarks' WHERE id=$id") or die(mysqli_error($con));	
			  
		$query = mysqli_query($con,"SELECT status FROM sheets WHERE id = $id") or die(mysqli_error($con));
		$sheet = mysqli_fetch_array($query,MYSQLI_ASSOC);
		
		if($sheet['status'] == 'requested')
			header( "Location: requests.php" );
		else
			header( "Location: deliveries.php" );
	}
}	
