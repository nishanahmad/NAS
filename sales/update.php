<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
if(isset($_SESSION["user_name"]))
{
	require '../connect.php';
	require '../functions/sms.php';
	require 'updateUserDetails.php';
	if(count($_POST)>0) 
	{	

		$id = $_POST['id'];
		$result = mysqli_query($con,"SELECT * FROM nas_sale WHERE sales_id='$id'") or die(mysqli_error($con));	
		$oldSale= mysqli_fetch_array($result,MYSQLI_ASSOC);
		
		$sqlDate = date("Y-m-d", strtotime($_POST["entryDate"])); 
		$arId = $_POST['ar'];
		$engId = $_POST['engineer'];
		$truck = $_POST['truck'];
		$brand = $_POST['brand'];
		$qty = $_POST['qty'];
		$return = $_POST['return'];	
		$remarks = $_POST['remarks'];
		$bill = $_POST['bill'];
		$customerName = $_POST['customerName'];
		$customerPhone = $_POST['customerPhone'];
		$address1 = $_POST['address1'];
		$address2 = $_POST['address2'];
		$entered_by = $_SESSION["user_name"];
		$entered_on = date('Y-m-d H:i:s');	
		if(empty($engId))
			$engId = null;	
		if(empty($return))
			$return = null;		
		
		
		$update = mysqli_query($con,"UPDATE nas_sale SET entry_date='$sqlDate', ar_id='$arId', eng_id = ".var_export($engId, true).", truck_no='$truck',
									brand='$brand',qty='$qty',return_bag=".var_export($return, true).",remarks='$remarks', 
									bill_no='$bill',address1='$address1', address2='$address2', customer_name='$customerName', customer_phone='$customerPhone'
									WHERE sales_id='$id'") or die(mysqli_error($con));
					
		$resultNew = mysqli_query($con,"SELECT * FROM nas_sale WHERE sales_id='$id'") or die(mysqli_error($con));	
		$newSale= mysqli_fetch_array($resultNew,MYSQLI_ASSOC);					

		updateUserDetails($oldSale,$newSale);
		
		$url = 'todayList.php?ar=all';
		header( "Location: $url" );
	}																							
}
else
	header("Location:../index.php");
