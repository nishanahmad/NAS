<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
if(isset($_SESSION["user_name"]))
{
	require '../connect.php';
	require '../functions/sms.php';
	if(count($_POST)>0) 
	{	

		$id = $_POST['id'];
		$result = mysqli_query($con,"SELECT * FROM nas_sale WHERE sales_id='$id'") or die(mysqli_error($con));	
		$old= mysqli_fetch_array($result,MYSQLI_ASSOC);

		$sqlDate = date("Y-m-d", strtotime($_POST["entryDate"])); 
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
		
		$qty = $srp + $srh + $f2r - $return;
		
		$update = mysqli_query($con,"UPDATE nas_sale SET entry_date='$sqlDate', ar_id='$arId', eng_id = ".var_export($engId, true).", truck_no='$truck',
									srp=".var_export($srp, true).", srh=".var_export($srh, true).", f2r=".var_export($f2r, true).",return_bag=".var_export($return, true).",
									remarks='$remarks', bill_no='$bill',address1='$address1', address2='$address2', customer_name='$customerName', customer_phone='$customerPhone'
									WHERE sales_id='$id'") or die(mysqli_error($con));	
			  
		if(  empty($old['bill_no']) || fnmatch("A*", $old['bill_no']) || fnmatch("a*", $old['bill_no'])  )
		{
			if(  !empty($bill) && !fnmatch("A*", $bill) && !fnmatch("a*", $bill)  )
			{
				$arQuery = mysqli_query($con,"SELECT type FROM ar_details WHERE id='$arId'") or die(mysqli_error($con));	
				$ar = mysqli_fetch_array($arQuery,MYSQLI_ASSOC);
				if(fnmatch("Engineer*", $ar['type']))
					checkEngineerPoints($arId,$qty);	
				if($engId != null)
					checkEngineerPoints($engId,$qty);				
			}
		}
			
		$url = 'todayList.php?ar=all';
		header( "Location: $url" );
	}																							
}
else
	header("Location:../index.php");
