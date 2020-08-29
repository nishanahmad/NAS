<?php

function getLoadings($con)
{	
	$rates = mysqli_query($con,"SELECT * FROM rate ORDER BY date ASC") or die(mysqli_error($con));
	foreach($rates as $rate)
	{
		$rateMap[$rate['product']][] = array($rate['date'] => $rate['rate']);
	}
	
	foreach($rateMap as $product => $subMap)
	{
		for($i=0; $i<sizeof($subMap); $i++)
		{
		
		}
	}
	
	foreach($mainMap as $product => $subMap)
		$mainMap[$product] = array_reverse($subMap);
		
	return $mainMap;
}


function getProductNames($con)
{
	$productNameMap = array();
	$products = mysqli_query($con,"SELECT id,name FROM products ORDER BY name ASC");
	foreach($products as $product)
		$productNameMap[$product['id']] = $product['name'];	
		
	return $productNameMap;	
}