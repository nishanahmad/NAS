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
	$half_qty = (int)$_POST['half_qty'];
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
		$updateRequest = mysqli_query($con,"UPDATE sheets SET delivered_on ='$date' ,status ='delivered', delivered_by =$delivered_by, qty = $qty, half_qty = $half_qty WHERE id=$id ");
		if(!$updateRequest)
		{
			$commitFlag = false;
			var_dump('Line 24');
		}
		
		$QuerySheetInHand = mysqli_query($con,"SELECT qty FROM sheets_in_hand WHERE user=$delivered_by");
		$newQty = mysqli_fetch_array($QuerySheetInHand,MYSQLI_ASSOC)['qty'] - $qty;
		$QueryHalfSheetInHand = mysqli_query($con,"SELECT half_qty FROM sheets_in_hand WHERE user=$delivered_by");
		$newHalfQty = mysqli_fetch_array($QueryHalfSheetInHand,MYSQLI_ASSOC)['half_qty'] - $half_qty;

		if($newQty < 0 || $newHalfQty < 0)
		{
			$commitFlag = false;	
			echo("Line 32 Error description: " . mysqli_error($con));
		}	
		else
		{
			$updateSheetInHand = mysqli_query($con,"UPDATE sheets_in_hand SET qty = qty - $qty, half_qty = half_qty - $half_qty WHERE user=$delivered_by ");
			if(!$updateSheetInHand)
			{
				$commitFlag = false;
				var_dump('Line 40');			
			}
		}		

		$transferred_on = date('Y-m-d H:i:s');
		$transferred_by = $_SESSION['user_id'];		
		
		// Insert logs with full qty
		$queryFrom = mysqli_query($con,"SELECT qty FROM sheets_in_hand WHERE user=$delivered_by ");
		if(!$queryFrom)
			$commitFlag = false;
			
		$fromStock = mysqli_fetch_array($queryFrom,MYSQLI_ASSOC)['qty'];	

		$insertLogs = mysqli_query($con, "INSERT INTO transfer_logs (user_from, qty, transferred_on, transferred_by, fromStock, site) VALUES ('$delivered_by', '$qty', '$transferred_on', '$transferred_by', '$fromStock', '$id')");;
		if(!$insertLogs)
			$commitFlag = false;	
		
		// Insert logs with half qty
		$queryFrom = mysqli_query($con,"SELECT half_qty FROM sheets_in_hand WHERE user=$delivered_by ");
		if(!$queryFrom)
			$commitFlag = false;
			
		$fromStock = mysqli_fetch_array($queryFrom,MYSQLI_ASSOC)['half_qty'];	

		$insertLogs = mysqli_query($con, "INSERT INTO half_transfer_logs (user_from, qty, transferred_on, transferred_by, fromStock, site) VALUES ('$delivered_by', '$half_qty', '$transferred_on', '$transferred_by', '$fromStock', '$id')");;
		if(!$insertLogs)
			$commitFlag = false;	

		
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