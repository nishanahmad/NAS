<?php
	require '../../connect.php';
	
	if(!empty($_POST['id']))
	{
		$id = $_POST['id'];
		$query = mysqli_query($con,"SELECT name FROM ar_details WHERE id = '$id' ") or die(mysqli_error($con));
		if(mysqli_num_rows($query)>0)
		{
			$ar = mysqli_fetch_array($query,MYSQLI_ASSOC) or die(mysqli_error($con));				 	 
			echo $ar['name'];
		}
		else
		{
			echo null;			
		}
	}
