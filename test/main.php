<?php
require '../connect.php';
$prevTotal = -300;
$newTotal =  500;

$acheivedGold = 0;
$totalGold = 0;


if($prevTotal > 0 && $newTotal > 0)
{
	if(floor($prevTotal/600) < floor($newTotal/600))
	{
		$acheivedGold = floor($newTotal/600) - floor($prevTotal/600);
		$totalGold = floor($newTotal/600);
	}		
}
else if($prevTotal < 0 && $newTotal > 0)
{
	if(floor($newTotal/600) > 0)
	{
		$acheivedGold = floor($newTotal/600);
		$totalGold = floor($newTotal/600);
	}				
}

//echo $acheivedGold;
//echo '<br/>';
//echo $totalGold;

$sales = mysqli_query($con,"SELECT bill_no FROM nas_sale WHERE bill_no NOT LIKE 'a%' AND bill_no NOT LIKE 'A%' AND bill_no <> '' ORDER BY bill_no LIMIT 10") or die(mysqli_error($con));		 
foreach($sales as $sale)
{
	var_dump($sale);
}