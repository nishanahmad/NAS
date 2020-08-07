<?php

	preg_match_all('!\d+!', '1458/Vp', $truckArray);
	$truckNumber = $truckArray[0][0];
	var_dump($truckNumber);