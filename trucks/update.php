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
		
		$number = $_POST['number'];
		$driver = $_POST['driver'];
		$phone = $_POST['phone'];
		$license_no = $_POST['license_no'];
		$vehicle_type = $_POST['vehicle_type'];
		$vehicle_area = $_POST['vehicle_area'];
		
		if(empty($vehicle_type))
			$vehicle_type = null;			
		if(empty($vehicle_area))
			$vehicle_area = null;	
		
		$update = mysqli_query($con,"UPDATE truck_details SET number='$number', driver='$driver', phone='$phone', license_no='$license_no', 
											vehicle_type=".var_export($vehicle_type, true).", vehicle_area=".var_export($vehicle_area, true)."
									 WHERE id='$id'") or die(mysqli_error($con));
					
		$url = 'list.php?success';
		
		header( "Location: $url" );
	}																							
}
else
	header("Location:../index/home.php");
