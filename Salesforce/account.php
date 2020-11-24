<?php

require 'authenticate.php';

$accessToken = authenticate();
if($accessToken != null)
{
	try {
		$response = $client->request()->get('services/data/v45.0/sobjects/Account/describe', [
			RequestOptions::HEADERS => [
				'Authorization' => 'Bearer ' . $accessToken,
				'X-PrettyPrint' => 1,
			],
		]);
	} catch (\Exception $exception) {
		echo 'Error';
	}

	$accountObject = json_decode($response->getBody());	
	
	var_dump($accountObject);
}

