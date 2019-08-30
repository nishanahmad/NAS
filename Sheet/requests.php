<?php
	require '../connect.php';
	session_start();	
	
	$designation = $_SESSION['role'];
	$userId = $_SESSION['user_id'];

	$driversQuery = mysqli_query($con,"SELECT user_id,user_name FROM users WHERE role ='driver'" ) or die(mysqli_error($con));
	foreach($driversQuery as $driver)
		$drivers[$driver['user_id']] = $driver['user_name'];
	
	if($designation != 'driver')
	{
		if(isset($_GET['requested_by']))
			$requested_by = $_GET['requested_by'];
		else
			$requested_by = 'All';

		$users = mysqli_query($con,"SELECT DISTINCT(requested_by) FROM sheets WHERE status ='requested' ORDER BY requested_by ASC" ) or die(mysqli_error($con));
		
		if($requested_by == 'All')
			$sheets = mysqli_query($con,"SELECT * FROM sheets WHERE status ='requested' ORDER BY date ASC" ) or die(mysqli_error($con));
		else
			$sheets = mysqli_query($con,"SELECT * FROM sheets WHERE status ='requested' AND requested_by = '$requested_by' ORDER BY date ASC" ) or die(mysqli_error($con));		
	}
	else
		$sheets = mysqli_query($con,"SELECT * FROM sheets WHERE status ='requested' AND assigned_to = '$userId' ORDER BY date ASC" ) or die(mysqli_error($con));		
	
	$inHandQuery = mysqli_query($con,"SELECT SUM(qty) FROM sheets_in_hand" ) or die(mysqli_error($con));
	$stockInHand = (int)mysqli_fetch_array($inHandQuery,MYSQLI_ASSOC)['SUM(qty)'];
?>	
<html>
	<style>
	.list-group li {
		list-style: none;
	}
	.panel-info, .panel-rating, .panel-more1 {
		float: left;
		margin: 0 10px;
	}
	</style>
	<head>
		<title>Pending Requests</title>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="../css/font-awesome.min.css">
		<link rel="stylesheet" href="../css/navigation-dark.css">
		<link rel="stylesheet" href="../css/slicknav.min.css">
		<script src="../js/jquery.js"></script> 
		<script src="../js/bootstrap.min.js"></script> 
		<script src="../js/jquery.slicknav.min.js"></script>
		<script>
		function deliver(id){
			var designation = "<?php echo $designation;?>";				
			var qty = window.prompt("Enter number of sheets delivered to this site");
			if(designation != 'driver')
				var driver = window.prompt("Enter driver name");
			else
				var driver = "<?php echo $_SESSION["user_name"];?>";				
			
			if(qty == null || driver == null)
			{
				return false;
			}
			else
			{
				if(isNaN(qty) || qty <= 0)
				{
					alert('Please enter a valid number');
					return false;
				}
				else
				{
					hrf = 'deliver.php?';
					window.location.href = hrf +"id="+ id + "&qty=" + qty + "&driver=" + driver;
				}				
			}
		}
		function cancel(id){
			var conf = confirm("This will cancel this request. Are you sure?");
			if(conf)
			{
				hrf = 'cancel.php?';
				window.location.href = hrf +"id="+ id;		
			}
		}
		</script>
	</head>
	<body>
		<nav class="menu-navigation-dark">																		<?php 
			if($_SESSION['role'] != 'driver')
			{																									?>	
				<a href="../index.php"><i class="fa fa-home"></i><span>Home</span></a>
				<a href="new.php"><i class="fa fa-plus"></i><span>New</span></a>
				<a href="plan.php"><i class="fa fa-list-alt"></i><span>Driver Assign</span></a>					<?php
			}																									?>	
			<a href="requests.php" class="selected"><i class="fa fa-spinner"></i><span>Pending ...</span></a>
			<a href="deliveries.php"><i class="fa fa-truck"></i><span>Delivered</span></a>
		</nav>		
		
		<br/><br/>																								<?php
		if($designation != 'driver')
		{																										?>
			<div align="center">
				<select name="requested_by" id="requested_by" onchange="document.location.href = 'requests.php?requested_by=' + this.value">
					<option value = "All" <?php if($requested_by == 'All') echo 'selected';?> >ALL</option>													    	<?php
					foreach($users as $user)
					{																																			?>
						<option value="<?php echo $user['requested_by'];?>" <?php if($requested_by == $user['requested_by']) echo 'selected';?>><?php echo $user['requested_by'];?></option> 						<?php
					}																																			?>
				</select>			
			</div>																							<?php	 				
		}																									?>			
		<br/><br/>
		<div align="center">
			<h2><?php echo $stockInHand;?> Sheets Available</h2>
			<br/><br/>
		</div>
		<div class="container" >
			<ul class="list-group">																			<?php 
				foreach($sheets as $sheet)
				{																							?>
					<li>
						<div class="panel panel-default">
							<div class="panel-body">
								<div class="panel-info">
									<p><i class="fa fa-map-marker"></i><strong> <?php echo $sheet['area'];?></strong></p>
									<p><i class="fa fa-user"></i> <?php echo $sheet['name'];?>
									, <i class="fa fa-phone"></i> <a href="tel:<?php echo $sheet['phone'];?>"><?php echo $sheet['phone'];?></a></p>
									<p><i class="fa fa-copy"></i> <?php echo $sheet['bags'].' bags';?></p>
									<p><i class="fa fa-calendar"></i> <?php echo date("d-m-Y",strtotime($sheet['date']));?></p>
									<p><i class="fa fa-university"></i> <?php echo $sheet['shop'];?></p>
									<p><i class="fa fa-align-left"></i> <?php echo $sheet['remarks'];?></p>	
									<p><i class="fa fa-truck"></i> Requested by <?php echo $sheet['requested_by'];?></p>							<?php 
									if($sheet['created_on'] != null && $designation != 'driver')
									{																																?>
										<p><i class="fa fa-clock-o"></i> Requested on <?php echo date('M d, h:i A', strtotime($sheet['created_on']));?></p>			<?php
									}
									if($designation != 'driver' && $sheet['assigned_to'] != 0)
									{																																?>
										<p><i class="fa fa-share"></i> Assigned to <?php echo $drivers[$sheet['assigned_to']];?></p>			<?php										
									}																																?>
								</div>
								<br/>
							</div>
							<div align="center">
								<a href="edit.php?id=<?php echo $sheet['id'];?>" class="btn btn-warning"><i class="fa fa-pencil"></i> Edit</a>&nbsp;&nbsp;								
								<button class="btn btn-primary" onclick="deliver(<?php echo $sheet['id'];?>)">Deliver</button>&nbsp;&nbsp;&nbsp;&nbsp;<?php
								if($designation != 'driver')
								{																														?>																																								
									<button class="btn btn-danger" onclick="cancel(<?php echo $sheet['id'];?>)">Cancel</button>							<?php
								}																														?>
							</div>
							<br/><br/>
						</div>
					</li>																					<?php				
				}																							?>

			</ul>
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
			});

		</script>		
	</body>
</html>