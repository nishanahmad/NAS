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
{
	$mainAreaMap[$mainArea['id']] = $mainArea['name'];		
	$areaDriverMap[$mainArea['id']] = $mainArea['driver'];		
}
	
$saleQuery = mysqli_query($con,"SELECT * FROM nas_sale WHERE sales_id = '$saleId'") or die(mysqli_error($con));
$sale = mysqli_fetch_array($saleQuery, MYSQLI_ASSOC);
$salePhone = trim($sale['customer_phone']);
$saleShop = $sale['ar_id'];

$sheetPhoneQuery = mysqli_query($con,"SELECT * FROM sheets WHERE (TRIM(customer_phone) = '$salePhone' OR TRIM(mason_phone) = '$salePhone') AND coveringBlock = 1 AND (date = '$today' OR date = '$tomorrow')") or die(mysqli_error($con));
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
	if($sheet['shop1'] > 0)
		$response_array['shop'] = $shopMap[$sheet['shop1']];	
}
else
{
	$sheetShopQuery = mysqli_query($con,"SELECT * FROM sheets WHERE shop1 = '$saleShop' AND coveringBlock = 1 AND (date = '$today' OR date = '$tomorrow')") or die(mysqli_error($con));
	$sheet = mysqli_fetch_array($sheetPhoneQuery, MYSQLI_ASSOC);
	$response_array['status'] = true;
	$response_array['sheet_id'] = $sheet['id'];
	$response_array['area'] = $sheet['area'];
	$response_array['driver_area'] = $mainAreaMap[$sheet['driver_area']];
	$response_array['customer_name'] = $sheet['customer_name'];
	$response_array['customer_phone'] = $sheet['customer_phone'];
	$response_array['mason_name'] = $sheet['mason_name'];
	$response_array['mason_phone'] = $sheet['mason_phone'];
	$response_array['shop'] = $shopMap[$sheet['shop1']];	
}


echo json_encode($response_array);
	
exit;