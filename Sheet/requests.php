<?php
	
session_start();

if(isset($_SESSION["user_name"]))
{	
	require '../connect.php';
	require 'navbar.php';
	require 'deliverModal.php';
	
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
		
	if($designation != 'driver')
	{
		if(isset($_GET['assigned_to']))
			$assigned_to = $_GET['assigned_to'];
		else
			$assigned_to = 'All';

		$users = mysqli_query($con,"SELECT DISTINCT(assigned_to) FROM sheets WHERE status ='requested' AND assigned_to > 0 ORDER BY assigned_to ASC" ) or die(mysqli_error($con));
		
		if($assigned_to == 'All')
			$sheets = mysqli_query($con,"SELECT * FROM sheets WHERE status ='requested' ORDER BY date ASC, priority DESC" ) or die(mysqli_error($con));
		else
			$sheets = mysqli_query($con,"SELECT * FROM sheets WHERE status ='requested' AND assigned_to = '$assigned_to' ORDER BY date ASC, priority DESC" ) or die(mysqli_error($con));		
	}
	else
		$sheets = mysqli_query($con,"SELECT * FROM sheets WHERE status ='requested' AND assigned_to = '$userId' ORDER BY date ASC" ) or die(mysqli_error($con));		
	
	$damageQuery = mysqli_query($con,"SELECT user_id FROM users WHERE user_name = 'Damage'" ) or die(mysqli_error($con));
	$damageId = (int)mysqli_fetch_array($damageQuery,MYSQLI_ASSOC)['user_id'];
	
	$inHandQuery = mysqli_query($con,"SELECT SUM(qty) FROM sheets_in_hand WHERE user != $damageId" ) or die(mysqli_error($con));
	$stockInHand = (int)mysqli_fetch_array($inHandQuery,MYSQLI_ASSOC)['SUM(qty)'];
	
	//$agr = mysqli_query($con,"SELECT SUM(qty) FROM sheets WHERE status ='delivered' " ) or die(mysqli_error($con));
	//$onSite = (int)mysqli_fetch_Array($agr,MYSQLI_ASSOC)['SUM(qty)'];	
	
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
	<head>
		<title>Pending Requests</title>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<link href="../css/styles.css" rel="stylesheet" type="text/css">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<script>		
		function cancel(id){
			bootbox.prompt({
				title: "Enter reason for cancellation",
				inputType: 'text',
				callback: function (result) {
					if(result)
					{
						console.log(result);
						hrf = 'cancel.php?';
						window.location.href = hrf +"id="+ id + "&reason=" + result;		
					}						
				}
			});										
		}		
		
		function addRemarks(id){
			bootbox.prompt({
				title: "Enter remarks here",
				inputType: 'text',
				callback: function (result) {
					if(result)
					{
						console.log(result);
						hrf = 'driver_remarks.php?';
						window.location.href = hrf +"id="+ id + "&remarks=" + result;		
					}						
				}
			});										
		}	

		</script>
	</head>
	<body>
		<div align="center">
			<br/><br/>
			<h2>Pending Requests</h2><br/>
			<div class="col-md-4 col-lg-4">
																																		<?php
				if($designation != 'driver')
				{																																							?>
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
								$totalInHand = $totalInHand + $stock['qty'];																	?>
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
							}																													?>
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
						<select name="assigned_to" id="assigned_to" onchange="document.location.href = 'requests.php?assigned_to=' + this.value" class="form-control col-md-6 col-lg-6">
							<option value = "All" <?php if($assigned_to == 'All') echo 'selected';?> >ALL</option>													    	<?php
							foreach($users as $user)
							{																																				?>
								<option value="<?php echo $user['assigned_to'];?>" <?php if($assigned_to == $user['assigned_to']) echo 'selected';?>><?php echo $drivers[$user['assigned_to']];?></option> 						<?php
							}																																			?>
						</select>
						<br/><br/>					<?php	 				
				}
				else
				{
					$stockQuery = mysqli_query($con,"SELECT * FROM sheets_in_hand WHERE user = '$userId'") or die(mysqli_error($con));
					$stock = mysqli_fetch_array($stockQuery,MYSQLI_ASSOC);
					echo '<b>'.$stock['qty'].'</b> sheets in hand<br/><br/>';
					//$godownQuery = mysqli_query($con,"SELECT * FROM sheets_in_hand WHERE user = 31") or die(mysqli_error($con));
					//$godown = mysqli_fetch_array($godownQuery,MYSQLI_ASSOC);
					//echo $godown['qty'].' sheets in Godown<br/><br/>';
					if(isset($todayPendingMap[$userId])) echo $todayPendingMap[$userId].' sites pending today</b><br/><br/>';
				}				?>
			</div>
		</div>	
		<div class="container" >																											<?php 
				foreach($sheets as $sheet)
				{																															?>
					<div class="card">
						<div class="card-header" style="background-color:#2a739e;color:#ffffff;font-family:Bookman;text-transform:uppercase;"><i class="fa fa-map-marker"></i> <?php echo $sheet['area']; if($sheet['driver_area'] > 0) echo '<font style="margin-left:10%;font-weight:bold">'.$mainAreaMap[$sheet['driver_area']].'</font>'; ?><p style="float:right"><?php echo $sheet['id'];?></p></div>
						<div class="card-body"><?php
							if($sheet['priority'])
							{																																?>
								<p style="color:#cc0000;font-size:18px;"><b><i class="fas fa-exclamation-triangle"></i> Priority Site</b></p>														<?php	
							}																									
							if(!empty($sheet['customer_name']) || !empty($sheet['customer_phone']))
							{?>
								<p><i class="fa fa-user"></i> Cust :  <?php echo $sheet['customer_name'];?>
								, <i class="fa fa-mobile"></i> <a href="tel:<?php echo $sheet['customer_phone'];?>"><?php echo $sheet['customer_phone'];?></a></p><?php
							}
							if(!empty($sheet['mason_name']) || !empty($sheet['mason_phone']))
							{?>
								<p><i class="fa fa-user"></i> Mason :  <?php echo $sheet['mason_name'];?>
								, <i class="fa fa-mobile"></i> <a href="tel:<?php echo $sheet['mason_phone'];?>"><?php echo $sheet['mason_phone'];?></a></p><?php
							}?>
							<p><i class="fa fa-calendar"></i> <?php echo date("d-m-Y",strtotime($sheet['date']));?>, <i class="fa fa-shopping-bag"></i> <?php echo $sheet['bags'].' bags';?>							   
							</p>
							<p><i class="fas fa-store"></i> <?php echo $sheet['shop'];?></p>
							<p><i class="fas fa-desktop"></i> Req by <b><?php echo $sheet['requested_by']; 
							if($sheet['created_on'] != null && $designation != 'driver')
							{																																?>
								</b> on <?php echo date('d M, h:i A', strtotime($sheet['created_on']));?></p>												<?php
							}?>
							<p><i class="fa fa-align-left"></i> <?php echo $sheet['remarks'];?></p>															<?php
							if($designation != 'driver' && $sheet['assigned_to'] != 0)
							{																																?>
								<p><i class="fa fa-share"></i> Assigned to <b><?php echo $drivers[$sheet['assigned_to']];?></b>								<?php
								if(!empty($sheet['driver_remarks']))
								{
									echo ' - <font style="font-weight:bold;color:red">'.$sheet['driver_remarks'].'</font> on '.date('d M, h:i A', strtotime($sheet['driver_remarks_dt']));
								}
								else if($sheet['driver_read'] == 1)
								{																															?>
									<font style="margin-left:5%"><i class="fa fa-eye fa-lg"style="color:#4285F4"></i></font>																													<?php
								}								
							}																																?>
							</p>																															<?php 
							if($sheet['coveringBlock'])
							{																																?>
								<p style="color:#cc0000"><i class="fas fa-th"></i> Covering Block</p>														<?php	
							}																																?>						
							<br/>
							<div align="center">
								<a href="edit.php?id=<?php echo $sheet['id'];?>" class="btn" style="margin-right:10px;color:#ffffff;background-color:e1be5c;width:80px;"><i class="fa fa-pencil"></i> Edit</a>	
								<button class="btn deliverId" style="margin-right:10px;color:#ffffff;background-color:7dc37d;width:100px;" data-id="<?php echo $sheet['id'];?>" data-toggle="modal" data-target="#deliverModal"><i class="fas fa-check"></i> Deliver</button><?php
								if($designation != 'driver')
								{																														?>																																								
									<button class="btn" onclick="cancel(<?php echo $sheet['id'];?>)" style="margin-right:10px;background-color:#E6717C;color:#FFFFFF;width:80px;"><i class="far fa-trash-alt"></i> Dlt</button>							<?php
								}
								else
								{
									if($sheet['driver_remarks'] == null)
									{
										if($sheet['driver_read'] == 0)
										{																																																																		?>
											<button class="btn" id="read<?php echo $sheet['id'];?>" value="<?php echo $sheet['id'];?>" style="margin-right:10px;background-color:#4285F4;color:#FFFFFF;width:90px;" onclick="markRead(this.value)"><i class="fa fa-eye" aria-hidden="true"></i> Read</button><?php
										}																																																																	?>									
										<button class="btn" onclick="addRemarks(<?php echo $sheet['id'];?>)" style="background-color:#E6717C;color:#FFFFFF;width:110px;"><i class="fa fa-comment"></i> Remark</button><?php
									}																																																							
								}?>
							</div>
						</div>
					</div>
					<br/><br/><br/>																		<?php				
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

				
				// SHOW ERROR IF RETURNED URL CONTAINS ERROR
				var error = "<?php echo $error;?>";
				if(error == 'true')
				{
					bootbox.alert("Not enough sheets in hand to deliver!!!");					
				}
				
				$('.deliverId').click(function(){
					var deliverId = $(this).data('id');
					$("#deliverIdhidden").val(deliverId);
				});	
				
				var pickeropts = { dateFormat:"dd-mm-yy"};
				$( ".datepicker" ).datepicker(pickeropts);				
			});

			function markRead(sheetId){
				$.ajax({
					type: "POST",
					url: "markReadAJAX.php",
					data:'sheetId='+sheetId,
					success: function(response){
						if(response != false){
							console.log(response);
							$('#read'+response).hide();
							//$('#'+response).find('td').eq(6).addClass("green");
						}
						else{
							alert('Some error occured. Try again');
							location.reload();
						}
					}
				});	  
			}
		</script>		
	</body>
</html>																				<?php
}
else
	header("Location:../index.php");
