<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require '../connect.php';
require '../functions/sms.php';
session_start();
if(isset($_SESSION["user_name"]))
{
	$date = $_POST['date'];
	$sqlDate = date("Y-m-d", strtotime($date));
	$arId = $_POST['ar'];
	$engId = $_POST['engineer'];
	$truck = $_POST['truck'];
	$srp = $_POST['srp'];
	$srh = $_POST['srh'];
	$f2r = $_POST['f2r'];
	$return = $_POST['return'];	
	$remarks = $_POST['remarks'];
	$bill = $_POST['bill'];
	$customerName = $_POST['customerName'];
	$customerPhone = $_POST['customerPhone'];
	$address1 = $_POST['address1'];
	$address2 = $_POST['address2'];
	$entered_by = $_SESSION["user_name"];
	$entered_on = date('Y-m-d H:i:s');	
	
	if(empty($engId))
		$engId = null;	
	if(empty($srp))
		$srp = null;
	if(empty($srh))
		$srh = null;
	if(empty($f2r))
		$f2r = null;
	if(empty($return))
		$return = null;
	
	$qty = $srp + $srh + $f2r;	

	$sql="INSERT INTO nas_sale (entry_date, ar_id, eng_id, truck_no, srp, srh, f2r, return_bag, remarks, bill_no, customer_name, customer_phone, address1, address2,entered_by,entered_on)
		 VALUES
		 ('$sqlDate', '$arId', ".var_export($engId, true).", '$truck', ".var_export($srp, true).", ".var_export($srh, true).", ".var_export($f2r, true).", ".var_export($return, true).", '$remarks', '$bill', '$customerName', '$customerPhone', '$address1', '$address2', '$entered_by', '$entered_on')";

	$result = mysqli_query($con, $sql) or die(mysqli_error($con));				 


//  SEND SMS TO ENGINEER IF GOLD ACHIEVED
	if(  !empty($bill) && !(fnmatch("A*", $bill) ||  fnmatch("a*", $bill))  )
	{
		$engQuery = mysqli_query($con, "SELECT type FROM ar_details WHERE id = $arId ") or die(mysqli_error($con));
		$ar = mysqli_fetch_array($engQuery,MYSQLI_ASSOC);
		if($ar['type'] == 'Engineer')
			checkEngineerPoints($arId,$qty);
		if($engId != null)
			checkEngineerPoints($engId,$qty);
	}
	
	
	header( "Location: new.php" );

}
else
	header( "Location: ../index.php" );
?> 