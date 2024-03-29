<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require '../connect.php';
session_start();
if(isset($_SESSION["user_name"]))
{
	$id = $_POST['deliverIdhidden'];
	$date = date('Y-m-d');
	$qty = (int)$_POST['qty'];
	if(isset($_POST['driverId']))
		$delivered_by = (int)$_POST['driverId'];
	else	
		$delivered_by = (int)$_SESSION['user_id'];

	$commitFlag = true;
	mysqli_autocommit($con, FALSE);
	
	$queryRequest = mysqli_query($con,"SELECT status FROM sheets WHERE id=$id ");
	$status = mysqli_fetch_array($queryRequest,MYSQLI_ASSOC)['status'];
	if($status != 'delivered')
	{
		$updateRequest = mysqli_query($con,"UPDATE sheets SET delivered_on ='$date' ,status ='delivered', delivered_by =$delivered_by, qty = $qty WHERE id=$id ");
		if(!$updateRequest)
		{
			$commitFlag = false;
			var_dump('Line 24');
		}
		
		$QuerySheetInHand = mysqli_query($con,"SELECT qty FROM sheets_in_hand WHERE user=$delivered_by");
		$newQty = mysqli_fetch_array($QuerySheetInHand,MYSQLI_ASSOC)['qty'] - $qty;
		if($newQty < 0)
		{
			$commitFlag = false;	
			var_dump('Line 32');
		}	
		else
		{
			$updateSheetInHand = mysqli_query($con,"UPDATE sheets_in_hand SET qty =qty - $qty WHERE user=$delivered_by ");
			if(!$updateSheetInHand)
			{
				$commitFlag = false;
				var_dump('Line 40');			
			}
		}		

		$queryFrom = mysqli_query($con,"SELECT qty FROM sheets_in_hand WHERE user=$delivered_by ");
		if(!$queryFrom)
		{
			$commitFlag = false;	
			var_dump('Line 48');					
		}
			
		$fromStock = mysqli_fetch_array($queryFrom,MYSQLI_ASSOC)['qty'];	

		$transferred_on = date('Y-m-d H:i:s');
		$transferred_by = $_SESSION['user_id'];		

		$insertLogs = mysqli_query($con, "INSERT INTO transfer_logs (user_from, qty, transferred_on, transferred_by, fromStock, site) VALUES ('$delivered_by', '$qty', '$transferred_on', '$transferred_by', '$fromStock', '$id')");;
		if(!$insertLogs)
		{
			$commitFlag = false;	
			var_dump('Line 60');							
		}
		
		if($commitFlag)
		{
			mysqli_commit($con);	
			header( "Location: requests.php" );
		}
		else
		{
			mysqli_rollback($con);
			header( "Location: requests.php?error=true" );		
		}		
	}
	else
	{
		header( "Location: requests.php" );
	}

}
else
	header( "Location: ../index.php" );
?> 