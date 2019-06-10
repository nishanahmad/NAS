<?php
require '../connect.php';

$query ="select * from nas_sale where f2r > 0 AND qty = 0 LIMIT 10000";

$result = mysqli_query($con, $query) or die(mysqli_error($con));				 

foreach($result as $row)
{
	$id = $row['sales_id'];
	$qty = $row['f2r'];
	$update = mysqli_query($con,"UPDATE nas_sale SET brand=3,qty=$qty WHERE sales_id='$id'") or die(mysqli_error($con));	
}