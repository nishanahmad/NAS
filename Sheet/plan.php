<?php
	require "../connect.php";
	
	$tomorrow = date("Y-m-d", strtotime("+1 day"));
	$drivers = mysqli_query($con,"SELECT user_id,user_name FROM users WHERE role = 'driver' ORDER BY user_id ASC") or die(mysqli_error($con));	
?>
<html>
<head>
	<title>Trello Like Drag and Drop Cards for Project Management Software</title>
	<link rel="stylesheet"
		href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

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
</style>
<body>
	<h1>Trello Like Drag and Drop Cards for Project Management Software</h1>
	<div class="task-board">
		<div class="status-card">
			<div class="card-header">
				<span class="card-header-text">Unassigned</span>
			</div>
			<ul class="sortable ui-sortable" id="sort0" data-driver-id="0"><?php
			$sheets = mysqli_query($con,"SELECT * FROM sheets WHERE assigned_to = 0 AND date = '$tomorrow'") or die(mysqli_error($con));
			foreach ($sheets as $sheet) 
			{																																?>
				<li class="text-row ui-sortable-handle" data-sheet-id="<?php echo $sheet["id"]; ?>"><?php echo $sheet["area"].'<br/>'.$sheet["name"]; ?></li>											<?php
			}																																?>
			</ul>
		</div>																																<?php	
		foreach($drivers as $driver)
		{
			$sheets = mysqli_query($con,"SELECT * FROM sheets WHERE assigned_to = 0 AND date = '$tomorrow'") or die(mysqli_error($con));?>
			<div class="status-card">
				<div class="card-header">
					<span class="card-header-text"><?php echo $driver["user_name"]; ?></span>
				</div>
				<ul class="sortable ui-sortable" id="sort<?php echo $driver["user_id"]; ?>" data-driver-id="<?php echo $driver["user_id"]; ?>"><?php
				foreach ($sheets as $sheet) 
				{																																?>
					<li class="text-row ui-sortable-handle" data-sheet-id="<?php echo $sheet["id"]; ?>"><?php echo $sheet["area"].'<br/>'.$sheet["name"]; ?></li>											<?php
				}																																?>
				</ul>
			</div>																																<?php
		}																																		?>
	</div>
	<script>
		$(function() {
			var url = 'edit-status.php';
			$('ul[id^="sort"]').sortable({
				connectWith : ".sortable",
				receive : function(e, ui) {
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