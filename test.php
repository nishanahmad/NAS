<?php
//
// A very simple PHP example that sends a HTTP POST to a remote site
//

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL,"https://nassales.in/api/retail/create.php");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query(array('entry_date' => '2021-03-10','product' => '1','qty' => '100','ar_id' => '1','customer_name' => 'Nishan','customer_phone' => '9846086862','address1' => 'Test','entered_by' => 'Nishan')));

// Receive server response ...
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$server_output = curl_exec($ch);

curl_close ($ch);

var_dump($server_output);
?>
