<?php
	require '../../connect.php';
	
	if(!empty($_POST['truck']))
	{
		$truckNumber = $_POST['truck'];
		
		$query = mysqli_query($con,"SELECT phone FROM truck_details WHERE number = $truckNumber") or die(mysqli_error($con));
		if(mysqli_num_rows($query)>0)
		{
			$truck = mysqli_fetch_array($query,MYSQLI_ASSOC) or die(mysqli_error($con));				 	 
			echo $truck['phone'];
		}
		else
		{
			echo null;			
		}
	}
