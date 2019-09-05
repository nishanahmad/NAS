<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
if(isset($_SESSION["user_name"]))
{
	require '../connect.php';
	if(count($_POST)>0) 
	{	
		var_dump($_POST);
		$id = $_POST['id'];
		$name = $_POST['name'];
		$phone = $_POST['phone'];
		$area = $_POST['area'];
		$qty = (int)$_POST['qty'];
		$bags = (int)$_POST['bags'];
		$shop = $_POST['shop'];
		$remarks = $_POST['remarks'];
		
		
		$update = mysqli_query($con,"UPDATE sheets SET name='$name', phone='$phone',qty=$qty,bags='$bags',shop='$shop',area='$area',remarks='$remarks' WHERE id=$id") or die(mysqli_error($con));	
			  
		if(isset($_POST['date']))
		{
			$sqlDate = date("Y-m-d",strtotime($_POST['date']));
			$update = mysqli_query($con,"UPDATE sheets SET date='$sqlDate' WHERE id=$id") or die(mysqli_error($con));	
		}
		
		if(isset($_POST['delivered_on']))
		{
			$delivered_on = date("Y-m-d",strtotime($_POST['delivered_on']));
			$update = mysqli_query($con,"UPDATE sheets SET delivered_on='$delivered_on' WHERE id=$id") or die(mysqli_error($con));	
		}		
			
		
		$query = mysqli_query($con,"SELECT status FROM sheets WHERE id = $id") or die(mysqli_error($con));
		$sheet = mysqli_fetch_array($query,MYSQLI_ASSOC);
		
		if($sheet['status'] == 'requested')
			header( "Location: requests.php" );
		else
			header( "Location: deliveries.php" );
		
	}
}	
