<!DOCTYPE html>
<?php
session_start();
if(isset($_SESSION['user_name']))
{
	require '../connect.php';
	require 'navbar.php';
    		
	$users = mysqli_query($con,"SELECT * FROM users") or die(mysqli_error($con));
	foreach($users as $user)
		$userMap[$user['user_id']] = $user['user_name'];		

	if(isset($_POST['startDate']))
	{
		$startDate = date('Y-m-d',strtotime($_POST['startDate']));
		$endDate = date('Y-m-d',strtotime($_POST['endDate']));
	}
	else
	{
		$startDate = date('Y-m-d');		
		$endDate = date('Y-m-d');		
	}

	$endDateTime = date('Y-m-d H:i:s', strtotime($endDate . ' +1 day'));
	
	$logs = mysqli_query($con,"SELECT * FROM transfer_logs WHERE transferred_on >= '$startDate' AND transferred_on <= '$endDateTime' ORDER BY transferred_on DESC") or die(mysqli_error($con));									?>
<html>
	<head>
		<title>Full Sheet transfer Logs</title>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" rel="stylesheet" type="text/css">
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/jquery.tablesorter.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/jquery.tablesorter.widgets.min.js"></script>
		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
		<style>
		.tablesorter .tablesorter-filter {
			width: 99%;
			border: 1px solid #C3E6CB;
			border-radius: 5px;  
		}		
		.tablesorter .filtered {
			display: none;
		}				
		.tablesorter .tablesorter-errorRow td {
			text-align: center;
			background-color: #e6bf99;
		}		
		</style>
	</head>
	<body>
		<br/><br/>		
			<div align="center">
				<h2><i class="fa fa-file-text"></i> Transfer Logs</i></h2><br/>
				<form method="post" action="">
					<div class="row" style="margin-left:35%">
						<div style="width:220px;">
							<div class="input-group">
								<span class="input-group-text col-md-5"><i class="far fa-calendar-alt"></i>&nbsp;From</span>
								<input type="text" required name="startDate" id="startDate" class="form-control datepicker" autocomplete="off" value="<?php echo date('d-m-Y',strtotime($startDate)); ?>">
							</div>
						</div>
						<div style="width:220px;">
							<div class="input-group">
								<span class="input-group-text col-md-5"><i class="far fa-calendar-alt"></i>&nbsp;To</span>
								<input type="text" required name="endDate" id="endDate" class="form-control datepicker" value="<?php echo date('d-m-Y',strtotime($endDate)); ?>">
							</div>
						</div>
						&nbsp;&nbsp;&nbsp;&nbsp;
						<div style="width:220px;">
							<div class="input-group">
								<input type="submit" class="btn" style="background-color:#54698D;color:white;" value="Search">		
							</div>
						</div>
					</div>
					<br/>
				</form>	
				<br/><br/>				
				<table class="table table-hover table-bordered" style="width:70%">
					<thead>
						<tr class="table-info">
							<th style="width:120px;">Date</th>
							<th style="width:120px;">Time</th>
							<th></th>
							<th style="width:50px;">Qty</th>
							<th style="width:120px;">Transfrd By</th>
							<th style="width:140px;">Stock</th>
							<th style="width:250px;">Remarks</th>
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
							<td><?php echo $log['remarks'];?></td>
						</tr>																											<?php
					}																													?>
					</tbody>
				</table>
			</div>	
		<script>
			$(function(){
				$("table").tablesorter({
					dateFormat : "ddmmyyyy",
					theme : 'bootstrap',
					widgets: ['filter'],
					filter_columnAnyMatch: true
				}); 
				
				var pickerOpts = { dateFormat:"dd-mm-yy"}; 
				$( ".datepicker" ).datepicker(pickerOpts);	
			});
		</script>
	</body>
</html>
<?php
}
else
	header("Location:../index.php");
?>