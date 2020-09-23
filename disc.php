<?php

require 'connect.php';

$arList = mysqli_query($con, "SELECT * FROM ar_details");
foreach($arList as $ar)
{
	$date = date('Y-m-d');
	$client = $ar['id'];
	
	$srp="INSERT INTO discounts (date, type, product, client, discount)
		 VALUES
		 ('$date', 'Cash Discount', 1, $client, 0)";

	$result = mysqli_query($con, $srp) or die(mysqli_error($con));				 	

	$f2r="INSERT INTO discounts (date, type, product, client, discount)
		 VALUES
		 ('$date', 'Cash Discount', 3, $client, 0)";

	$result2 = mysqli_query($con, $f2r) or die(mysqli_error($con));		 		
}