<?php
	
session_start();	

if(isset($_SESSION["user_name"]))
{
	require '../connect.php';	
	require 'navbar.php';	
	
	$urlDate = date('d-m-Y',strtotime($_GET['date']));	
	$date = date('Y-m-d',strtotime($urlDate));	
	
	$mainMap = array();
	$drivers = mysqli_query($con,"SELECT * FROM users WHERE role ='driver' AND active = 1 AND user_name != 'Damage' ORDER BY user_name ASC" ) or die(mysqli_error($con));
	foreach($drivers as $driver)
	{
		$driverId = $driver['user_id'];
		
		$areaList = array();
		$areaQuery = mysqli_query($con,"SELECT * FROM sheet_area WHERE driver = $driverId" ) or die(mysqli_error($con));
		foreach($areaQuery as $area)
			$areaList[$area['id']] = $area['name'];
				
		$areaList = implode("','",array_keys($areaList));
		
		$agr1 = mysqli_query($con,"SELECT count(id) FROM sheets WHERE delivered_on ='$date' AND delivered_by = $driverId" ) or die(mysqli_error($con));
		$delivered = (int)mysqli_fetch_Array($agr1,MYSQLI_ASSOC)['count(id)'];

		$agr2 = mysqli_query($con,"SELECT count(id) FROM sheets WHERE closed_on ='$date' AND closed_by = $driverId" ) or die(mysqli_error($con));
		$taken = (int)mysqli_fetch_Array($agr2,MYSQLI_ASSOC)['count(id)'];		
		
		$agr3 = mysqli_query($con,"SELECT count(id) FROM sheets WHERE date ='$date' AND driver_area IN ('$areaList') AND status ='requested'" ) or die(mysqli_error($con));
		$pending = (int)mysqli_fetch_Array($agr3,MYSQLI_ASSOC)['count(id)'];		
		
		$mainMap[$driver['user_name']]['delivered'] = $delivered;
		$mainMap[$driver['user_name']]['taken'] = $taken;
		$mainMap[$driver['user_name']]['pending'] = $pending;
	}
?>	
<html>
	<head>
		<title>Day Wise Details</title>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
	</head>
	<body>
		<br/><br/>
		<div align="center">
			<h2><i class="fa fa-calendar"></i> Day Wise Details</h2><br/>
			<input type="text" name="date" id="date" onchange="document.location.href = 'dayBook.php?date=' + this.value" class="form-control datepicker col-md-1" autocomplete="off" value="<?php echo date('d-m-Y',strtotime($urlDate)); ?>">
			<br/><br/>
			<table class="table table-hover table-bordered" style="width:30%;">
				<thead>
					<tr class="table-success">
						<th style="text-align:left;">Driver</th>
						<th>Delivered</th>
						<th>Taken</th>
						<th>Pending</th>
					</tr>
				</thead>
				<tbody>																																			<?php
				foreach($mainMap as $driver => $status)
				{																																				?>
					<tr align="center">
						<td style="text-align:left;"><?php echo $driver;?></b></td>
						<td><?php echo $status['delivered'];?></td>
						<td><?php echo $status['taken'];?></td>
						<td><?php echo $status['pending'];?></td>
					</tr>																																		<?php
				}																																				?>
				</tbody>	
			</table>
			<br/><br/>
		</div>	 			
		<script>

			$(function(){

				var menu = $('.menu-navigation-dark');

				menu.slicknav();

				// Mark the clicked item as selected

				menu.on('click', 'a', function(){
					var a = $(this);

					a.siblings().removeClass('selected');
					a.addClass('selected');
				});
				
				var pickeropts = { dateFormat:"dd-mm-yy"}; 
				$( ".datepicker" ).datepicker(pickeropts);					
			});

		</script>				
	</body>
</html>																								<?php
}
else
	header("Location:../index.php");
