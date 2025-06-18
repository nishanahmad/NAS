<?php
function getLatestMonth($con)
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
?>