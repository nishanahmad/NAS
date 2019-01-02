<?php

date_default_timezone_set("Asia/Kolkata");		

$sqlDate = date("Y-m-d",strtotime('2019-01-03'));

$tomorrow = new DateTime('tomorrow');
$tomorrow = $tomorrow ->format('Y-m-d');

if (time() > strtotime("4:48 PM")) 
{
	echo 'Hell Yeah';
}
//if($sqlDate == $tomorrow)
	//echo 'Hell Yeah';