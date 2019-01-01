<?php

	$message = "Hello";
	$phone = "9846086862";
	
	$curl = curl_init();
	
	curl_setopt_array($curl, array(
			CURLOPT_URL => "https://control.msg91.com/api/postsms.php",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => "<MESSAGE><AUTHKEY>212006AOQzrpS4jW5adee1be</AUTHKEY><ROUTE>AUTO</ROUTE><CAMPAIGN>ENGINEERS</CAMPAIGN><COUNTRY>91</COUNTRY><SENDER>ACCHLP</SENDER><SMS TEXT=\"".$message."\"><ADDRESS TO=\"".$phone."\"></ADDRESS></SMS></MESSAGE>",
			CURLOPT_SSL_VERIFYHOST => 0,
			CURLOPT_SSL_VERIFYPEER => 0,
			CURLOPT_HTTPHEADER => array(
			"content-type: application/xml"
			),
		)
	);

	$response = curl_exec($curl);
	$err = curl_error($curl);

	curl_close($curl);