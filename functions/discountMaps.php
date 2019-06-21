<?php

function getRateMap()
{
	require '../connect.php';	
	
	$rateMap = array();

	$rates = mysqli_query($con, "SELECT * FROM rate ORDER BY date") or die(mysqli_error($con));				 	 
	foreach($rates as $rate)
	{
		$rateMap[$rate['product']][$rate['date']] = $rate['rate'];
	}
	
	foreach($rateMap as $product => $array)
	{
		foreach($array as $date => $amount)
		{
			$current = date('Y-m-d', strtotime($date));
			$today = date('Y-m-d');

			while($current < $today)
			{
				$next = date('Y-m-d', strtotime($current. ' + 1 days'));
				if(!array_key_exists($next,$array))
				{
					$rateMap[$product][$next] = $rateMap[$product][$current];
				}
				$current = date('Y-m-d', strtotime($current. ' + 1 days'));
			}
		}
	}
	
	return $rateMap;	
}

function getWDMap()
{
	require '../connect.php';	
	
	$wdMap = array();

	$discounts = mysqli_query($con, "SELECT * FROM discounts WHERE type = 'wd' ORDER BY date") or die(mysqli_error($con));				 	 
	foreach($discounts as $discount)
	{
		$wdMap[$discount['product']][$discount['date']] = $discount['amount'];
	}
	
	return $wdMap;	
}

function getSDMap()
{
	require '../connect.php';	
	
	$sdMap = array();

	$discounts = mysqli_query($con, "SELECT * FROM discounts WHERE type = 'sd' ORDER BY date") or die(mysqli_error($con));				 	 
	foreach($discounts as $discount)
	{
		$sdMap[$discount['product']][$discount['client']][$discount['date']] = $discount['amount'];
	}

	foreach($sdMap as $product => $array1)
	{
		foreach($array1 as $client => $array2)
		{
			$current = date('Y-m-d', strtotime(array_key_first($array2)));
			$today = date('Y-m-d');

			while($current < $today)
			{
				$next = date('Y-m-d', strtotime($current. ' + 1 days'));
				if(!array_key_exists($next,$array2))
				{
					$sdMap[$product][$client][$next] = $sdMap[$product][$client][$current];
				}
				$current = date('Y-m-d', strtotime($current. ' + 1 days'));
			}
		}
	}
	
	return $sdMap;	
}

function getCDMap()
{
	require '../connect.php';	
	
	$cdMap = array();

	$discounts = mysqli_query($con, "SELECT * FROM discounts WHERE type = 'cd' ORDER BY date") or die(mysqli_error($con));				 	 
	foreach($discounts as $discount)
	{
		$cdMap[$discount['product']][$discount['client']][$discount['date']] = $discount['amount'];
	}

	foreach($cdMap as $product => $array1)
	{
		foreach($array1 as $client => $array2)
		{
			$current = date('Y-m-d', strtotime(array_key_first($array2)));
			$today = date('Y-m-d');

			while($current < $today)
			{
				$next = date('Y-m-d', strtotime($current. ' + 1 days'));
				if(!array_key_exists($next,$array2))
				{
					$cdMap[$product][$client][$next] = $cdMap[$product][$client][$current];
				}
				$current = date('Y-m-d', strtotime($current. ' + 1 days'));
			}
		}
	}
	
	return $cdMap;	
}

function array_key_first(array $arr) 
{
	foreach($arr as $key => $unused) 
	{
		return $key;
	}
	return NULL;
}

function array_key_last($array) 
{
	if (!is_array($array) || empty($array)) 
	{
		return NULL;
	}
	
	return array_keys($array)[count($array)-1];
}
?>