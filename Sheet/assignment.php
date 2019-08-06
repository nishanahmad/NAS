<?php 

require  '../connect.php';

$driver_id = $_GET['driver_id'];
$sheet_id = $_GET['sheet_id'];

$update1 = mysqli_query($con,"UPDATE sheets SET assigned_to='$driver_id' WHERE id='$sheet_id'") or die(mysqli_error($con));
?>
