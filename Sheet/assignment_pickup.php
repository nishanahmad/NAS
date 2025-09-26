<?php 

require  '../connect.php';

$driver = $_GET['driver'];
$sheet = $_GET['sheet'];

$update = mysqli_query($con,"UPDATE sheets SET delivered_by='$driver', pickup_reassigned = 1 WHERE id='$sheet'") or die(mysqli_error($con));
?>
