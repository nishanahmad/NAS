<?php
	require '../connect.php';
	
	if(!empty($_POST['date'])) 
	{
		$date = date('Y-m-d',strtotime($_POST['date']));
		$product = (int)$_POST['product'];
		
		$discountQuery = mysqli_query($con,"SELECT amount FROM discounts WHERE date = '$date' AND product = $product AND type = 'wd' ") or die(mysqli_error($con));				 	 			
		if(mysqli_num_rows($discountQuery)>0)
		{
			$discount = mysqli_fetch_array($discountQuery,MYSQLI_ASSOC) or die(mysqli_error($con));	
			echo $discount['amount'];
		}
		else
		{
			echo null;
		}	
	}
