<?php
function getPointPercentage($arId,$actual_perc,$year,$month,$con)
{
	$point_perc = 0;
	if($year < 2017 || ($year == 2017 && $month <= 9))
	{
		if($actual_perc < 30)			
			$point_perc = 0;
		else if($actual_perc <= 40)		
			$point_perc = 20;
		else if($actual_perc <= 59)		
			$point_perc = 30;
		else if($actual_perc <= 69)		
			$point_perc = 40;
		else if($actual_perc <= 79)		
			$point_perc = 60;
		else if($actual_perc <= 89)		
			$point_perc = 80;
		else if($actual_perc <= 95)		
			$point_perc = 90;
		else if($actual_perc >= 96)		
			$point_perc = 100;										
	}
	else if( ($year == 2020 && $month <= 9) || $year < 2020)
	{
		if($actual_perc <= 70)			
			$point_perc = 0;
		else if($actual_perc <= 80)		
			$point_perc = 50;
		else if($actual_perc <= 95)		
			$point_perc = 70;
		else if($actual_perc >= 96)		
			$point_perc = 100;										
	}
	else
	{
		if($actual_perc < 50)			
			$point_perc = 0;
		else if($actual_perc <= 59)		
			$point_perc = 50;
		else if($actual_perc <= 79)		
			$point_perc = 70;
		else if($actual_perc >= 80)		
			$point_perc = 100;												
	}

	$custom = mysqli_query($con,"SELECT * FROM custom_point_perc WHERE ar = $arId AND year = $year AND month = $month") or die(mysqli_error($con));
	if(mysqli_num_rows($custom) > 0 )
		$point_perc = mysqli_fetch_array($custom, MYSQLI_ASSOC)['percentage'];
	
	return $point_perc;
}
?>