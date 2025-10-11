<?php
function getLatestYearUltra($con)
{
	$latestYear = null;
	$targetObjects = mysqli_query($con, "SELECT MAX(year) FROM target_ultra") or die(mysqli_error($con));
	foreach($targetObjects as $target)
	{
		$latestYear = (int)$target['MAX(year)'];
	}	
	
	return $latestYear;
}

function getLatestYearAcc($con)
{
	$latestYear = null;
	$targetObjects = mysqli_query($con, "SELECT MAX(year) FROM target") or die(mysqli_error($con));
	foreach($targetObjects as $target)
	{
		$latestYear = (int)$target['MAX(year)'];
	}	
	
	return $latestYear;
}
?>