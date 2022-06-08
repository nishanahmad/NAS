<?php
	
session_start();

if(isset($_SESSION["user_name"]))
{	
	require '../connect.php';
	require 'navbar.php';
	
	$designation = $_SESSION['role'];
	
	if(isset($_GET['error']))
		$error = $_GET['error'];
	else
		$error = 'false';	
	
	$userId = $_SESSION['user_id'];

	$driversQuery = mysqli_query($con,"SELECT user_id,user_name FROM users WHERE role ='driver' ORDER BY user_name") or die(mysqli_error($con));
	foreach($driversQuery as $driver)
		$drivers[$driver['user_id']] = $driver['user_name'];
		
	$mainAreaQuery = mysqli_query($con,"SELECT id,name,driver FROM sheet_area ORDER BY name") or die(mysqli_error($con));
	foreach($mainAreaQuery as $mainArea)
	{
		$mainAreaMap[$mainArea['id']] = $mainArea['name'];
		$areaDriverMap[$mainArea['id']] = $mainArea['driver'];		
	}
		
	$sheets = mysqli_query($con,"SELECT * FROM sheets WHERE status ='delivered' ORDER BY date ASC" ) or die(mysqli_error($con));		 	 
?>	
<html>
	<head>
		<title>Delivered Sheets</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link href="../css/styles.css" rel="stylesheet" type="text/css">	
		<link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" rel="stylesheet" type="text/css">
		<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/jquery.tablesorter.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/jquery.tablesorter.widgets.min.js"></script>
	</head>
	<body>
		<div align="center">
			<br/><br/>
			<h2>Delivered Sheets</h2><br/>
		</div>
		<a href="deliveries.php" style="margin-left:46%">Main View</a><br/><br/>
		<table class="table table-hover table-bordered" style="margin-left:25px;width:98%">
			<thead>
				<tr class="table-info">
					<th>Address</th>
					<th style="width:12%">Date</th>
					<th style="width:2%">Qty</th>
					<th>Mason</th>
					<th>Customer</th>
					<th>Shop</th>
					<th>Delv By</th>
					<th style="width:12%">Delv On</th>
					<th>Remarks</th>
					<th>Assigned</th>
				</tr>
			</thead>
			<tbody>		<?php 
			foreach($sheets as $sheet)
			{																															?>
				<tr>
					<td><?php if($sheet['driver_area'] > 0) echo '<font style="font-weight:bold">'.$mainAreaMap[$sheet['driver_area']].'</font>';echo '<br/>'.$sheet['area']; ?></td>
					<td><?php echo date("d-m-Y",strtotime($sheet['date']));?></td>
					<td><?php echo $sheet['qty'];?></td>
					<td><?php echo $sheet['customer_name'];?></td>
					<td><?php echo $sheet['mason_name'];?></td>
					<td><?php echo $sheet['shop'];?></td>
					<td><?php echo $drivers[$sheet['delivered_by']];?></td>
					<td><?php echo date('d-m-Y h:i A',strtotime($sheet['delivered_on']));?></td>
					<td><?php echo $sheet['remarks'];?></td>
					<td><?php echo $drivers[$sheet['assigned_to']];?></td>				
				</tr>																												<?php				
			}																															?>
			<tbody>
		</table>		
		<script>
		$(function(){
			$("table").tablesorter({
				dateFormat : "ddmmyyyy",
				theme : 'bootstrap',
				widgets: ['filter'],
				filter_columnAnyMatch: true
			}); 								
		});
		</script>		
	</body>
</html>																				<?php
}
else
	header("Location:../index.php");
