<?php
	require '../connect.php';
	session_start();
	if(!empty($_POST['saleId']))
	{		
		$saleId = $_POST['saleId'];
		$checked = $_POST['checked'];			
		
		if($checked == 'True')
			$update ="UPDATE nas_sale SET coveringblock=1 WHERE sales_id = '$saleId'";
		else	
			$update ="UPDATE nas_sale SET coveringblock=0 WHERE sales_id = '$saleId'";
		
		$result = mysqli_query($con, $update);				 			 			
		
		if($result)
			echo true;
		else
			echo false;
	}
