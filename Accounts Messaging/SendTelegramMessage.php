<?php
require '../vendor/autoload.php';

function sendTelegramMessage($message,$bot_chatID)
{
	$bot_token = '6810686140:AAHqEQImO03bocclIzYwpv0Qwl1q7oT6bn0';
	$uri =  'https://api.telegram.org/bot'.$bot_token.'/sendMessage';
	
	$response = \Httpful\Request::post($uri)
                    ->body([
                        'chat_id' => $bot_chatID,
                        'parse_mode' => 'Markdown',
						'text' => $message
                            ], \Httpful\Mime::FORM)
                    ->send();
					
	return $response->body;					
}