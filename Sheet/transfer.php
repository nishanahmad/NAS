<?php
	require '../connect.php';	
	session_start();	

if(isset($_SESSION["user_name"]))
{	
	$driversQuery = mysqli_query($con,"SELECT user_id,user_name FROM users WHERE role ='driver' ORDER BY user_name") or die(mysqli_error($con));
	
	
	$users = mysqli_query($con,"SELECT * FROM users WHERE role ='driver' ORDER BY user_name ASC" ) or die(mysqli_error($con));
	foreach($users as $user)
	{
		$userMap[$user['user_id']] = $user['user_name']; 
	}
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
	
	.modal-footer button {
	  float:right;
	  margin-left: 10px;
	}
	
	</style>
	<head>
		<title>Transfer Stock</title>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="../css/font-awesome.min.css">
		<link rel="stylesheet" href="../css/navigation-dark.css">
		<link rel="stylesheet" href="../css/slicknav.min.css">				
		<script type="text/javascript" src="../js/jquery.js"></script> 
		<script type="text/javascript" src="../js/bootstrap.min.js"></script> 
		<script src="../js/bootbox.min.js"></script>
		<script src="../js/jquery.slicknav.min.js"></script>
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
			<a href="deliveries.php" class="selected"><i class="fa fa-truck"></i><span>Delivered</span></a>
			<a href="#" class="selected"><i class="fa fa-truck"></i><span>Transfer</span></a>					<?php
			if($_SESSION['role'] != 'driver')
			{																									?>				
				<a href="transfer_logs.php" class="selected"><i class="fa fa-truck"></i><span>Transfer Logs</span></a><?php
			}?>	
		</nav>		
		<br/><br/>
		<div align="center">
			<h2>Tranfer Sheets</h2><br/>
			<select name="delivered_by" id="delivered_by" onchange="document.location.href = 'deliveries.php?delivered_by=' + this.value">
				<option value = "All" <?php if($delivered_by == 'All') echo 'selected';?> >ALL</option>													    	<?php
				foreach($users as $user)
				{																																			?>
					<option value="<?php echo $user['user_id'];?>" <?php if($delivered_by == $user['user_id']) echo 'selected';?>><?php echo $user['user_name'];?></option> 						<?php
				}																																			?>
			</select>			
		
			<br/><br/>
			<h2><?php echo $onSite;?> Sheets to collect</h2>
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
									<p><i class="fa fa-file"></i> <?php echo $sheet['qty'].' Nos';?></p>
									<p><i class="fa fa-calendar"></i> <?php echo date("d-m-Y",strtotime($sheet['delivered_on']));?></p>
									<p><i class="fa fa-university"></i> <?php echo $sheet['shop'];?></p>
									<p><i class="fa fa-align-left"></i> <?php echo $sheet['remarks'];?></p>																	<?php
									if($designation != 'driver')
									{?>
										<p><i class="fa fa-pencil"></i> Req by <?php echo $sheet['requested_by'];?></p>														<?php
									}?>									
									<p><i class="fa fa-truck"></i> Deliv by <?php echo $userMap[$sheet['delivered_by']];?></p>
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
</html>																								<?php
}
else
	header("Location:../index.php");