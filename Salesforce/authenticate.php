<?php


	require '../vendor/autoload.php';

	use GuzzleHttp\{Client, RequestOptions};

	$apiCredentials = [
		'client_id' => '3MVG9ZUGg10Hh224QOd_JX_DtrJqCjKx3JLYa9tcgDooAD184h4ed9SlnbPcR5FUBiwgW5yn1Lvizq4Aethjk',
		'client_secret' => 'CDC4DF0785D845DA5C4B3ED27CBEFD3802E51F0EF29DDFFFE4A18DF2AAB66911',
		'security_token' => 'n47wgO4I6dKfDQFG0tUuxgDp',
	];
	$userCredentials = [
		'username' => 'user@nas.com.uat',
		'password' => 'upn86862',
	];

	$client = new Client(['base_uri' => 'https://upn--uat.my.salesforce.com/','verify' => false]);
	try {
		$response = $client->post('services/oauth2/token', [
			RequestOptions::FORM_PARAMS => [
				'grant_type' => 'password',
				'client_id' => $apiCredentials['client_id'],
				'client_secret' => $apiCredentials['client_secret'],
				'username' => $userCredentials['username'],
				'password' => $userCredentials['password'] . $apiCredentials['security_token'],
			]
		]);

		$data = json_decode($response->getBody());
		
		$hash = hash_hmac(
			'sha256', 
			$data->id . $data->issued_at, 
			$apiCredentials['client_secret'], 
			true
		);
		if (base64_encode($hash) !== $data->signature) {
			throw new \SalesforceException('Access token is invalid');
		}
		$accessToken = $data->access_token; // Valid access token
		
		
		$data = [	
					'Date__c' => '2020-11-15',
					'Mason__c' => 'a066F00001MhyPe',
					'Customer__c' => 'Nishan',
					'Level__c' => 'BELT',
					'Total_Bags__c' => 100,
					'Brand__c' => 'ACC'
				];
		
		try {
			$response = $client->post('services/data/v45.0/sobjects/Bag_Report__c/', [
				RequestOptions::HEADERS => [
					'Authorization' => 'Bearer ' . $accessToken,
					'X-PrettyPrint' => 1,
				],
				RequestOptions::JSON => $data,
			]);
			$newRecord = json_decode($response->getBody());
		} catch (\Exception $exception) {
			echo 'Error';
		}

		var_dump($newRecord);
	} 
	catch (\Exception $exception) 
	{
		echo 'Error';
	}