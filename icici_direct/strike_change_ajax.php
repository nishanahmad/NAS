<?php
header('Content-Type: application/json');

require 'functions.php';

$expiriesFile = fopen('D:\ExpiryStrangle\Icici\expiries.txt','r');
while ($line = fgets($expiriesFile))
	$expiriesMap = json_decode($line, true);

fclose($expiriesFile);

$indexMap = getIndexDetails($_POST['selectedIndex']);
$indexMap['expiry_date'] = $expiriesMap[$_POST['selectedIndex']];

$right = $_POST['right'];
$strike = $_POST['strike'];

$instrument_details['stock_code'] = $indexMap['stock_code'];
$instrument_details['exchange_code'] = $indexMap['fo_exchange_code'];
$instrument_details['expiry_date'] = $indexMap['expiry_date'];
$instrument_details['product_type'] = 'options';
$instrument_details['right'] = $right;
$instrument_details['strike_price'] = (int)$strike;

/*
$errorFile = fopen('ajaxError.txt', 'w');
fwrite($errorFile, json_encode($instrument_details));
fclose($errorFile);
*/

$result = parseResult(fetchData($instrument_details),$instrument_details);

echo json_encode($result);

exit;