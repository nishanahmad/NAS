<html>
<head>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<title>
Sheets
</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<script type="text/javascript" language="javascript" src="../js/jquery.js"></script>
<script>
function deliver(id){
  var qty = window.prompt("Enter number of sheets delivered to this site");		
  
	hrf = 'deliver.php?';
	window.location.href = hrf +"id="+ id + "&qty=" + qty;
}
</script>
																														<?php
	require '../connect.php';																															
	$sheets = mysqli_query($con,"SELECT * FROM sheet_requests WHERE status IS NULL ORDER BY date ASC" ) or die(mysqli_error($con));		 	 
	foreach($sheets as $sheet)
	{																													?>
		<div class="row">
		  <div class="column" style="background-color:#ddd;">
			<p><?php echo $sheet['area'] .', '.$sheet['location'].', '.$sheet['landmark'];?></p>		  
			<p><?php echo $sheet['masonName'] . ', ' .$sheet['masonPhone'];?>
			<p><?php echo $sheet['customerName'] . ', ' .$sheet['customerPhone'];?>
			<p><?php echo 'Qty:'.$sheet['qty'];?></p>
			<p><?php echo date("d-m-Y",strtotime($sheet['date']));?></p>
			<p><?php echo $sheet['fe'];?></p>
			<input type="button" value="Deliver" onclick="deliver(<?php echo $sheet['id'];?>)"/>			
		  </div>
		</div>																											<?php	
	}																													?>
</html>																														