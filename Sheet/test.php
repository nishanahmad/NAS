<!DOCTYPE html>
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
	<link rel="icon" type="image/png" href="st/og-image.png">
	<title>SortableJS</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="../css/theme.css">

	<meta charset="utf-8"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
	<meta property="og:image" content="/st/og-image.png"/>
	<meta name="keywords" content="sortable, reorder, list, javascript, html5, drag and drop, dnd, animation, groups, dnd, sortableJS"/>
	<meta name="description" content="Sortable — is a JavaScript library for reorderable drag-and-drop lists on modern browsers and touch devices. No jQuery required. Supports Meteor, AngularJS, React, Polymer, Vue, Knockout and any CSS library, e.g. Bootstrap."/>
	<meta name="viewport" content="width=device-width, initial-scale=0.5"/>
	<style>
	.task-board {
		background: #2c7cbc;
		display: inline-block;
		padding: 12px;
		border-radius: 3px;
		white-space: nowrap;
		min-height: 300px;
		width:100%;
	}	
	.status-card {
		width: 250px;
		margin-left: 15px;
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
	.text-row {
		padding: 8px 10px;
		margin: 10px;
		box-sizing: border-box;
		border-radius: 3px;
		border-bottom: 1px solid #ccc;
		cursor: pointer;
		font-size: 0.8em;
		white-space: normal;
		line-height: 20px;
	}
	</style>
</head>
<body>
	<br/><br/><br/><br/>
	<div class="task-board">
		<div id="shared-lists" class="row">
			<div id="block1" class="status-card">
				<div class="card-header">
					<span class="card-header-text">UNASSIGNED</span>
				</div><?php
				$sheets = mysqli_query($con,"SELECT * FROM sheets WHERE assigned_to = 0 AND date = '$date' AND status = 'requested' ORDER BY requested_by") or die(mysqli_error($con));
				foreach ($sheets as $sheet) 
				{																																?>
					<div class="text-row list-group-item" id="<?php echo $sheet['id'];?>"><?php echo $sheet['area'].'<br/>'.$sheet['name'].'<br/><b>'.$sheet['bags'].' bags<br/>'.$sheet['requested_by'].'</b>'; ?></div><?php
				}																																?>
				</ul>
			</div>																																<?php			
			$i=2;
			foreach($drivers as $driver)
			{
				$driverId = $driver["user_id"];																				?>
				<div id="block<?php echo $i;?>" class="status-card">
					<div class="card-header">
						<span class="card-header-text"><?php echo $driver['user_name'];?></span>
					</div><?php
					$sheets = mysqli_query($con,"SELECT * FROM sheets WHERE assigned_to = '$driverId' AND date = '$date' AND status = 'requested' ORDER BY requested_by") or die(mysqli_error($con));
					foreach ($sheets as $sheet) 
					{																																?>
						<div class="text-row list-group-item" id="<?php echo $sheet['id'];?>"><?php echo $sheet['area'].'<br/>'.$sheet['name'].'<br/><b>'.$sheet['bags'].' bags<br/>'.$sheet['requested_by'].'</b>'; ?></div><?php
					}																																?>
				</div><?php
				$i++;
			}?>
			<div style="padding: 0" class="col-12">
			</div>
		</div>
		<hr />
	</div>


	<!-- Latest Sortable -->
	<script src="../js/Sortable.min.js"></script>
	<script src="../js/Sortable-app.js"></script>
</body>
</html>