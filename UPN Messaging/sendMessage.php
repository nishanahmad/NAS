<?php
require '../vendor/autoload.php';

function sendMessage($message,$phone)
{
	$uri =  "https://api.wa.anant.io/v1/send";
	
	$instanceId = 'e0f41d66-9142-49c7-ad9e-19bbae820fc5';
	$authToken = 'd81929dc-7d33-a8c8-2a2b-8cd78e5fd0df';

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