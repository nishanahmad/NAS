<?php
require '../vendor/autoload.php';

function sendMessage($message,$phone)
{
	$uri =  "https://api.wa.anant.io/v1/send";

	$instanceId = '458f01be-f075-4745-8639-f6632c7a3697';
	$authToken = 'c4b4997fbff09e74e53f90bb0c2b55b3';

	$response = \Httpful\Request::post($uri)
                    ->body([
                        'instanceId' => $instanceId,
                        'authToken' => $authToken,
						'to' => $phone,
						'channel' => 'whatsapp',
						'messageType' => 'TEXT',
						'message' => $message
                            ], \Httpful\Mime::FORM)
                    ->send();
					
	return $response->body;					
	/*
	$uri = "http://api.tally.messaging.bizbrain.in/api/v2/sendWAMessage?token=d25df4daa30fdd63fb08119080c643be&to=";
	$uri = $uri.$phone."&type=text&text=".urlencode($text);
	$response = \Httpful\Request::get($uri)->send();

	return $response->body->flag;
	*/
}