<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require '../connect.php';
session_start();
if(isset($_SESSION["user_name"]))
{
	$sqlDate = date("Y-m-d");
	$id = $_POST['closeIdhidden'];
	$qty = (int)$_POST['qty'];
	
	if(isset($_POST['driverId']))
		$closed_by = (int)$_POST['driverId'];
	else
		$closed_by = (int)$_SESSION['user_id'];
	
	//$panelId = 'panel'.$_GET['panelId'];

	$sheet = mysqli_query($con,"SELECT * FROM sheets WHERE id=$id AND status != 'closed'") or die(mysqli_error($con));
	if(mysqli_num_rows($sheet) > 0)
	{
		$originalQty = (int)mysqli_fetch_array($sheet,MYSQLI_ASSOC)['qty'];

		if($qty == $originalQty)
		{
			$updateQuery = "UPDATE sheets SET status ='closed', closed_on='$sqlDate', closed_by = $closed_by WHERE id=$id";
		}
		else if($qty < $originalQty)
		{
			$diff = $originalQty - $qty;
			$updateQuery = "UPDATE sheets SET qty = $diff WHERE id=$id ";
		}
		else
		{
			echo 'Error !!!!';
			exit;
		}
		
		$update = mysqli_query($con,$updateQuery);
		if($update)
		{
			/********************				UPDATE SHEETS IN HAND FOR THE USER				********************/
			$selectUser = mysqli_query($con,"SELECT * FROM sheets_in_hand WHERE user=$closed_by ") or die(mysqli_error($con));
			
			if(mysqli_num_rows($selectUser) > 0)
			{
				$update = mysqli_query($con,"UPDATE sheets_in_hand SET qty =qty + $qty WHERE user=$closed_by ") or die(mysqli_error($con));		
			}
			else
			{
				$insert = mysqli_query($con,"INSERT INTO sheets_in_hand (user, qty) VALUES ($closed_by, $qty)") or die(mysqli_error($con));
			}

			$queryTo = mysqli_query($con,"SELECT qty FROM sheets_in_hand WHERE user=$closed_by ") or die(mysqli_error($con));
			$toStock = mysqli_fetch_array($queryTo,MYSQLI_ASSOC)['qty'];

			$transferred_on = date('Y-m-d H:i:s');
			$transferred_by = $_SESSION['user_id'];		

			$insertLogs = mysqli_query($con, "INSERT INTO transfer_logs (user_to, qty, transferred_on, transferred_by, toStock, site) VALUES ('$closed_by', '$qty', '$transferred_on', '$transferred_by', '$toStock', '$id')") or die(mysqli_error($con));		
		}				
	}
	
	header( "Location: deliveries.php?");
}
else
	header( "Location: ../index.php" );
?>