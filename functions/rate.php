<?php
function getRate($date,$product)
{
	require '../connect.php';
	
	$rateQuery = mysqli_query($con,"SELECT rate FROM rate WHERE date <= '$date' AND product = $product ORDER BY date DESC LIMIT 1") or die(mysqli_error($con));				 	 
	if(mysqli_num_rows($rateQuery)>0)
	{
		$rate = mysqli_fetch_array($rateQuery,MYSQLI_ASSOC) or die(mysqli_error($con));				 	 
		
		return $rate['rate'];
	}
	else
	{
		return null;			
	}
}

function getWD($date,$product)
{
	require '../connect.php';
	
	$wdQuery = mysqli_query($con,"SELECT discount FROM discounts WHERE date = '$date' AND product = $product AND type = 'Wagon Discount'") or die(mysqli_error($con));				 	 
	if(mysqli_num_rows($wdQuery)>0)
	{
		$wd = mysqli_fetch_array($wdQuery,MYSQLI_ASSOC) or die(mysqli_error($con));				 	 
		
		return $wd['discount'];
	}
	else
	{
		return null;			
	}
}

function getCD($date,$product,$client)
{
	require '../connect.php';
	
	$cdQuery = mysqli_query($con,"SELECT discount FROM discounts WHERE date <= '$date' AND product = $product AND client = $client AND type = 'cd' ORDER BY date DESC LIMIT 1") or die(mysqli_error($con));				 	 
	if(mysqli_num_rows($cdQuery)>0)
	{
		$cd = mysqli_fetch_array($cdQuery,MYSQLI_ASSOC) or die(mysqli_error($con));				 	 
		
		return $cd['discount'];
	}
	else
	{
		return null;			
	}
}
?>