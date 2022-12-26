<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
if(isset($_SESSION["user_name"]))
{
	require '../connect.php';
	
	if(count($_POST)>0) 
	{
		$arId = $_POST['ar'];
		$shop_name = $_POST['shop_name'];
		$whatsapp = $_POST['whatsapp'];
		$child_code = $_POST['child_code'];
		$parent_code = $_POST['parent_code'];
		$type = $_POST['type'];
		
		if(empty($shop_name))
			$shop_name = null;			
		if(empty($whatsapp))
			$whatsapp = null;			
		if(empty($child_code))
			$child_code = null;			
		if(empty($parent_code))
			$parent_code = null;					
		
		$sql = "UPDATE ar_details SET shop_name=".var_export($shop_name, true).",whatsapp=".var_export($whatsapp, true).",
									  child_code=".var_export($child_code, true).",parent_code=".var_export($parent_code, true).",
									  type = '$type' WHERE id = $arId";
		$update = mysqli_query($con,$sql) or die(mysqli_error($con));
							
		$url = 'edit.php?success&id='.$arId;
		
		header( "Location: $url" );
	}																						
}
else
	header("Location:../index.php");
