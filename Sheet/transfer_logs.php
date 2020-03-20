<!DOCTYPE html>
<?php
session_start();
if(isset($_SESSION['user_name']))
{
	require '../connect.php';
    
	$logs = mysqli_query($con,"SELECT * FROM transfer_logs ORDER BY transferred_on DESC") or die(mysqli_error($con));
		
	$users = mysqli_query($con,"SELECT * FROM users") or die(mysqli_error($con));
	foreach($users as $user)
		$userMap[$user['user_id']] = $user['user_name'];		
		
																														?>
<html>
	<head>
		<title>Sheet transfer Logs</title>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" type="text/css" href="../css/jquery-ui.css">
		<link href="../css/bootstrap.min.css" rel="stylesheet">
		<link href="../css/dashio.css" rel="stylesheet">
		<link href="../css/dashio-responsive.css" rel="stylesheet">	
		<link href="../css/font-awesome.min.css" rel="stylesheet">	
		<link rel="stylesheet" href="../css/navigation-dark.css">
		<link rel="stylesheet" href="../css/slicknav.min.css">	
		<link rel="stylesheet" href="../css/TableSorterBlueTheme.css">			
		<script type="text/javascript" language="javascript" src="../js/jquery.js"></script>
		<script type="text/javascript" language="javascript" src="../js/jquery-ui.min.js"></script>
		<script type="text/javascript" src="../js/bootstrap.min.js"></script>
		<script src="../js/jquery.slicknav.min.js"></script>
		<script src="../js/TableSorter.js"></script>
		<script src="../js/TablesorterWidgets.js"></script>				
		<style>		
		.tooltip {
		  position: relative;
		  display: inline-block;
		  border-bottom: 1px dotted black;
		}

		.tooltip .tooltiptext {
		  visibility: hidden;
		  width: 120px;
		  background-color: black;
		  color: #fff;
		  text-align: center;
		  border-radius: 6px;
		  padding: 5px 0;
		  position: absolute;
		  z-index: 1;
		  top: -5px;
		  left: 110%;
		}

		.tooltip .tooltiptext::after {
		  content: "";
		  position: absolute;
		  top: 50%;
		  right: 100%;
		  margin-top: -5px;
		  border-width: 5px;
		  border-style: solid;
		  border-color: transparent black transparent transparent;
		}
		.tooltip:hover .tooltiptext {
		  visibility: visible;
		}
		</style>
	</head>
	<body>
		<nav class="menu-navigation-dark">																		<?php 
			if($_SESSION['role'] != 'driver')
			{																									?>	
				<a href="../index.php"><i class="fa fa-home"></i><span>Home</span></a>
				<a href="new.php"><i class="fa fa-plus"></i><span>New</span></a>
				<a href="plan.php"><i class="fa fa-list-alt"></i><span>Driver Assign</span></a>		<?php
			}																									?>	
			<a href="requests.php"><i class="fa fa-spinner"></i><span>Pending ...</span></a>
			<a href="deliveries.php"><i class="fa fa-truck"></i><span>Delivered</span></a>
			<a href="transfer.php"><i class="fa fa-exchange"></i><span>Transfer</span></a>					<?php
			if($_SESSION['role'] != 'driver')
			{																									?>				
				<a href="#" class="selected"><i class="fa fa-file-text"></i><span>Transfer Logs</span></a>
				<a href="closed.php"><i class="fa fa-check-square"></i><span>Closed</span></a><?php
			}?>	
		</nav>		
		<br/><br/>		
		<div class="row mt">
			<div class="col-lg-12">
				<div class="content-panel">
					<div align="center">
					<h2><i class="fa fa-file-text"></i> Transfer Logs</i></h2><br/>
					<section style="margin-top:20px;margin-left:100px;margin-right:100px;">
						<table class="tablesorter" style="width:60%" id="logs">
							<thead class="cf">
								<tr>
									<th style="width:100px;">Date</th>
									<th style="width:80px;">Time</th>
									<th></th>
									<th style="width:50px;">Qty</th>
									<th style="width:100px;">Transfrd By</th>
									<th style="width:120px;">Stock</th>
								</tr>
							</thead>
							<tbody>
							<?php
							foreach($logs as $log)
							{
								$sheetId = $log['site'];
								$siteQuery = mysqli_query($con,"SELECT * FROM sheets WHERE id = '$sheetId' ") or die(mysqli_error($con));
								$site = mysqli_fetch_array($siteQuery,MYSQLI_ASSOC);																								?>
								<tr>
									<td><?php echo date('d-m-Y',strtotime($log['transferred_on']));?></td>
									<td><?php echo date('h:i:s A',strtotime($log['transferred_on']));?></td>
									<td><?php 
										if(isset($userMap[$log['user_from']]))
											echo $userMap[$log['user_from']] .' ---> ';
										else
										{?>
											<a href="#" data-toggle="tooltip" data-placement="bottom" title="<?php echo $site['area'];?>">
											<?php if(!empty($site['customer_name']))
													echo $site['customer_name'].' --> ';
												  else
													echo $site['mason_name'].' --> ';?></a><?php
										}
										if(isset($userMap[$log['user_to']]))
											echo $userMap[$log['user_to']];
										else
										{?>
											<a href="#" data-toggle="tooltip" data-placement="bottom" title="<?php echo $site['area'];?>">
											<?php if(!empty($site['customer_name']))
													echo $site['customer_name'];
												  else
													echo $site['mason_name'];?></a><?php
										}?>
									</td>
									<td><?php echo $log['qty'];?></td>				
									<td><?php echo $userMap[$log['transferred_by']];?></td>
									<td><?php 
										if(isset($userMap[$log['user_from']]))
											echo $userMap[$log['user_from']].' : '.$log['fromStock'].'<br/>';
										if(isset($userMap[$log['user_to']]))
											echo $userMap[$log['user_to']].' : '.$log['toStock'];?>
									</td>									
								</tr>																											<?php
							}																													?>
							</tbody>
						</table>
					</section>
				</div>
			</div>
		</div>
		<div class="tooltip">Hover over me
		  <span class="tooltiptext">Tooltip text</span>
		</div> 				
		<script>
		$(document).ready(function() {		

			$('[data-toggle="tooltip"]').tooltip(); 		
			var menu = $('.menu-navigation-dark');

			menu.slicknav();

			menu.on('click', 'a', function(){
				var a = $(this);

				a.siblings().removeClass('selected');
				a.addClass('selected');
			});			
			
			$("table").tablesorter({
				dateFormat : "ddmmyyyy",
				theme : 'blue',
				widgets: ['filter'],
				filter_columnAnyMatch: true,
			}); 
		} );
		</script>
	</body>
</html>
<?php
}
else
	header("Location:../index.php");
?>