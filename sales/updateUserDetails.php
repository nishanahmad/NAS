<?php
function updateUserDetails($oldSale,$newSale)
{
	require '../connect.php';
	
	$id = $newSale['sales_id'];
	
	if($oldSale['entry_date'] != $newSale['entry_date'])
	{
		$user = $_SESSION["user_name"];
		$dateTime = date('Y-m-d H:i:s');
		$update = mysqli_query($con,"UPDATE nas_sale SET entry_date_mod ='$user', entry_date_dt='$dateTime'
									 WHERE sales_id='$id'") or die(mysqli_error($con));		
	}
	if($oldSale['ar_id'] != $newSale['ar_id'])
	{
		$user = $_SESSION["user_name"];
		$dateTime = date('Y-m-d H:i:s');
		$update = mysqli_query($con,"UPDATE nas_sale SET ar_mod ='$user', ar_dt='$dateTime'
									 WHERE sales_id='$id'") or die(mysqli_error($con));		
	}	
	if($oldSale['eng_id'] != $newSale['eng_id'])
	{
		$user = $_SESSION["user_name"];
		$dateTime = date('Y-m-d H:i:s');		
		$update = mysqli_query($con,"UPDATE nas_sale SET eng_mod ='$user', eng_dt='$dateTime'
									 WHERE sales_id='$id'") or die(mysqli_error($con));		
	}	
	if($oldSale['truck_no'] != $newSale['truck_no'])
	{
		$user = $_SESSION["user_name"];
		$dateTime = date('Y-m-d H:i:s');		
		$update = mysqli_query($con,"UPDATE nas_sale SET truck_no_mod ='$user', 	truck_no_dt='$dateTime'
									 WHERE sales_id='$id'") or die(mysqli_error($con));		
	}	
	if($oldSale['srp'] != $newSale['srp'])
	{
		$user = $_SESSION["user_name"];
		$dateTime = date('Y-m-d H:i:s');		
		$update = mysqli_query($con,"UPDATE nas_sale SET srp_mod ='$user', srp_dt='$dateTime'
									 WHERE sales_id='$id'") or die(mysqli_error($con));		
	}	
	if($oldSale['srh'] != $newSale['srh'])
	{
		$user = $_SESSION["user_name"];
		$dateTime = date('Y-m-d H:i:s');		
		$update = mysqli_query($con,"UPDATE nas_sale SET srh_mod ='$user', srh_dt='$dateTime'
									 WHERE sales_id='$id'") or die(mysqli_error($con));		
	}	
	if($oldSale['f2r'] != $newSale['f2r'])
	{
		$user = $_SESSION["user_name"];
		$dateTime = date('Y-m-d H:i:s');		
		$update = mysqli_query($con,"UPDATE nas_sale SET f2r_mod ='$user', f2r_dt='$dateTime'
									 WHERE sales_id='$id'") or die(mysqli_error($con));		
	}	
	if($oldSale['return_bag'] != $newSale['return_bag'])
	{
		$user = $_SESSION["user_name"];
		$dateTime = date('Y-m-d H:i:s');		
		$update = mysqli_query($con,"UPDATE nas_sale SET return_mod ='$user', return_dt='$dateTime'
									 WHERE sales_id='$id'") or die(mysqli_error($con));		
	}	
	if($oldSale['remarks'] != $newSale['remarks'])
	{
		$user = $_SESSION["user_name"];
		$dateTime = date('Y-m-d H:i:s');		
		$update = mysqli_query($con,"UPDATE nas_sale SET remarks_mod ='$user', remarks_dt='$dateTime'
									 WHERE sales_id='$id'") or die(mysqli_error($con));		
	}	
	if($oldSale['bill_no'] != $newSale['bill_no'])
	{
		$user = $_SESSION["user_name"];
		$dateTime = date('Y-m-d H:i:s');		
		$update = mysqli_query($con,"UPDATE nas_sale SET bill_no_mod ='$user', bill_no_dt='$dateTime'
									 WHERE sales_id='$id'") or die(mysqli_error($con));		
	}	
	if($oldSale['customer_name'] != $newSale['customer_name'])
	{
		$user = $_SESSION["user_name"];
		$dateTime = date('Y-m-d H:i:s');		
		$update = mysqli_query($con,"UPDATE nas_sale SET customer_name_mod ='$user', customer_name_dt='$dateTime'
									 WHERE sales_id='$id'") or die(mysqli_error($con));		
	}	
	if($oldSale['customer_phone'] != $newSale['customer_phone'])
	{
		$user = $_SESSION["user_name"];
		$dateTime = date('Y-m-d H:i:s');		
		$update = mysqli_query($con,"UPDATE nas_sale SET customer_phone_mod ='$user', customer_phone_dt='$dateTime'
									 WHERE sales_id='$id'") or die(mysqli_error($con));		
	}	
	if($oldSale['address1'] != $newSale['address1'])
	{
		$user = $_SESSION["user_name"];
		$dateTime = date('Y-m-d H:i:s');		
		$update = mysqli_query($con,"UPDATE nas_sale SET address1_mod ='$user', address1_dt='$dateTime'
									 WHERE sales_id='$id'") or die(mysqli_error($con));		
	}	
	if($oldSale['address2'] != $newSale['address2'])
	{
		$user = $_SESSION["user_name"];
		$dateTime = date('Y-m-d H:i:s');		
		$update = mysqli_query($con,"UPDATE nas_sale SET address2_mod ='$user', address2_dt='$dateTime'
									 WHERE sales_id='$id'") or die(mysqli_error($con));		
	}		
}