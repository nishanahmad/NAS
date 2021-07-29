<?php
	
session_start();	

if(isset($_SESSION["user_name"]))
{
	require '../connect.php';	
	require 'navbar.php';	
	
	$urlDate = date('d-m-Y',strtotime($_GET['date']));	
	$date = date('Y-m-d',strtotime($urlDate));	
	
	$mainMap = array();
	$deliveryMap = array();
	$pendingMap = array();
	$takenMap = array();
	
	$areaList = mysqli_query($con,"SELECT id,name FROM sheet_area ORDER BY name ASC");	
	foreach($areaList as $area)
		$areaMap[$area['id']] = $area['name'];
		
	$drivers = mysqli_query($con,"SELECT * FROM users WHERE role ='driver' AND active = 1 AND user_name != 'Damage' ORDER BY user_name ASC" ) or die(mysqli_error($con));
	foreach($drivers as $driver)
	{
		$driverMap[$driver['user_id']] = $driver['user_name'];
		
		$driverId = $driver['user_id'];
		
		$agr1 = mysqli_query($con,"SELECT count(id) FROM sheets WHERE date ='$date' AND delivered_by = $driverId" ) or die(mysqli_error($con));
		$delivered = (int)mysqli_fetch_Array($agr1,MYSQLI_ASSOC)['count(id)'];

		$agr2 = mysqli_query($con,"SELECT count(id) FROM sheets WHERE date ='$date' AND assigned_to = $driverId AND status ='requested'" ) or die(mysqli_error($con));
		$pending = (int)mysqli_fetch_Array($agr2,MYSQLI_ASSOC)['count(id)'];		
		
		$agr3 = mysqli_query($con,"SELECT count(id) FROM sheets WHERE closed_on ='$date' AND closed_by = $driverId" ) or die(mysqli_error($con));
		$taken = (int)mysqli_fetch_Array($agr3,MYSQLI_ASSOC)['count(id)'];		
		

		$mainMap[$driverId]['delivered'] = $delivered;
		$mainMap[$driverId]['pending'] = $pending;
		$mainMap[$driverId]['taken'] = $taken;
		
		
		$deliverQuery = mysqli_query($con,"SELECT * FROM sheets WHERE date ='$date' AND delivered_by = $driverId" ) or die(mysqli_error($con));		
		foreach($deliverQuery as $del)
			$deliveryMap[$driverId][] = $del;

		$pendingQuery = mysqli_query($con,"SELECT * FROM sheets WHERE date ='$date' AND assigned_to = $driverId AND status ='requested'" ) or die(mysqli_error($con));		
		foreach($pendingQuery as $pend)
			$pendingMap[$driverId][] = $pend;						
			
		$takenQuery = mysqli_query($con,"SELECT * FROM sheets WHERE closed_on ='$date' AND closed_by = $driverId" ) or die(mysqli_error($con));		
		foreach($takenQuery as $tak)
			$takenMap[$driverId][] = $tak;			
	}

	if(isset($_GET['urlId']))
		$urlId = $_GET['urlId'];
	else	
		$urlId = (int)array_key_first($driverMap);
?>	
<html>
	<head>
		<title>Day Book</title>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
	</head>
	<body>
		<br/><br/>
		<div align="center">
			<h2><i class="fa fa-calendar"></i> Day Book</h2><br/>
			<input type="text" name="date" id="date" onchange="document.location.href = 'dayBook.php?date=' + this.value" class="form-control datepicker col-md-1" autocomplete="off" value="<?php echo date('d-m-Y',strtotime($urlDate)); ?>">
			<br/><br/>
			<table class="table table-hover table-bordered" style="width:30%;">
				<thead>
					<tr class="table-info">
						<th style="text-align:left;">Driver</th>
						<th>Total</th>
						<th>Delivered</th>
						<th>Pending</th>
						<th>Taken</th>
						
					</tr>
				</thead>
				<tbody>																																			<?php
				foreach($mainMap as $driver => $status)
				{
					if($driver != '31')
					{																																			?>
						<tr align="center" onclick="reload(<?php echo $driver;?>,<?php echo "'".$urlDate."'";?>)" style="cursor:pointer">
							<td style="text-align:left;"><?php echo $driverMap[$driver];?></b></td>
							<td><b><?php echo $status['delivered'] + $status['pending'];?></b></td>
							<td><?php echo $status['delivered'];?></td>
							<td><?php echo $status['pending'];?></td>
							<td><?php echo $status['taken'];?></td>
						</tr>																																	<?php							
					}																																			?>																																								<?php
				}																																				?>
					<tr align="center" onclick="reload('31',<?php echo "'".$urlDate."'";?>)" style="cursor:pointer">
						<td style="text-align:left;">GODOWN</b></td>
						<td><b><?php echo $mainMap['31']['delivered'];?></b></td>
						<td><?php echo $mainMap['31']['delivered'];?></td>
						<td></td>
						<td></td>
					</tr>																																					
				</tbody>	
			</table>
			<br/><br/>
			<h3><?php echo $driverMap[$urlId];?></h3><br/>
			<div class="container">
				<div class="row">
					<div class="col-12">
						<div class="card">
							<div class="card-header">
								<ul class="nav nav-tabs card-header-tabs" id="bologna-list" role="tablist">
									<li class="nav-item">
										<a class="nav-link active" href="#delivered" role="tab" aria-controls="delivered" aria-selected="true">Pending & Delivered</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" href="#taken" role="tab" aria-controls="taken" aria-selected="false">Taken</a>
									</li>
								</ul>
							</div>
							<div class="card-body">
								<div class="tab-content mt-3">



									<div class="tab-pane active" id="delivered" role="tabpanel">
										<table class="table table-hover table-bordered" style="width:95%">
											<thead>
												<tr class="table-success">
													<th style="width:15%;"><i class="fa fa-map-o"></i> Area</th>
													<th style="width:25%;"><i class="fas fa-map-marker-alt"></i> Address</th>
													<th style="width:20%;"><i class="far fa-user"></i> Contacts</th>
													<th style="width:6%;"><i class="fab fa-buffer"></i> Qty</th>
													<th style="width:15%;"><i class="far fa-comment-dots"></i> Remarks</th>
												</tr>
											</thead>
											<tbody>																										<?php
											if(isset($pendingMap[$urlId]))
											{
												foreach($pendingMap[$urlId] as $pend)
												{																										?>				
													<tr>
														<td style="background-color:#ffe6e6"><?php echo $areaMap[$pend['driver_area']];?></td>
														<td><?php echo $pend['area'];?></td>
														<td><?php 
															if(!empty($pend['customer_name']) || !empty($pend['customer_phone']))
																	echo 'Cust : '.$pend['customer_name'].', '.$pend['customer_phone'].'<br/>';
															if(!empty($pend['mason_name']) || !empty($pend['mason_phone']))							
																	echo 'Mason : '.$pend['mason_name'].', '.$pend['mason_phone'];						?>
														</td>
														<td><?php echo $pend['qty'];?></td>
														<td><?php echo $pend['remarks'];?></td>
													</tr>																								<?php				
												}																																						
											}																											
											if(isset($deliveryMap[$urlId]))
											{
												foreach($deliveryMap[$urlId] as $del)
												{																										?>				
													<tr>
														<td><?php echo $areaMap[$del['driver_area']];?></td>
														<td><?php echo $del['area'];?></td>
														<td><?php 
															if(!empty($del['customer_name']) || !empty($del['customer_phone']))
																	echo 'Cust : '.$del['customer_name'].', '.$del['customer_phone'].'<br/>';
															if(!empty($del['mason_name']) || !empty($del['mason_phone']))							
																	echo 'Mason : '.$del['mason_name'].', '.$del['mason_phone'];						?>
														</td>
														<td><?php echo $del['qty'];?></td>
														<td><?php echo $del['remarks'];?></td>
													</tr>																								<?php				
												}																																						
											}																											?>
											</tbody>																											
										</table>
										<br/>
									</div>


									<div class="tab-pane" id="taken" role="tabpanel" aria-labelledby="taken-tab">
										<table class="table table-hover table-bordered" style="width:95%">
											<thead>
												<tr style="background-color:grey;color:white">
													<th style="width:15%;"><i class="fa fa-map-o"></i> Area</th>
													<th style="width:25%;"><i class="fas fa-map-marker-alt"></i> Address</th>
													<th style="width:20%;"><i class="far fa-user"></i> Contacts</th>
													<th style="width:6%;"><i class="fab fa-buffer"></i> Qty</th>
													<th style="width:15%;"><i class="far fa-comment-dots"></i> Remarks</th>
												</tr>
											</thead>
											<tbody>																										<?php
											if(isset($takenMap[$urlId]))
											{
												foreach($takenMap[$urlId] as $tak)
												{																										?>				
													<tr>
														<td><?php echo $areaMap[$tak['driver_area']];?></td>
														<td><?php echo $tak['area'];?></td>
														<td><?php 
															if(!empty($tak['customer_name']) || !empty($tak['customer_phone']))
																	echo 'Cust : '.$tak['customer_name'].', '.$tak['customer_phone'].'<br/>';
															if(!empty($tak['mason_name']) || !empty($tak['mason_phone']))							
																	echo 'Mason : '.$tak['mason_name'].', '.$tak['mason_phone'];						?>
														</td>
														<td><?php echo $tak['qty'];?></td>
														<td><?php echo $tak['remarks'];?></td>
													</tr>																								<?php				
												}																																						
											}																											?>

											</tbody>																											
										</table>
										<br/>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>					
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
			
			function reload(driverId,urlDate)
			{
				var hrf = window.location.href;
				hrf = hrf.slice(0,hrf.indexOf("?"));
				window.location.href = hrf +"?date="+ urlDate + "&urlId=" + driverId;				
			}

			$('#bologna-list a').on('click', function (e) {
				e.preventDefault()
				$(this).tab('show')
			})		
		</script>				
	</body>
</html>																								<?php
}
else
	header("Location:../index.php");
