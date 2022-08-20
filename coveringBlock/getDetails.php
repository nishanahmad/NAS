<?php
header('Content-Type: application/json');

require '../connect.php';

session_start();

$saleId = $_POST['saleId'];

$today = date('Y-m-d');
$tomorrow = date('Y-m-d', strtotime(' +1 day'));
$response_array['status'] = false;

$shops = mysqli_query($con,"SELECT * FROM ar_details WHERE shop_name IS NOT NULL AND shop_name != ''") or die(mysqli_error($con));
foreach($shops as $shop)
	$shopMap[$shop['id']] = $shop['shop_name'];

$mainAreaQuery = mysqli_query($con,"SELECT id,name,driver FROM sheet_area ORDER BY name") or die(mysqli_error($con));
foreach($mainAreaQuery as $mainArea)
	$mainAreaMap[$mainArea['id']] = $mainArea['name'];		
	
$drivers = mysqli_query($con,"SELECT * FROM users WHERE role = 'driver'") or die(mysqli_error($con));
foreach($drivers as $driver)
	$driverMap[$driver['user_id']] = $driver['user_name'];
	
$saleQuery = mysqli_query($con,"SELECT * FROM nas_sale WHERE sales_id = '$saleId'") or die(mysqli_error($con));
$sale = mysqli_fetch_array($saleQuery, MYSQLI_ASSOC);
$salePhone = trim($sale['customer_phone']);
$saleShop = $sale['ar_id'];

$sheetPhoneQuery = mysqli_query($con,"SELECT * FROM sheets WHERE (TRIM(customer_phone) = '$salePhone' OR TRIM(mason_phone) = '$salePhone') AND coveringBlock = 1 AND (date = '$today' OR date = '$tomorrow') AND ignore_dup = 0 AND status != 'cancelled'") or die(mysqli_error($con));
if(mysqli_num_rows($sheetPhoneQuery))
{
	$sheet = mysqli_fetch_array($sheetPhoneQuery, MYSQLI_ASSOC);	
	$response_array['status'] = true;
	$response_array['sheet_id'] = $sheet['id'];
	$response_array['area'] = $sheet['area'];
	$response_array['driver_area'] = $mainAreaMap[$sheet['driver_area']];
	$response_array['customer_name'] = $sheet['customer_name'];
	$response_array['customer_phone'] = trim($sheet['customer_phone']);
	$response_array['mason_name'] = $sheet['mason_name'];
	$response_array['mason_phone'] = trim($sheet['mason_phone']);
	$response_array['sheet_date'] = date('d-m-Y',strtotime($sheet['date']));
	$response_array['bags'] = $sheet['bags'];
	$response_array['requested_by'] = $sheet['requested_by'];
	$response_array['created_on'] = date('d M, h:i A', strtotime($sheet['created_on']));
	$response_array['remarks'] = $sheet['remarks'];
	$response_array['assigned_to'] = $driverMap[$sheet['assigned_to']];
	
	
	if($sheet['shop1'] > 0)
		$response_array['shop'] = $shopMap[$sheet['shop1']];
	if($sheet['priority'])
		$response_array['priority'] = true;
	else
		$response_array['priority'] = false;
}
else
{
	$sheetShopQuery = mysqli_query($con,"SELECT * FROM sheets WHERE shop1 = '$saleShop' AND coveringBlock = 1 AND (date = '$today' OR date = '$tomorrow') AND ignore_dup = 0 AND status != 'cancelled'") or die(mysqli_error($con));
	$sheet = mysqli_fetch_array($sheetShopQuery, MYSQLI_ASSOC);
	$response_array['status'] = true;
	$response_array['sheet_id'] = $sheet['id'];
	$response_array['area'] = $sheet['area'];
	if(isset($mainAreaMap[$sheet['driver_area']]))
		$response_array['driver_area'] = $mainAreaMap[$sheet['driver_area']];
	else
		$response_array['driver_area'] = 'No area found';
	$response_array['customer_name'] = $sheet['customer_name'];
	$response_array['customer_phone'] = trim($sheet['customer_phone']);
	$response_array['mason_name'] = $sheet['mason_name'];
	$response_array['mason_phone'] = trim($sheet['mason_phone']);
	$response_array['sheet_date'] = date('d-m-Y',strtotime($sheet['date']));
	$response_array['bags'] = $sheet['bags'];
	$response_array['requested_by'] = $sheet['requested_by'];
	$response_array['created_on'] = date('d M, h:i A', strtotime($sheet['created_on']));
	$response_array['remarks'] = $sheet['remarks'];
	if(isset($driverMap[$sheet['assigned_to']]))
		$response_array['assigned_to'] = $driverMap[$sheet['assigned_to']];
	else
		$response_array['assigned_to'] = 'No driver assigned';
	
	
	if($sheet['shop1'] > 0)
		$response_array['shop'] = $shopMap[$sheet['shop1']];	
	if($sheet['priority'])
		$response_array['priority'] = true;
	else
		$response_array['priority'] = false;
}


echo json_encode($response_array);
	
exit;