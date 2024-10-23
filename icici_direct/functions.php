<?php
require '../vendor/autoload.php';
date_default_timezone_set("Asia/Kolkata");

function fetchData($instrument_details)
{
	$uri =  "http://localhost:5000/get_quote";

	$data =
		array(
			'stock_code' => $instrument_details['stock_code'],
			'exchange_code' => $instrument_details['exchange_code'],
			'expiry_date' => $instrument_details['expiry_date'],
			'product_type' => $instrument_details['product_type'],
			'right' => $instrument_details['right'],
			'strike_price' => $instrument_details['strike_price']
		);
	$req = \Httpful\Request::post($uri);
	
	$response = $req
		->body(json_encode($data))
		->sendsJson()
		->send();
	
	return $response->body;
}



function fetchDataIronFly($instrument_details)
{
	$uri =  "http://localhost:5000/get_quote";

	$data =
		array(
			'exchange_code' => $instrument_details['exchange_code'],
			'expiry_date' => $instrument_details['expiry_date'],

			'ce_main_stock_code' => $instrument_details['ce_main_stock_code'],			
			'ce_main_strike' => $instrument_details['ce_main_strike'],
			'ce_hedge_stock_code' => $instrument_details['ce_hedge_stock_code'],			
			'ce_hedge_strike' => $instrument_details['ce_hedge_strike'],			
			'pe_main_stock_code' => $instrument_details['pe_main_stock_code'],			
			'pe_main_strike' => $instrument_details['pe_main_strike'],	
			'pe_hedge_stock_code' => $instrument_details['pe_hedge_stock_code'],			
			'pe_hedge_strike' => $instrument_details['pe_hedge_strike']
			
		);
	$req = \Httpful\Request::post($uri);
	
	$response = $req
		->body(json_encode($data))
		->sendsJson()
		->send();
	
	return $response->body;
}



function calculateMargin($iron_fly)
{
	$uri =  "http://localhost:5001/calculate_margin";

	$data = $iron_fly;
	$req = \Httpful\Request::post($uri);
	
	$response = $req
		->body(json_encode($data))
		->sendsJson()
		->send();
	
	return $response->body;
}



function placeCeLeg($order)
{
	$uri =  "http://localhost:5002/place_ce_leg";

	$data = $order;
	$req = \Httpful\Request::post($uri);
	
	$response = $req
		->body(json_encode($data))
		->sendsJson()
		->send();
	
	return $response->body;
}


function placePeLeg($order)
{
	$uri =  "http://localhost:5003/place_pe_leg";

	$data = $order;
	$req = \Httpful\Request::post($uri);
	
	$response = $req
		->body(json_encode($data))
		->sendsJson()
		->send();
	
	return $response->body;
}



function getTodayExpiringIndex()
{
	$dayofweek = (int)date('w');
	if($dayofweek == 1)
		return 'NIFSEL';
	else if($dayofweek == 2)
		return 'NIFFIN';
	else if($dayofweek == 3)
		return 'CNXBAN';
	else if($dayofweek == 4)
		return 'NIFTY';
	else if($dayofweek == 5)
		return 'BSESEN';
}



function getIndexDetails($index)
{
	$returnMap = array();

	if($index == 'NIFSEL')
	{
		$returnMap['stock_code'] = 'NIFSEL';
		$returnMap['index_exchange_code'] = 'NSE';
		$returnMap['fo_exchange_code'] = 'NFO';
		$returnMap['tick_size'] = 25;
		$returnMap['lot_size'] = 50;
		$returnMap['hedge_steps'] = 4;
	}
	else if($index == 'NIFFIN')
	{
		$returnMap['stock_code'] = 'NIFFIN';
		$returnMap['index_exchange_code'] = 'NSE';
		$returnMap['fo_exchange_code'] = 'NFO';
		$returnMap['tick_size'] = 50;
		$returnMap['lot_size'] = 25;
		$returnMap['hedge_steps'] = 4;
	}
	else if($index == 'CNXBAN')
	{
		$returnMap['stock_code'] = 'CNXBAN';
		$returnMap['index_exchange_code'] = 'NSE';
		$returnMap['fo_exchange_code'] = 'NFO';
		$returnMap['tick_size'] = 100;		
		$returnMap['lot_size'] = 15;	
		$returnMap['hedge_steps'] = 5;		
	}
	else if($index == 'NIFTY')
	{
		$returnMap['stock_code'] = 'NIFTY';
		$returnMap['index_exchange_code'] = 'NSE';		
		$returnMap['fo_exchange_code'] = 'NFO';
		$returnMap['tick_size'] = 50;
		$returnMap['lot_size'] = 25;
		$returnMap['hedge_steps'] = 4;
	}
	else if($index == 'BSESEN')
	{
		$returnMap['stock_code'] = 'BSESEN';
		$returnMap['index_exchange_code'] = 'BSE';		
		$returnMap['fo_exchange_code'] = 'BFO';
		$returnMap['tick_size'] = 100;
		$returnMap['lot_size'] = 10;
		$returnMap['hedge_steps'] = 4;
	}
	
	return $returnMap;
}



function getStrike($currentValue,$tick_size) 
{
	return round($currentValue/$tick_size)*$tick_size;
}


function getIndexName($code) 
{
	if($code == 'NIFSEL')
		$name = 'MIDCAP NIFTY';
	else if($code == 'NIFFIN')
		$name = 'NIFTY FINANCE';
	else if($code == 'CNXBAN')
		$name = 'BANK NIFTY';
	else if($code == 'NIFTY')
		$name = 'NIFTY';
	else if($code == 'BSESEN')
		$name = 'SENSEX';
	
    return $name;
}



function parseResult($result)
{
	foreach($result as $key => $value)
	{
		foreach($value as $index => $detail)
		{
			if($detail-> ltp > 0)
				$ltp = $detail-> ltp;
		}
	}
	$returnMap['ltp'] = $ltp;
		
	return $returnMap;
}


function generateIronFly($todayMap,$strike)
{
	$iron_fly['exchange_code'] = $todayMap['fo_exchange_code'];
	$iron_fly['expiry_date'] = $todayMap['expiry_date'];

    $iron_fly['ce_main_stock_code'] = $todayMap['stock_code'];	
	$iron_fly['ce_main_strike'] = $strike;
	$iron_fly['ce_hedge_stock_code'] = $todayMap['stock_code'];
	$iron_fly['ce_hedge_strike'] = $strike + $todayMap['tick_size'] * $todayMap['hedge_steps'];
	$iron_fly['pe_main_stock_code'] = $todayMap['stock_code'];
	$iron_fly['pe_main_strike'] = $strike;
	$iron_fly['pe_hedge_stock_code'] = $todayMap['stock_code'];
	$iron_fly['pe_hedge_strike'] = $strike - $todayMap['tick_size'] * $todayMap['hedge_steps'];

	return $iron_fly;	
}



function parseResultIronFly($result)
{
	$returnMap = array();
	foreach($result as $key => $value)
	{
		if($value -> leg == 'ce_main')
			$returnMap['ce_main'] = $value -> ltp;
		else if($value -> leg == 'ce_hedge')
			$returnMap['ce_hedge'] = $value -> ltp;
		else if($value -> leg == 'pe_main')
			$returnMap['pe_main'] = $value -> ltp;		
		else if($value -> leg == 'pe_hedge')
			$returnMap['pe_hedge'] = $value -> ltp;		
	}
	
	return $returnMap;
}


function generateIronFlyMarginParams($todayMap,$expiry_date,$strike)
{
	$expiryDate = str_replace("T06:00:00.000Z", "",$expiry_date);
	$expiryDate = date("d-M-Y",strtotime($expiryDate));
	
    $ce_main["strike_price"] = $strike;
    $ce_main["quantity"] = $todayMap['lot_size'];
    $ce_main["right"] = "call";
    $ce_main["product"] = "options";
    $ce_main["action"] = "sell";
    $ce_main["price"] = "0";
    $ce_main["expiry_date"] = $expiryDate;
    $ce_main["stock_code"] = $todayMap['stock_code'];
    $ce_main["cover_order_flow"] = "N";
    $ce_main["fresh_order_type"] = "N";
    $ce_main["cover_limit_rate"] = "0";
    $ce_main["cover_sltp_price"] = "0";
    $ce_main["fresh_limit_rate"] = "0";
    $ce_main["open_quantity"] = "0";
	
    $ce_hedge["strike_price"] = $strike + $todayMap['tick_size'] * $todayMap['hedge_steps'];
    $ce_hedge["quantity"] = $todayMap['lot_size'];
    $ce_hedge["right"] = "call";
    $ce_hedge["product"] = "options";
    $ce_hedge["action"] = "buy";
    $ce_hedge["price"] = "0";
    $ce_hedge["expiry_date"] = $expiryDate;
    $ce_hedge["stock_code"] = $todayMap['stock_code'];
    $ce_hedge["cover_order_flow"] = "N";
    $ce_hedge["fresh_order_type"] = "N";
    $ce_hedge["cover_limit_rate"] = "0";
    $ce_hedge["cover_sltp_price"] = "0";
    $ce_hedge["fresh_limit_rate"] = "0";
    $ce_hedge["open_quantity"] = "0";

    $pe_main["strike_price"] = $strike;
    $pe_main["quantity"] = $todayMap['lot_size'];
    $pe_main["right"] = "put";
    $pe_main["product"] = "options";
    $pe_main["action"] = "sell";
    $pe_main["price"] = "0";
    $pe_main["expiry_date"] = $expiryDate;
    $pe_main["stock_code"] = $todayMap['stock_code'];
    $pe_main["cover_order_flow"] = "N";
    $pe_main["fresh_order_type"] = "N";
    $pe_main["cover_limit_rate"] = "0";
    $pe_main["cover_sltp_price"] = "0";
    $pe_main["fresh_limit_rate"] = "0";
    $pe_main["open_quantity"] = "0";	
	
    $pe_hedge["strike_price"] = $strike + $todayMap['tick_size'] * $todayMap['hedge_steps'];
    $pe_hedge["quantity"] = $todayMap['lot_size'];
    $pe_hedge["right"] = "put";
    $pe_hedge["product"] = "options";
    $pe_hedge["action"] = "buy";
    $pe_hedge["price"] = "0";
    $pe_hedge["expiry_date"] = $expiryDate;
    $pe_hedge["stock_code"] = $todayMap['stock_code'];
    $pe_hedge["cover_order_flow"] = "N";
    $pe_hedge["fresh_order_type"] = "N";
    $pe_hedge["cover_limit_rate"] = "0";
    $pe_hedge["cover_sltp_price"] = "0";
    $pe_hedge["fresh_limit_rate"] = "0";
    $pe_hedge["open_quantity"] = "0";
	
	$iron_fly['array'] = array($ce_main,$ce_hedge,$pe_main,$pe_hedge);
	$iron_fly['exchange_code'] = $todayMap['fo_exchange_code'];
	
	return $iron_fly;	
}


function moneyFormatIndia($num){
    $explrestunits = "" ;
    if(strlen($num)>3) {
        $lastthree = substr($num, strlen($num)-3, strlen($num));
        $restunits = substr($num, 0, strlen($num)-3); // extracts the last three digits
        $restunits = (strlen($restunits)%2 == 1)?"0".$restunits:$restunits; // explodes the remaining digits in 2's formats, adds a zero in the beginning to maintain the 2's grouping.
        $expunit = str_split($restunits, 2);
        for($i=0; $i<sizeof($expunit); $i++) {
            // creates each of the 2's group and adds a comma to the end
            if($i==0) {
                $explrestunits .= (int)$expunit[$i].","; // if is first value , convert into integer
            } else {
                $explrestunits .= $expunit[$i].",";
            }
        }
        $thecash = $explrestunits.$lastthree;
    } else {
        $thecash = $num;
    }
    return $thecash; // writes the final format where $currency is the currency symbol.
}
?>