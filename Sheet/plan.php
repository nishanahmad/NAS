<?php
	require "../connect.php";
	session_start();	
	
	if(isset($_GET['date']))
		$date = date("Y-m-d", strtotime($_GET['date']));
	else
		$date = date("Y-m-d", strtotime("+1 day"));
	
	$drivers = mysqli_query($con,"SELECT user_id,user_name FROM users WHERE role = 'driver' ORDER BY user_id ASC") or die(mysqli_error($con));	
?>
<html>
<head>
	<title><?php echo date("d M", strtotime($date)).' driver assign';?></title>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">	
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<link rel="stylesheet" type="text/css" href="../css/font-awesome.min.css">
	<link rel="stylesheet" href="../css/navigation-dark.css">
	<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
</head>
<style>
	body {
		font-family: arial;
	}
	h1 {
		font-weight: normal;
	}
	.task-board {
		background: #2c7cbc;
		display: inline-block;
		padding: 12px;
		border-radius: 3px;
		white-space: nowrap;
		min-height: 300px;
	}

	.status-card {
		width: 250px;
		margin-right: 20px;
		background: #e2e4e6;
		border-radius: 3px;
		display: inline-block;
		vertical-align: top;
		text-align: left;
		font-size: 0.9em;
	}

	.status-card:last-child {
		margin-right: 0px;
	}

	.card-header {
		width: 100%;
		padding: 10px 10px 0px 10px;
		box-sizing: border-box;
		border-radius: 3px;
		display: block;
		font-weight: bold;
		text-align: center;
	}

	.card-header-text {
		display: block;
	}

	ul.sortable {
		padding-bottom: 10px;
	}

	ul.sortable li:last-child {
		margin-bottom: 0px;
	}

	ul {
		list-style: none;
		margin: 0;
		padding: 0px;
	}

	.text-row {
		padding: 8px 10px;
		margin: 10px;
		background: #fff;
		box-sizing: border-box;
		border-radius: 3px;
		border-bottom: 1px solid #ccc;
		cursor: pointer;
		font-size: 0.8em;
		white-space: normal;
		line-height: 20px;
	}

	.ui-sortable-placeholder {
		visibility: inherit !important;
		background: transparent;
		border: #666 2px dashed;
	}
	
	.list-group li {
		list-style: none;
	}
	.panel-info, .panel-rating, .panel-more1 {
		float: left;
		margin: 0 10px;
	}
	
</style>
<body>
	<nav class="menu-navigation-dark">
		<a href="index.php"><i class="fa fa-home"></i><span>Home</span></a>
		<a href="new.php"><i class="fa fa-plus"></i><span>New</span></a>
		<a href="requests.php"><i class="fa fa-spinner"></i><span>Pending ...</span></a>
		<a href="deliveries.php"><i class="fa fa-truck"></i><span>Delivered</span></a><?php 
		if($_SESSION['role'] != 'driver')
		{?>
			<a href="#" class="selected"><i class="fa fa-list-alt"></i><span>Driver Assign</span></a><?php
		}?>
		
	</nav>		
	<div align="center">
	<br/><br/>
	<input name="date" type="text" id="datepicker" onchange="document.location.href = 'plan.php?date=' + this.value" value="<?php echo date('d-m-Y',strtotime($date));?>"/>
	<br/><br/><br/>
	<div class="task-board">
		<div class="status-card">
			<div class="card-header">
				<span class="card-header-text">UNASSIGNED</span>
			</div>
			<ul class="sortable ui-sortable" id="sort0" data-driver-id="0"><?php
			$sheets = mysqli_query($con,"SELECT * FROM sheets WHERE assigned_to = 0 AND date = '$date' AND status = 'requested' ORDER BY requested_by") or die(mysqli_error($con));
			foreach ($sheets as $sheet) 
			{																																?>
				<li class="text-row ui-sortable-handle" data-sheet-id="<?php echo $sheet['id']; ?>"><?php echo $sheet['area'].'<br/>'.$sheet['name'].'<br/><b>'.$sheet['requested_by'].'</b>'; ?></li>											<?php
			}																																?>
			</ul>
		</div>																																<?php	
		foreach($drivers as $driver)
		{
			$driverId = $driver["user_id"];
			$sheets = mysqli_query($con,"SELECT * FROM sheets WHERE assigned_to = '$driverId' AND date = '$date' AND status = 'requested'") or die(mysqli_error($con));?>
			<div class="status-card">
				<div class="card-header">
					<span class="card-header-text"><?php echo $driver['user_name']; ?></span>
				</div>
				<ul class="sortable ui-sortable" id="sort<?php echo $driverId; ?>" data-driver-id="<?php echo $driver['user_id']; ?>"><?php
				foreach ($sheets as $sheet) 
				{																																?>
					<li class="text-row ui-sortable-handle" data-sheet-id="<?php echo $sheet['id']; ?>"><?php echo $sheet['area'].'<br/>'.$sheet['name'].'<br/><b>'.$sheet['requested_by'].'</b>'; ?></li>											<?php
				}																																?>
				</ul>
			</div>																																<?php
		}																																		?>
	</div>
	</div>
	<script>
		$(function() {

			var pickerOpts = { dateFormat:"dd-mm-yy"}; 
			$( "#datepicker" ).datepicker(pickerOpts);

			var url = 'assignment.php';
			$('ul[id^="sort"]').sortable({
				connectWith : ".sortable",
				receive : function(e, ui) {
					console.log('receive');
					var driver_id = $(ui.item).parent(".sortable").data("driver-id");
					var sheet_id = $(ui.item).data("sheet-id");
					$.ajax({
						url : url + '?driver_id=' + driver_id + '&sheet_id=' + sheet_id,
						success : function(response){}
					});
				}		
			}).disableSelection();
		});
	</script>
</body>
</html>