<?php
	require '../connect.php';
	session_start();
	if(!empty($_POST['sheetId']))
	{		
		$sheetId = $_POST['sheetId'];
		$read_on = date('Y-m-d H:i:s');			
		
		$result = mysqli_query($con, "UPDATE sheets SET driver_read = 1, read_on = '$read_on' WHERE id = $sheetId");				 			 			
		
		if($result)
		{
			echo $sheetId;
		}
		else
		{
			echo false;
		}
	}
