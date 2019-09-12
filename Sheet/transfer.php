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
	
	if($_POST)
	{
		$from = (int)$_POST['from'];
		$to = (int)$_POST['to'];
		$qty = (int)$_POST['qty'];
		$transferred_on = date('Y-m-d H:i:s');
		
		$updateFrom = mysqli_query($con,"UPDATE sheets_in_hand SET qty = qty - '$qty' WHERE user=$from ");
		$updateTo = mysqli_query($con,"UPDATE sheets_in_hand SET qty = qty + '$qty' WHERE user=$to ");
		$insertLogs = mysqli_query($con, "INSERT INTO transfer_logs (user_from, user_to, qty, transferred_on) VALUES ('$from', '$to', '$qty', '$transferred_on')");
		
		header("Location:requests.php");
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
			<a href="deliveries.php"><i class="fa fa-truck"></i><span>Delivered</span></a>
			<a href="#" class="selected"><i class="fa fa-exchange"></i><span>Transfer</span></a>					<?php
			if($_SESSION['role'] != 'driver')
			{																									?>				
				<a href="transfer_logs.php"><i class="fa fa-file-text"></i><span>Transfer Logs</span></a><?php
			}?>	
		</nav>		
		<br/><br/>
		<div align="center">
			<h2>Tranfer Sheets</h2><br/>
			<form action="" method="post" style="width:400px;border:1px solid black">
				<br/><br/>
				<div class="form-group">
					<div class="col-sm-5 col-md-offset-3">
						<input type="text" name="qty" required class="form-control" placeholder="Qty" pattern="[0-9]+" title="Input a valid number"><br/><br/>
					</div>	
				</div>	

				<div class="form-group">
					<div class="col-sm-5">
						<select required name="from" id="from" class="form-control">
							<option value="">--- FROM ---</option><?php
							foreach($users as $user)
							{																																			?>
								<option value="<?php echo $user['user_id'];?>"><?php echo $user['user_name'];?></option> 						<?php
							}																																			?>
						</select>
					</div>
					<div class="col-sm-2 control-label">
						<i class="fa fa-angle-double-right"></i><i class="fa fa-angle-double-right"></i>
					</div>
					<div class="col-sm-5">
						<select required name="to" id="to" class="form-control">
							<option value="">---- TO ----</option><?php
							foreach($users as $user)
							{																																			?>
								<option value="<?php echo $user['user_id'];?>"><?php echo $user['user_name'];?></option> 						<?php
							}																																			?>
						</select>
					</div>						
				</div>						
				<br/><br/><br/><br/><br/><br/>
				<div class="form-group">
					<button type="submit" class="btn btn-info">Submit</button>
				</div>

			</form>
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
