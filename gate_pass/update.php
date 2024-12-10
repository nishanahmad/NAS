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
		$result = mysqli_query($con,"SELECT * FROM gate_pass WHERE id='$id'") or die(mysqli_error($con));
		$oldPass = mysqli_fetch_array($result,MYSQLI_ASSOC);
		
		$token_no = $_POST['token'];
		$sqlDate = date("Y-m-d", strtotime($_POST["date"])); 
		$sl_no = $_POST['sl_no'];
		$order_no = $_POST['order_no'];
		$time = $_POST['time'];
		$vehicle_id = $_POST['vehicle_id'];
		$consignor_id = $_POST['consignor'];
		$from_godown = $_POST['from_godown'];
		$delivery_at = $_POST['delivery_at'];
		$driver = $_POST['driver'];
		$driver_phone = $_POST['driver_phone'];
		$driver_license_no = $_POST['driver_license_no'];
		$ut_qty = $_POST['ut_qty'];	
		$super_qty = $_POST['super_qty'];

		$entered_by = $_SESSION["user_name"];
		$entered_on = date('Y-m-d H:i:s');
			

		if(empty($token_no))
			$token_no = null;			
		if(empty($sl_no))
			$sl_no = null;	
		if(empty($order_no))
			$order_no = null;				
		
		$update = mysqli_query($con,"UPDATE gate_pass SET token_no = ".var_export($token_no, true).", sl_no=".var_export($sl_no, true).", vehicle_id = '$vehicle_id', 
											date='$sqlDate',order_no = ".var_export($order_no, true).",consignor_id='$consignor_id',from_godown='$from_godown',
											time='$time',delivery_at='$delivery_at',driver='$driver',driver_phone='$driver_phone',
											driver_license_no='$driver_license_no',ut_qty=$ut_qty,super_qty=$super_qty
									 WHERE id='$id'") or die(mysqli_error($con));
					
		//$resultNew = mysqli_query($con,"SELECT * FROM nas_sale WHERE deleted IS NULL AND sales_id='$id'") or die(mysqli_error($con));	
		//$newSale= mysqli_fetch_array($resultNew,MYSQLI_ASSOC);					

		//updateUserDetails($oldSale,$newSale);
		
		$url = 'list.php?success';
		
		header( "Location: $url" );
	}																							
}
else
	header("Location:../index/home.php");
