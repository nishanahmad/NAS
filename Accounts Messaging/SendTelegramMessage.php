<?php
require '../vendor/autoload.php';

function sendTelegramMessage($message,$bot_chatID,$con)
{
	$credentails = mysqli_query($con,"SELECT * FROM telegram_credentials") or die(mysqli_error($con));
	$bot_token = mysqli_fetch_array($credentails, MYSQLI_ASSOC)['bot_token'];
	
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