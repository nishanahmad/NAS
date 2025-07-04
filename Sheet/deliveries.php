<?php
	
session_start();	

if(isset($_SESSION["user_name"]))
{
	require '../connect.php';	
	require 'navbar.php';	
	require 'closeModal.php';	
	
	if(isset($_GET['panelId']))
		$panelId = $_GET['panelId'];
	else
		$panelId = 'mainView';   // Don't scroll
		
	$designation = $_SESSION['role'];	

	$damageQuery = mysqli_query($con,"SELECT user_id FROM users WHERE user_name = 'Damage'" ) or die(mysqli_error($con));
	$damageId = (int)mysqli_fetch_array($damageQuery,MYSQLI_ASSOC)['user_id'];
	
	$driversQuery = mysqli_query($con,"SELECT user_id,user_name FROM users WHERE role ='driver' ORDER BY user_name") or die(mysqli_error($con));
	foreach($driversQuery as $driver)
		$drivers[$driver['user_id']] = $driver['user_name'];

	$shopsQuery = mysqli_query($con,"SELECT id,shop_name FROM ar_details WHERE shop_name IS NOT NULL AND shop_name != '' ORDER BY shop_name ASC");
	foreach($shopsQuery as $shop)
		$shops[$shop['id']] = $shop['shop_name'];		
		
	$users = mysqli_query($con,"SELECT * FROM users WHERE role ='driver' ORDER BY user_name ASC" ) or die(mysqli_error($con));
	foreach($users as $user)
		$userMap[$user['user_id']] = $user['user_name']; 
	
	if(isset($_GET['delivered_by']))
		$delivered_by = $_GET['delivered_by'];
	else
		$delivered_by = 'All';
	
	$mainAreaQuery = mysqli_query($con,"SELECT id,name,driver FROM sheet_area ORDER BY name") or die(mysqli_error($con));
	foreach($mainAreaQuery as $mainArea)
	{
		$mainAreaMap[$mainArea['id']] = $mainArea['name'];		
		$areaDriverMap[$mainArea['id']] = $mainArea['driver'];
		$driverAreaMap[$mainArea['driver']][] = $mainArea['id'];
	}

	if($designation != 'driver')
	{
		if($delivered_by == 'All')
		{
			$sheets = mysqli_query($con,"SELECT * FROM sheets WHERE status ='delivered' ORDER BY date ASC" ) or die(mysqli_error($con));		 	 
		}		
		else
		{
			if(isset($driverAreaMap[$delivered_by]))
			{
				$areaIds = implode("','",$driverAreaMap[$delivered_by]);
				$sheets = mysqli_query($con,"SELECT * FROM sheets WHERE status ='delivered' AND driver_area IN('$areaIds') ORDER BY date ASC" ) or die(mysqli_error($con));		 	 																																			
			}
			else
			{
				$sheets = [];
			}
		}
			
	}
	else
	{
		if(isset($driverAreaMap[$_SESSION['user_id']]))
			$areaIds = implode("','",$driverAreaMap[$_SESSION['user_id']]);
		else
			$areaIds = '';	
		
		$sheets = mysqli_query($con,"SELECT * FROM sheets WHERE status ='delivered' AND driver_area IN('$areaIds') ORDER BY date ASC" ) or die(mysqli_error($con));
	}
	
	$driverToCollectMap = array();
	$totalToCollect = 0;
	$toCollectQuery = mysqli_query($con,"SELECT SUM(qty),driver_area FROM sheets WHERE status ='delivered' GROUP BY driver_area" ) or die(mysqli_error($con));
	foreach($toCollectQuery as $toCollect)
	{
		$driver = $areaDriverMap[$toCollect['driver_area']];
		if(!isset($driverToCollectMap[$driver]))
			$driverToCollectMap[$driver] = $toCollect['SUM(qty)'];
		else
			$driverToCollectMap[$driver] = $driverToCollectMap[$driver] + $toCollect['SUM(qty)'];
			
		$totalToCollect = $totalToCollect + $toCollect['SUM(qty)'];
	}
	
	$lateDate = date('Y-m-d',strtotime("-3 days"));
	$driverLateMap = array();
	$lateQuery = mysqli_query($con,"SELECT SUM(qty),driver_area FROM sheets WHERE status ='delivered' AND date < '$lateDate' GROUP BY driver_area" ) or die(mysqli_error($con));
	foreach($lateQuery as $late)
	{
		$driver = $areaDriverMap[$late['driver_area']];
		if(!isset($driverLateMap[$driver]))
			$driverLateMap[$driver] = $late['SUM(qty)'];
		else
			$driverLateMap[$driver] = $driverLateMap[$driver] + $late['SUM(qty)'];
	}	
		
	$today = date('Y-m-d');
	$todayPendingMap = array();
	$todayPendingQuery = mysqli_query($con,"SELECT count(id),assigned_to FROM sheets WHERE status ='requested' AND date = '$today' GROUP BY assigned_to" ) or die(mysqli_error($con));
	foreach($todayPendingQuery as $todayPending)
	{
		$driver = $todayPending['assigned_to'];
		$todayPendingMap[$driver] = $todayPending['count(id)'];
	}	
?>	
<html>
	<head>
		<title>Delivered Sheets</title>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<style>
		.stockTable{
			border: 1px solid black;
			width:300px;
		}	
		.stockTable th,td {
			padding: 5px;	
			border: 1px solid black;
		}
		</style>
	</head>
	<body>
		<div align="center">
			<br/><br/>
			<h2>Delivered</h2><br/>
			<div class="col-md-4 col-lg-4"><?php
				if($designation != 'driver')
				{										?>
					<table class="stockTable" style="width:100%">
						<tr>
							<th></th>
							<th style="text-align:center">In hand</th>
							<th style="text-align:center">To collect</th>
							<th style="text-align:center">Pend Today</th>
						</tr><?php
						$totalInHand = 0;
						$stockQuery = mysqli_query($con,"SELECT * FROM sheets_in_hand WHERE user != $damageId") or die(mysqli_error($con));
						foreach($stockQuery as $stock)
						{	
							$totalInHand = $totalInHand + $stock['qty'];
							if($stock['qty'] > 0)
							{																																			?>
								<tr>
									<td><?php echo $drivers[$stock['user']];?></td>
									<td style="text-align:center"><?php echo $stock['qty'];?></td>
									<td style="text-align:center"><?php 
										if(isset($driverToCollectMap[$stock['user']]))
										{
											echo '<font style="float:left;margin-left:10px;">'.$driverToCollectMap[$stock['user']].'</font>'; 
											if(isset($driverLateMap[$stock['user']])) 
												echo '<font style="float:right;color:#DC143C;margin-right:10px;">'.$driverLateMap[$stock['user']].'</font>';
										}																										?>
									</td>
									<td style="text-align:center"><?php 
										if(isset($todayPendingMap[$stock['user']])) 
											echo $todayPendingMap[$stock['user']].' sites'; 													?>
									</td>
								</tr>																											<?php													
							}								
						}																														?>
						<tr>
							<th></th>
							<th style="text-align:center"><?php echo $totalInHand;?></th>
							<th style="text-align:center"><?php echo '<font style="float:left;margin-left:10px;">'.$totalToCollect.'</font>';?></th>
						</tr>																										
						<tr>
							<th>Total</th>
							<th colspan="2" style="text-align:center"><?php echo $totalInHand + $totalToCollect;?></th>
						</tr>																																
					</table>
					<br/><br/>			
					<select name="delivered_by" id="delivered_by" onchange="document.location.href = 'deliveries.php?delivered_by=' + this.value" class="form-control col-md-6 col-lg-6">
						<option value = "All" <?php if($delivered_by == 'All') echo 'selected';?> >ALL</option>													    	<?php
						foreach($users as $user)
						{
							if($user['user_id'] != '31' && $user['user_id'] != '38')
							{																								?>
								<option value="<?php echo $user['user_id'];?>" <?php if($delivered_by == $user['user_id']) echo 'selected';?>><?php echo $user['user_name'];?></option> 						<?php								
							}
						}																																			?>
					</select>																						<?php							
				}
				else
				{																									?>
					<font size="5"><b><?php if(isset($driverToCollectMap[$_SESSION['user_id']])) echo $driverToCollectMap[$_SESSION['user_id']];?></b> sheets on site<br/>
								   <b><font style="color:#DC143C"><?php if(isset($driverLateMap[$_SESSION['user_id']])) echo $driverLateMap[$_SESSION['user_id']];else echo '0'?></font></b> sheets late to collect<br/>
					</font>												<?php
				}																									?>		
				
				<br/><br/>
			</div>
		</div><?php
		if($designation != 'driver')
		{																																?>
			<a href="deliveries_table.php" style="margin-left:46%">Table View</a><br/><br/>												<?php
		}																																?>		
		<div class="container"><?php 
			$divId = 1;																				
			foreach($sheets as $sheet)
			{																							?>
					<div class="card" id="panel<?php echo $divId;?>">
						<div class="card-header" style="background-color:#2a739e;color:#ffffff;font-family:Bookman;text-transform:uppercase;"><i class="fa fa-map-marker"></i> <?php echo $sheet['area']; if($sheet['driver_area'] > 0) echo '<font style="margin-left:10%;font-weight:bold">'.$mainAreaMap[$sheet['driver_area']].'</font>' ?><p style="float:right"><?php echo $sheet['id'];?></p></div>
						<div class="card-body"><?php
							if($sheet['customer_name'])
							{?>
								<p><i class="fas fa-user-tie"></i> Cust : <?php echo $sheet['customer_name'];?>
								, <i class="fa fa-mobile"></i> <a href="tel:<?php echo $sheet['customer_phone'];?>"><?php echo $sheet['customer_phone'];?></a></p><?php
							}
							if($sheet['mason_name'])
							{?>
								<p><i class="fa fa-user"></i> Mason : <?php echo $sheet['mason_name'];?>
								, <i class="fa fa-mobile"></i> <a href="tel:<?php echo $sheet['mason_phone'];?>"><?php echo $sheet['mason_phone'];?></a></p><?php
							}?>									
							<p><i class="fa fa-file"></i> <?php echo $sheet['qty'].' sheets for '.$sheet['bags']. ' bags';?></p><?php
							if($sheet['half_qty'] > 0)
							{?>
								<p><i class="fa fa-file"></i> <?php echo $sheet['half_qty'].' half sheets for '.$sheet['bags']. ' bags';?></p><?php
							}?>
							
							<p><i class="fa fa-calendar"></i> <?php echo date("d-m-Y",strtotime($sheet['date']));?></p>
							<p><i class="fas fa-store"></i> <?php 							
								if(isset($sheet['shop1']) && $sheet['shop1'] != 0)
									echo $shops[$sheet['shop1']];
								else
									echo $sheet['shop'];?>
							</p>
							<p><i class="fa fa-align-left"></i> <?php echo $sheet['remarks'];?></p>																	<?php
							if($designation != 'driver')
							{?>
								<p><i class="fas fa-desktop"></i> Req by <b><?php echo $sheet['requested_by'];?> </b> on <?php echo date('d M, h:i A', strtotime($sheet['created_on']));?></p>														<?php
							}?>									
							<p><i class="fa fa-truck"></i> Deliv by																									<?php
														if($userMap[$sheet['delivered_by']] == 'GODOWN')
														{
															echo $sheet['driver'];																					?>
															<a href="tel:<?php echo $sheet['phone'];?>"><?php echo $sheet['phone'];?></a>							<?php
														}
														else
														{
															echo $userMap[$sheet['delivered_by']];
														}																											?>
								on <?php echo date("d-m-Y",strtotime($sheet['delivered_on']));?>						
							</p>																															<?php 
							if($sheet['coveringBlock'])
							{																																?>
								<p style="color:#cc0000"><i class="fas fa-th"></i> Covering Block</p>														<?php	
							}																																?>						
							<br/>							
							<br/>
							<div align="center">
								<a href="edit.php?id=<?php echo $sheet['id'];?>" class="btn" style="color:#ffffff;background-color:e1be5c;width:100px;"><i class="fa fa-pencil"></i> Edit</a>&nbsp;&nbsp;
								<button class="btn closeId" style="color:#ffffff;background-color:7dc37d;width:100px;" data-id="<?php echo $sheet['id'];?>" data-closeqty="<?php echo $sheet['qty'];?>" data-closehalfqty="<?php echo $sheet['half_qty'];?>" data-toggle="modal" data-target="#closeModal"><i class="fas fa-check"></i> Close</button>
							</div>
						</div>
					</div>
					<br/><br/>																			<?php	
				$divId ++;
			}																							?>
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
				
				$('.closeId').click(function(){
					var closeId = $(this).data('id');
					var closeqty = $(this).data('closeqty');
					var closehalfqty = $(this).data('closehalfqty');
					$("#closeIdhidden").val(closeId);
					$("#qty").val(closeqty);
					$("#half_qty").val(closehalfqty);
				});					
			});

		</script>				
	</body>
</html>																								<?php
}
else
	header("Location:../index.php");