<?php
	
session_start();	

function dateDiff($date1, $date2)
{
    $date1_ts = strtotime($date1);
    $date2_ts = strtotime($date2);
    $diff = $date2_ts - $date1_ts;
    return round($diff / 86400);
}

if(isset($_SESSION["user_name"]))
{
	require '../connect.php';	
	require 'navbar.php';	
	
	if(isset($_POST['fromDate']))
		$fromDate = date('Y-m-d',strtotime($_POST['fromDate']));
	else
		$fromDate = date('Y-m-d');	
		
	if(isset($_POST['toDate']))
		$toDate = date('Y-m-d',strtotime($_POST['toDate']));	
	else
		$toDate = date('Y-m-d');		
	
	$no_days = dateDiff($fromDate,$toDate) + 1;
	
	$mainMap = array();
	$deliveryMap = array();
	$pendingMap = array();
	$takenMap = array();
	
	$areaList = mysqli_query($con,"SELECT id,name FROM sheet_area ORDER BY name ASC");	
	foreach($areaList as $area)
		$areaMap[$area['id']] = $area['name'];

	$drivers = mysqli_query($con,"SELECT * FROM users WHERE role = 'driver' ORDER BY user_name ASC" ) or die(mysqli_error($con));
	foreach($drivers as $driver)
		$driverMap[$driver['user_id']] = $driver['user_name'];		
	
	$agr1 = mysqli_query($con,"SELECT count(id),delivered_by FROM sheets WHERE date >= '$fromDate' AND date <= '$toDate' GROUP BY delivered_by" ) or die(mysqli_error($con));
	foreach($agr1 as $delv)
		$mainMap[$delv['delivered_by']]['delivered'] = $delv['count(id)'];
		
	$agr2 = mysqli_query($con,"SELECT count(id),closed_by FROM sheets WHERE closed_on >= '$fromDate' AND closed_on <= '$toDate' GROUP BY closed_by" ) or die(mysqli_error($con));
	foreach($agr2 as $closed)
		$mainMap[$closed['closed_by']]['taken'] = $closed['count(id)'];
		
?>	
<html>
	<head>
		<title>Month Report</title>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
	</head>
	<body>
		<br/><br/>
		<div style="margin-left:46%;">
			<select id="selectbox" name="" onchange="javascript:location.href = this.value;">
				<option value="daybook.php?date=<?php echo date('Y-m-d');?>">Day Book</option>
				<option value="monthReport.php" selected>Month Report</option>
			</select>
		</div>	
		<br/><br/>
		<div align="center">
					<form method="post" action="" autocomplete="off">
						<div class="row" style="margin-left:35%">
							<div style="width:220px;">
								<div class="input-group">
									<span class="input-group-text col-md-5"><i class="far fa-calendar-alt"></i>&nbsp;From</span>
									<input type="text" required name="fromDate" id="fromDate" class="form-control datepicker" autocomplete="off" value="<?php echo date('d-m-Y',strtotime($fromDate)); ?>">
								</div>
							</div>
							&nbsp;&nbsp;&nbsp;&nbsp;
							<div style="width:220px;">
								<div class="input-group">
									<span class="input-group-text col-md-5"><i class="far fa-calendar-alt"></i>&nbsp;To</span>
									<input type="text" required name="toDate" id="toDate" class="form-control datepicker" value="<?php echo date('d-m-Y',strtotime($toDate)); ?>">
								</div>
							</div>
						</div>
						<br/>
						<div class="justify-content-center">
							<input type="submit" class="btn" style="background-color:#54698D;color:white;" value="Search">		
						</div>			
					</form>																													
			<br/><br/>
			<table class="table table-hover table-bordered" style="width:30%;">
				<thead>
					<tr class="table-info">
						<th style="text-align:left;">Driver</th>
						<th>Total</th>
						<th>Delivered</th>
						<th>Taken</th>
						<th>Avg</th>
						
					</tr>
				</thead>
				<tbody>																																			<?php
				foreach($mainMap as $driver => $status)
				{
					if(isset($driverMap[$driver]))
					{
						if($driver != '31' && $driver != '38')
						{																																			?>
							<tr align="center">
								<td style="text-align:left;"><?php echo $driverMap[$driver];?></b></td>
								<td><b><?php echo $status['delivered'] + $status['taken'];?></b></td>
								<td><?php echo $status['delivered'];?></td>
								<td><?php echo $status['taken'];?></td>
								<td><b><?php echo round(($status['delivered'] + $status['taken'])/$no_days,1);?></b></td>
							</tr>																																	<?php							
						}																																			?>																																								<?php						
					}
				}																																				?>	
				</tbody>	
			</table>
		</div>	 			
		<script>

			$(function(){

				var menu = $('.menu-navigation-dark');

				menu.slicknav();
				
				var pickeropts = { dateFormat:"dd-mm-yy"}; 
				$( ".datepicker" ).datepicker(pickeropts);					
			});
		</script>				
	</body>
</html>																								<?php
}
else
	header("Location:../index.php");
