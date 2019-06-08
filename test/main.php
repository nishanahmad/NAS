<?php
require '../connect.php';

$query ="select * from nas_sale where srh > 0 AND f2r > 0";
//$query ="select * from nas_sale where sales_id = 5835";
$result = mysqli_query($con, $query) or die(mysqli_error($con));				 

foreach($result as $row)
{
  $id = $row['sales_id'];
  $date = $row['entry_date'];
  $ar = $row['ar_id'];
  $eng = $row['eng_id'];
  $truck = $row['truck_no'];
  $f2r = $row['f2r'];
  $ret = $row['return_bag'];
  $bill = $row['bill_no'].'(1)';
  $cust = $row['customer_name'];
  $phone = $row['customer_phone'];
  $add1 = $row['address1'];
  $add2 = $row['address2'];
  
	$sql="INSERT INTO nas_sale (entry_date, ar_id, eng_id, truck_no, f2r, return_bag, bill_no, customer_name, customer_phone, address1, address2)
	VALUES
	('$date', '$ar', ".var_export($eng, true).", '$truck', ".var_export($f2r, true).", ".var_export($ret, true).", '$bill', '$cust', '$phone', '$add1', '$add2')";	  
	
	$insert = mysqli_query($con, $sql) or die(mysqli_error($con));	

	$update = mysqli_query($con,"UPDATE nas_sale SET f2r=null WHERE sales_id='$id'") or die(mysqli_error($con));	
}