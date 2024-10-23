<?php
header('Content-Type: application/json');

require 'functions.php';

$expiriesFile = fopen('D:\ExpiryStrangle\Icici\expiries.txt','r');
while ($line = fgets($expiriesFile))
	$expiriesMap = json_decode($line, true);

fclose($expiriesFile);

$indexMap = getIndexDetails($_POST['selectedIndex']);
$indexMap['expiry_date'] = $expiriesMap[$_POST['selectedIndex']];

$expiryDate = str_replace("T06:00:00.000Z", "",$indexMap['expiry_date']);
$expiryDate = date("d-M-Y",strtotime($expiryDate));

$ce_main["strike_price"] = $_POST['ce_main_strike'];
$ce_main["quantity"] = $indexMap['lot_size'];
$ce_main["right"] = "call";
$ce_main["product"] = "options";
$ce_main["action"] = "sell";
$ce_main["price"] = $_POST['ce_main_price'];
$ce_main["expiry_date"] = $expiryDate;
$ce_main["stock_code"] = $indexMap['stock_code'];
$ce_main["cover_order_flow"] = "N";
$ce_main["fresh_order_type"] = "N";
$ce_main["cover_limit_rate"] = "0";
$ce_main["cover_sltp_price"] = "0";
$ce_main["fresh_limit_rate"] = "0";
$ce_main["open_quantity"] = "0";

$ce_hedge["strike_price"] = $_POST['ce_hedge_strike'];
$ce_hedge["quantity"] = $indexMap['lot_size'];
$ce_hedge["right"] = "call";
$ce_hedge["product"] = "options";
$ce_hedge["action"] = "buy";
$ce_hedge["price"] = $_POST['ce_hedge_price'];
$ce_hedge["expiry_date"] = $expiryDate;
$ce_hedge["stock_code"] = $indexMap['stock_code'];
$ce_hedge["cover_order_flow"] = "N";
$ce_hedge["fresh_order_type"] = "N";
$ce_hedge["cover_limit_rate"] = "0";
$ce_hedge["cover_sltp_price"] = "0";
$ce_hedge["fresh_limit_rate"] = "0";
$ce_hedge["open_quantity"] = "0";

$pe_main["strike_price"] = $_POST['pe_main_strike'];
$pe_main["quantity"] = $indexMap['lot_size'];
$pe_main["right"] = "put";
$pe_main["product"] = "options";
$pe_main["action"] = "sell";
$pe_main["price"] = $_POST['pe_main_price'];
$pe_main["expiry_date"] = $expiryDate;
$pe_main["stock_code"] = $indexMap['stock_code'];
$pe_main["cover_order_flow"] = "N";
$pe_main["fresh_order_type"] = "N";
$pe_main["cover_limit_rate"] = "0";
$pe_main["cover_sltp_price"] = "0";
$pe_main["fresh_limit_rate"] = "0";
$pe_main["open_quantity"] = "0";	

$pe_hedge["strike_price"] = $_POST['pe_hedge_strike'];
$pe_hedge["quantity"] = $indexMap['lot_size'];
$pe_hedge["right"] = "put";
$pe_hedge["product"] = "options";
$pe_hedge["action"] = "buy";
$pe_hedge["price"] = $_POST['pe_hedge_price'];
$pe_hedge["expiry_date"] = $expiryDate;
$pe_hedge["stock_code"] = $indexMap['stock_code'];
$pe_hedge["cover_order_flow"] = "N";
$pe_hedge["fresh_order_type"] = "N";
$pe_hedge["cover_limit_rate"] = "0";
$pe_hedge["cover_sltp_price"] = "0";
$pe_hedge["fresh_limit_rate"] = "0";
$pe_hedge["open_quantity"] = "0";

$iron_fly['array'] = array($ce_main,$ce_hedge,$pe_main,$pe_hedge);
$iron_fly['exchange_code'] = $indexMap['fo_exchange_code'];

$result = calculateMargin($iron_fly);

echo json_encode($result);

exit;