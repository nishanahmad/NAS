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
		$customer_name = $_POST['customer_name'];
		$customer_phone = $_POST['customer_phone'];
		$mason_name = $_POST['mason_name'];
		$mason_phone = $_POST['mason_phone'];		
		$area = $_POST['area'];
		$driver_area = $_POST['driver_area'];
		if(isset($_POST['shop1']) && !empty($_POST['shop1']))
			$shop1 = $_POST['shop1'];
		else
			$shop1 = 0;
		
		$remarks = $_POST['remarks'];

		if(isset($_POST['priority']))
			$priority = 1;
		else	
			$priority = 0;
			
		if(isset($_POST['block']))
			$block = 1;
		else	
			$block = 0;
		
		
		$update = mysqli_query($con,"UPDATE sheets SET customer_name='$customer_name', customer_phone='$customer_phone',mason_name='$mason_name', mason_phone='$mason_phone',shop1='$shop1',driver_area=$driver_area,area='$area',remarks='$remarks',priority=$priority,coveringBlock=$block WHERE id=$id") or die(mysqli_error($con));	
			  
		if(isset($_POST['date']))
		{
			$sqlDate = date("Y-m-d",strtotime($_POST['date']));
			$update = mysqli_query($con,"UPDATE sheets SET date='$sqlDate' WHERE id=$id") or die(mysqli_error($con));	
		}
		
		if(isset($_POST['qty']))
		{
			$qty = (int)$_POST['qty'];
			$oldQuery = mysqli_query($con,"SELECT * FROM sheets WHERE id=$id") or die(mysqli_error($con));
			$old = mysqli_fetch_array($oldQuery,MYSQLI_ASSOC);
			$oldQty = (int)$old['qty'];
			$driver = $old['delivered_by'];
			if($oldQty != $qty)
			{
				$diff = $qty - $oldQty;
				$updateInHand = mysqli_query($con,"UPDATE sheets_in_hand SET qty = qty - $diff WHERE user = $driver") or die(mysqli_error($con));
				$update = mysqli_query($con,"UPDATE sheets SET qty=$qty WHERE id=$id") or die(mysqli_error($con));			
			}
		}		
		
		if(isset($_POST['bags']))
		{
			$bags = (int)$_POST['bags'];
			$update = mysqli_query($con,"UPDATE sheets SET bags=$bags WHERE id=$id") or die(mysqli_error($con));	
		}				
		
		if(isset($_POST['delivered_on']))
		{
			$sqlDate = date("Y-m-d",strtotime($_POST['delivered_on']));
			$update = mysqli_query($con,"UPDATE sheets SET date='$sqlDate' WHERE id=$id") or die(mysqli_error($con));	
		}				
			
		
		$query = mysqli_query($con,"SELECT status FROM sheets WHERE id = $id") or die(mysqli_error($con));
		$sheet = mysqli_fetch_array($query,MYSQLI_ASSOC);
		
		if($sheet['status'] == 'requested')
			header( "Location: requests.php" );
		else
			header( "Location: deliveries.php" );
		
	}
}	
