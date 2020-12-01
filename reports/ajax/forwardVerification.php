<?php
	require '../../connect.php';
	session_start();
	if(!empty($_POST['forwardId']))
	{		
		$saleId = $_POST['forwardId'];
		$remarks = $_POST['remarks'];
		$forwarded_by = $_SESSION['user_id'];
		$forwarded_on = date('Y-m-d H:i:s');		
		
		$insert ="INSERT INTO tally_check_forwards (sale, remarks, forwarded_by, forwarded_on)
				  VALUES
				 ('$saleId', '$remarks', '$forwarded_by', '$forwarded_on')";			
		$result = mysqli_query($con, $insert);				 			 

		if($result)
			echo $saleId;
		else
			echo false;	
	}
