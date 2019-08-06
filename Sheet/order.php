<?php 

require  '../connect.php';

$sheet_id = $_GET['sheet_id'];
$old_sheet = $_GET['old_sheet'];
$driver_id = $_GET['driver_id'];
$old_order = $_GET['old_order'];
$order = $_GET['order'];

$update1 = mysqli_query($con,"UPDATE sheets SET assign_order = '$order' WHERE id = '$old_sheet'") or die(mysqli_error($con));

$update2 = mysqli_query($con,"UPDATE sheets SET assign_order='$old_order' WHERE id='$sheet_id'") or die(mysqli_error($con));

if($driver_id == 0 || $driver_id == '0')
{
	$update3 = mysqli_query($con,"UPDATE sheets SET assign_order=null WHERE id='$sheet_id'") or die(mysqli_error($con));	
}


?>
