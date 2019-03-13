<?php
function updateUserDetails($oldSale,$newSale)
{
	require '../connect.php';

	$arObjects = mysqli_query($con,"SELECT id,name FROM ar_details ORDER BY name");
	foreach($arObjects as $arObject)
	{
		$arId = $arObject['id'];
		$arMap[$arId] = $arObject['name'];
	}
		
	$id = $newSale['sales_id'];
	$user = $_SESSION["user_name"];
	$dateTime = date('Y-m-d H:i:s');	
	
	if($oldSale['entry_date'] != $newSale['entry_date'])
	{
		$oldValue = date('d-m-Y',strtotime($oldSale['entry_date']));
		$newValue = date('d-m-Y',strtotime($newSale['entry_date']));
		
		$sql="INSERT INTO sale_edits (sale_id, edited_on, edited_by, field, old_value, new_value)
			 VALUES
			 ($id, '$dateTime', '$user', 'Date', '$oldValue', '$newValue')";

		$insert = mysqli_query($con, $sql) or die(mysqli_error($con));				 				
	}
	if($oldSale['ar_id'] != $newSale['ar_id'])
	{
		$oldValue = $arMap[$oldSale['ar_id']];
		$newValue = $arMap[$newSale['ar_id']];
		
		$sql="INSERT INTO sale_edits (sale_id, edited_on, edited_by, field, old_value, new_value)
			 VALUES
			 ($id, '$dateTime', '$user', 'AR', '$oldValue', '$newValue')";

		$insert = mysqli_query($con, $sql) or die(mysqli_error($con));				 				
	}	
	if($oldSale['eng_id'] != $newSale['eng_id'])
	{
		$oldValue = $arMap[$oldSale['eng_id']];
		$newValue = $arMap[$newSale['eng_id']];
		
		$sql="INSERT INTO sale_edits (sale_id, edited_on, edited_by, field, old_value, new_value)
			 VALUES
			 ($id, '$dateTime', '$user', 'Engineer', '$oldValue', '$newValue')";

		$insert = mysqli_query($con, $sql) or die(mysqli_error($con));				 				
	}	
	if($oldSale['truck_no'] != $newSale['truck_no'])
	{
		$oldValue = $oldSale['truck_no'];
		$newValue = $newSale['truck_no'];
		
		$sql="INSERT INTO sale_edits (sale_id, edited_on, edited_by, field, old_value, new_value)
			 VALUES
			 ($id, '$dateTime', '$user', 'Truck', '$oldValue', '$newValue')";

		$insert = mysqli_query($con, $sql) or die(mysqli_error($con));				 				
	}	
	if($oldSale['srp'] != $newSale['srp'])
	{
		$oldValue = $oldSale['srp'];
		$newValue = $newSale['srp'];
		
		$sql="INSERT INTO sale_edits (sale_id, edited_on, edited_by, field, old_value, new_value)
			 VALUES
			 ($id, '$dateTime', '$user', 'SRP', '$oldValue', '$newValue')";

		$insert = mysqli_query($con, $sql) or die(mysqli_error($con));
	}	
	if($oldSale['srh'] != $newSale['srh'])
	{
		$oldValue = $oldSale['srh'];
		$newValue = $newSale['srh'];
		
		$sql="INSERT INTO sale_edits (sale_id, edited_on, edited_by, field, old_value, new_value)
			 VALUES
			 ($id, '$dateTime', '$user', 'SRH', '$oldValue', '$newValue')";

		$insert = mysqli_query($con, $sql) or die(mysqli_error($con));
	}	
	if($oldSale['f2r'] != $newSale['f2r'])
	{
		$oldValue = $oldSale['f2r'];
		$newValue = $newSale['f2r'];
		
		$sql="INSERT INTO sale_edits (sale_id, edited_on, edited_by, field, old_value, new_value)
			 VALUES
			 ($id, '$dateTime', '$user', 'F2R', '$oldValue', '$newValue')";

		$insert = mysqli_query($con, $sql) or die(mysqli_error($con));
	}	
	if($oldSale['return_bag'] != $newSale['return_bag'])
	{
		$oldValue = $oldSale['return_bag'];
		$newValue = $newSale['return_bag'];
		
		$sql="INSERT INTO sale_edits (sale_id, edited_on, edited_by, field, old_value, new_value)
			 VALUES
			 ($id, '$dateTime', '$user', 'Return', '$oldValue', '$newValue')";

		$insert = mysqli_query($con, $sql) or die(mysqli_error($con));
	}	
	if($oldSale['remarks'] != $newSale['remarks'])
	{
		$oldValue = $oldSale['remarks'];
		$newValue = $newSale['remarks'];
		
		$sql="INSERT INTO sale_edits (sale_id, edited_on, edited_by, field, old_value, new_value)
			 VALUES
			 ($id, '$dateTime', '$user', 'Remarks', '$oldValue', '$newValue')";

		$insert = mysqli_query($con, $sql) or die(mysqli_error($con));
	}	
	if($oldSale['bill_no'] != $newSale['bill_no'])
	{
		$oldValue = $oldSale['bill_no'];
		$newValue = $newSale['bill_no'];
		
		$sql="INSERT INTO sale_edits (sale_id, edited_on, edited_by, field, old_value, new_value)
			 VALUES
			 ($id, '$dateTime', '$user', 'Bill', '$oldValue', '$newValue')";

		$insert = mysqli_query($con, $sql) or die(mysqli_error($con));
	}	
	if($oldSale['customer_name'] != $newSale['customer_name'])
	{
		$oldValue = $oldSale['customer_name'];
		$newValue = $newSale['customer_name'];
		
		$sql="INSERT INTO sale_edits (sale_id, edited_on, edited_by, field, old_value, new_value)
			 VALUES
			 ($id, '$dateTime', '$user', 'Cust Name', '$oldValue', '$newValue')";

		$insert = mysqli_query($con, $sql) or die(mysqli_error($con));
	}	
	if($oldSale['customer_phone'] != $newSale['customer_phone'])
	{
		$oldValue = $oldSale['customer_phone'];
		$newValue = $newSale['customer_phone'];
		
		$sql="INSERT INTO sale_edits (sale_id, edited_on, edited_by, field, old_value, new_value)
			 VALUES
			 ($id, '$dateTime', '$user', 'Cust Phone', '$oldValue', '$newValue')";

		$insert = mysqli_query($con, $sql) or die(mysqli_error($con));
	}	
	if($oldSale['address1'] != $newSale['address1'])
	{
		$oldValue = $oldSale['address1'];
		$newValue = $newSale['address1'];
		
		$sql="INSERT INTO sale_edits (sale_id, edited_on, edited_by, field, old_value, new_value)
			 VALUES
			 ($id, '$dateTime', '$user', 'Address1', '$oldValue', '$newValue')";

		$insert = mysqli_query($con, $sql) or die(mysqli_error($con));		
	}	
	if($oldSale['address2'] != $newSale['address2'])
	{
		$oldValue = $oldSale['address2'];
		$newValue = $newSale['address2'];
		
		$sql="INSERT INTO sale_edits (sale_id, edited_on, edited_by, field, old_value, new_value)
			 VALUES
			 ($id, '$dateTime', '$user', 'Address2', '$oldValue', '$newValue')";

		$insert = mysqli_query($con, $sql) or die(mysqli_error($con));		
	}		
}