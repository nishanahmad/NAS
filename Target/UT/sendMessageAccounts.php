<?php
require '../vendor/autoload.php';

function sendMessage($message,$phone)
{
	$uri =  "https://api.wa.anant.io/v1/send";
	
	// NAS Marketing
	$instanceId = '458f01be-f075-4745-8639-f6632c7a3697';
	$authToken = '3a9344a4-600e-47b2-ab8a-4c42e64adda7';
	
	// Hitech
	//$instanceId = 'e0f41d66-9142-49c7-ad9e-19bbae820fc5';
	//$authToken = 'd81929dc-7d33-a8c8-2a2b-8cd78e5fd0df';

	$response = \Httpful\Request::post($uri)
                    ->body([
                        'instanceId' => $instanceId,
                        'authToken' => $authToken,
						'to' => $phone,
						'channel' => 'whatsapp',
						'messageType' => 'TEXT',
						'message' => $message,
						'safeDelivery' => true
                            ], \Httpful\Mime::FORM)
                    ->send();
					
	return $response->body;					
}