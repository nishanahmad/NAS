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
	
	if(isset($_POST['status']))
		$status = $_POST['status'];
	else
		$status = 'closed';

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
	
	if($status == 'closed')
		$sheets = mysqli_query($con,"SELECT * FROM sheets WHERE status = 'closed' AND closed_on >= '$startDate' AND closed_on <= '$endDate' ORDER BY closed_on DESC") or die(mysqli_error($con));
	else
		$sheets = mysqli_query($con,"SELECT * FROM sheets WHERE status = 'cancelled' AND closed_on >= '$startDate' AND closed_on <= '$endDate' ORDER BY closed_on DESC") or die(mysqli_error($con));

	$areaList = mysqli_query($con,"SELECT id,name FROM sheet_area ORDER BY name ASC");
	foreach($areaList as $area)
		$areaMap[$area['id']] = $area['name'];															?>
<html>
	<head>
		<title>Closed/Cancelled</title>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" rel="stylesheet" type="text/css">
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/jquery.tablesorter.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/jquery.tablesorter.widgets.min.js"></script>
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
		<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
		<script>
		$(document).ready(function(){
			$('#status').selectpicker();	

			var pickerOpts = { dateFormat:"dd-mm-yy"}; 
			$( ".datepicker" ).datepicker(pickerOpts);				
		});
		</script>
		<style>
		.tablesorter {
			width: auto;
		}		
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
		</div>
		<br/><br/>
		<div style="margin-left:42%;">
			<select name="status" id="status" class="form-control col-2">
				<option value = "closed" <?php if($status == 'closed') echo 'selected';?> data-content="<i class='fa fa-check' aria-hidden='true'></i> Closed"></option>
				<option value = "cancelled" <?php if($status == 'cancelled') echo 'selected';?> data-content="<i class='fa fa-trash' aria-hidden='true'></i> Deleted"></option>
			</select>&nbsp;&nbsp;&nbsp;&nbsp;		
			<input type="submit" class="btn" style="background-color:#54698D;color:white;" value="Search">		
		</div>
		<br/>
	</form>	
	<br/><br/>
	<div align="center">																							<?php 
		if($status == 'closed')
		{																											?>
			<table class="table table-hover table-bordered" style="width:95%">
				<thead>
					<tr class="table-success">
						<th>Area</th>
						<th>Address</th>
						<th>Customer</th>
						<th>Mason</th>
						<th>Qty</th>
						<th style="width:120px;">Delvrd On</th>
						<th style="width:120px;">Closed On</th>
						<th>Closed By</th>
						<th>Req By</th>
					</tr>
				</thead>
				<tbody>																								<?php
				foreach($sheets as $sheet)
				{																									?>
					<tr>
						<td><?php if(!empty($sheet['driver_area'])) echo $areaMap[$sheet['driver_area']];?></td>
						<td><?php echo $sheet['area'];?></td>
						<td><?php echo $sheet['customer_name'].'<br/>'.$sheet['customer_phone'];?></td>
						<td><?php echo $sheet['mason_name'].'<br/>'.$sheet['mason_phone'];?></td>
						<td style="text-align:center;"><?php echo $sheet['qty'];?></td>
						<td><?php echo date('d-m-Y',strtotime($sheet['delivered_on']));?></td>
						<td><?php echo date('d-m-Y',strtotime($sheet['closed_on']));?></td>
						<td><?php echo $userMap[$sheet['closed_by']];?></td>
						<td><?php echo $sheet['requested_by'];?></td>
					</tr>																											<?php
				}																													?>
				</tbody>
			</table>																												<?php			
		}
		else
		{																															?>
			<table class="table table-hover table-bordered" style="width:90%">
				<thead>
					<tr class="table-success">
						<th>Area</th>
						<th>Address</th>
						<th>Customer</th>
						<th>Mason</th>
						<th style="width:120px;">Deleted On</th>
						<th>DeletedBy</th>
						<th style="width:250px;">Delete Reason</th>
						<th>Requested By</th>
					</tr>
				</thead>
				<tbody>																								<?php
				foreach($sheets as $sheet)
				{																									?>
					<tr>
						<td><?php if(!empty($sheet['driver_area'])) echo $areaMap[$sheet['driver_area']];?></td>
						<td><?php echo $sheet['area'];?></td>
						<td><?php echo $sheet['customer_name'].'<br/>'.$sheet['customer_phone'];?></td>
						<td><?php echo $sheet['mason_name'].'<br/>'.$sheet['mason_phone'];?></td>
						<td><?php echo date('d-m-Y',strtotime($sheet['closed_on']));?></td>
						<td><?php echo $userMap[$sheet['closed_by']];?></td>
						<td><?php echo $sheet['cancel_reason'];?></td>
						<td><?php echo $sheet['requested_by'];?></td>
					</tr>																											<?php
				}																													?>
				</tbody>
			</table><?php			
		}?>
	</div>
	<script>        
	$(function(){
		$("table").tablesorter({
			dateFormat : "ddmmyyyy",
			theme : 'bootstrap',
			widgets: ['filter'],
			filter_columnAnyMatch: true,
		}); 
	});
	</script>       
	</body>
</html>																															<?php
}
else
	header("Location:../index.php");
?>