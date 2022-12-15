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
		
		$sql = "UPDATE ar_details SET shop_name='$shop_name',whatsapp='$whatsapp',child_code='$child_code',parent_code='$parent_code' WHERE id = $arId";
		$update = mysqli_query($con,$sql) or die(mysqli_error($con));
							
		$url = 'edit.php?success&id='.$arId;
		
		header( "Location: $url" );
	}																						
}
else
	header("Location:../index.php");
