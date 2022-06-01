<?php
ini_set('max_execution_time', '0'); // for infinite time of execution 
ini_set('memory_limit', '-1');

function getClientNames($con)
{
	$clientMap = array();
	$clients = mysqli_query($con,"SELECT id,name FROM ar_details ORDER BY name ASC");	
	foreach($clients as $client)
		$clientMap[$client['id']] = $client['name'];	
		
	return $clientMap;	
}


function getGodownNames($con)
{
	$godownMap = array();
	$godowns = mysqli_query($con,"SELECT * FROM godowns ORDER BY name");
	foreach($godowns as $godown)
		$godownMap[$godown['id']] = $godown['name'];	
		
	return $godownMap;	
}


function getClientType($con)
{
	$clientMap = array();
	$clients = mysqli_query($con,"SELECT id,type FROM ar_details ORDER BY name ASC");	
	foreach($clients as $client)
		$clientMap[$client['id']] = $client['type'];
		
	return $clientMap;	
}


function getProductDetails($con)
{
	$productMap = array();
	$products = mysqli_query($con,"SELECT id,name,colorcode FROM products ORDER BY name ASC");
	foreach($products as $product)
	{
		$productMap[$product['id']]['name'] = $product['name'];	
		$productMap[$product['id']]['colorcode'] = $product['colorcode'];	
	}
		
		
	return $productMap;	
}


function getTruckNumbers($con)
{
	$truckNumbersMap = array();
	$trucks = mysqli_query($con,"SELECT id,number FROM truck_details");
	foreach($trucks as $truck)
		$truckNumbersMap[$truck['id']] = $truck['number'];	
		
	return $truckNumbersMap;	
}

function get_array_key_first(array $arr) 
{
	foreach($arr as $key => $unused) 
	{
		return $key;
	}
	return NULL;
}

function get_array_key_last($array) 
{
	if (!is_array($array) || empty($array)) 
	{
		return NULL;
	}
	
	return array_keys($array)[count($array)-1];
}