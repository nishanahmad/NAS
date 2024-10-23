<?php
header('Content-Type: application/json');

require 'functions.php';

$expiriesFile = fopen('D:\ExpiryStrangle\Icici\expiries.txt','r');
while ($line = fgets($expiriesFile))
	$expiriesMap = json_decode($line, true);

fclose($expiriesFile);

$indexMap = getIndexDetails($_POST['selectedIndex']);
$indexMap['expiry_date'] = $expiriesMap[$_POST['selectedIndex']];

$iron_fly['expiry_date'] = $indexMap['expiry_date'];
$iron_fly['exchange_code'] = $indexMap['fo_exchange_code'];

$iron_fly['ce_main_stock_code'] = $_POST['ce_main_stock_code'];
$iron_fly['ce_main_strike'] = $_POST['ce_main_strike'];

$iron_fly['ce_hedge_stock_code'] = $_POST['ce_hedge_stock_code'];
$iron_fly['ce_hedge_strike'] = $_POST['ce_hedge_strike'];


$iron_fly['pe_main_stock_code'] = $_POST['pe_main_stock_code'];
$iron_fly['pe_main_strike'] = $_POST['pe_main_strike'];


$iron_fly['pe_hedge_stock_code'] = $_POST['pe_hedge_stock_code'];
$iron_fly['pe_hedge_strike'] = $_POST['pe_hedge_strike'];
			
$result = parseResultIronFly(fetchDataIronFly($iron_fly));

echo json_encode($result);

exit;