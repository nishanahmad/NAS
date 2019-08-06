<?php
	require '../connect.php';	
	session_start();	

	if(isset($_GET['delivered_by']))
		$delivered_by = $_GET['delivered_by'];
	else
		$delivered_by = 'All';
	
	$users = mysqli_query($con,"SELECT DISTINCT(delivered_by) FROM sheets WHERE status ='delivered' ORDER BY delivered_by ASC" ) or die(mysqli_error($con));	
	
	if($delivered_by == 'All')	
		$sheets = mysqli_query($con,"SELECT * FROM sheets WHERE status ='delivered' ORDER BY date ASC" ) or die(mysqli_error($con));		 	 
	else
		$sheets = mysqli_query($con,"SELECT * FROM sheets WHERE status ='delivered' AND delivered_by = '$delivered_by' ORDER BY date ASC" ) or die(mysqli_error($con));		 	 
	
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
		<title>Delivered Sheets</title>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="../css/font-awesome.min.css">
		<link rel="stylesheet" href="../css/navigation-dark.css">
		<link rel="stylesheet" href="../css/slicknav.min.css">				
		<script type="text/javascript" src="../js/jquery.js"></script> 
		<script type="text/javascript" src="../js/bootstrap.min.js"></script> 
		<script src="../js/jquery.slicknav.min.js"></script>

		<script>
			function closeRequest(id){
				var conf = confirm("Are you sure?");
				if(conf)
				{
					hrf = 'close.php?';
					window.location.href = hrf +"id="+ id;		
				}
			}
		</script>		
	</head>
	<body>
		<nav class="menu-navigation-dark">
			<a href="index.php"><i class="fa fa-home"></i><span>Home</span></a>
			<a href="new.php"><i class="fa fa-plus"></i><span>New</span></a>
			<a href="requests.php"><i class="fa fa-spinner"></i><span>Pending ...</span></a>
			<a href="#" class="selected"><i class="fa fa-truck"></i><span>Delivered</span></a><?php 
			if($_SESSION['role'] != 'driver')
			{?>
				<a href="plan.php"><i class="fa fa-list-alt"></i><span>Driver Assign</span></a><?php
			}?>
		</nav>		
		<br/><br/>
		<div align="center">
			<select name="delivered_by" id="delivered_by" onchange="document.location.href = 'deliveries.php?delivered_by=' + this.value">
				<option value = "All" <?php if($delivered_by == 'All') echo 'selected';?> >ALL</option>													    	<?php
				foreach($users as $user)
				{																																			?>
					<option value="<?php echo $user['delivered_by'];?>" <?php if($delivered_by == $user['delivered_by']) echo 'selected';?>><?php echo $user['delivered_by'];?></option> 						<?php
				}																																			?>
			</select>			
		</div>	 			
		<br/><br/><br/><br/>
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
									<p><i class="fa fa-copy"></i> <?php echo $sheet['qty'].' Nos';?></p>
									<p><i class="fa fa-calendar"></i> <?php echo date("d-m-Y",strtotime($sheet['date']));?></p>
									<p><i class="fa fa-university"></i> <?php echo $sheet['shop'];?></p>
									<p><i class="fa fa-align-left"></i> <?php echo $sheet['remarks'];?></p>
									<p><i class="fa fa-truck"></i> Delivered by <?php echo $sheet['delivered_by'];?></p>
								</div>
								<br/>
							</div>
							<div align="center">
								<a href="edit.php?id=<?php echo $sheet['id'];?>" class="btn btn-primary" style="width:100px;">Edit</a>&nbsp;&nbsp;								
								<button class="btn btn-danger" style="width:100px;" onclick="closeRequest(<?php echo $sheet['id'];?>)">Close</button>				
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
