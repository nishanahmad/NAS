<?php
function getLatestMonthUltra($con)
{
	$latestYear = null;
	$latestMonth = null;

	$targetObjects = mysqli_query($con, "SELECT MAX(year) FROM target_ultra") or die(mysqli_error($con));
	foreach($targetObjects as $target)
		$latestYear = (int)$target['MAX(year)'];

	$targetObjects = mysqli_query($con, "SELECT MAX(month) FROM target_ultra WHERE year = $latestYear") or die(mysqli_error($con));
	foreach($targetObjects as $target)
		$latestMonth = (int)$target['MAX(month)'];
	
	return $latestMonth;
}

function getLatestMonthAcc($con)
{
	$latestYear = null;
	$latestMonth = null;

	$targetObjects = mysqli_query($con, "SELECT MAX(year) FROM target") or die(mysqli_error($con));
	foreach($targetObjects as $target)
		$latestYear = (int)$target['MAX(year)'];

	$targetObjects = mysqli_query($con, "SELECT MAX(month) FROM target WHERE year = $latestYear") or die(mysqli_error($con));
	foreach($targetObjects as $target)
		$latestMonth = (int)$target['MAX(month)'];
	
	return $latestMonth;
}
?>