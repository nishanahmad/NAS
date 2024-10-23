<?php
header('Content-Type: application/json');

require 'functions.php';

$expiriesFile = fopen('D:\ExpiryStrangle\Icici\expiries.txt','r');
while ($line = fgets($expiriesFile))
	$expiriesMap = json_decode($line, true);

fclose($expiriesFile);

$indexMap = getIndexDetails($_POST['selectedIndex']);
$indexMap['expiry_date'] = $expiriesMap[$_POST['selectedIndex']];

$iron_fly['exchange_code'] = $indexMap['fo_exchange_code'];
$iron_fly['qty'] = $_POST['qty'];
$iron_fly['validity_date'] = $indexMap['expiry_date'];
$iron_fly['expiry_date'] = $indexMap['expiry_date'];
$iron_fly['stock_code'] = $_POST['stock_code'];

$iron_fly['ce_main_strike'] = $_POST['ce_main_strike'];
$iron_fly['ce_hedge_strike'] = $_POST['ce_hedge_strike'];
			
$result = placeCeLeg($iron_fly);

echo json_encode($result);

exit;