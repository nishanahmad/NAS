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
		<script type="text/javascript" language="javascript" src="../js/jquery.js"></script>
		<script type="text/javascript" language="javascript" src="../js/jquery-ui.min.js"></script>
		<script type="text/javascript" language="javascript" src="../js/jquery.dataTables.min.js"></script>
		<script type="text/javascript" src="../js/bootstrap.min.js"></script>
		<script src="../js/jquery.slicknav.min.js"></script>
		<style>
		.dataTables_length{
		  display:none;
		}
		.dataTables_paginate{
		  display:none;
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
				<a href="#" class="selected"><i class="fa fa-file-text"></i><span>Transfer Logs</span></a><?php
			}?>	
		</nav>		
		<br/><br/>
		<div class="row mt">
			<div class="col-lg-12">
				<div class="content-panel">
					<h2 style="margin-left:40%;" ><i class="fa fa-file-text"></i> Transfer Logs</i></h2><br/>
					<section style="margin-top:40px;">
						<form id="searchbox">
							<table class="col-md-offset-3" style="width:50%">
								<tr>
									<td style="width:25%;padding:2px;"><input type="text" data-column="0"  class="form-control" placeholder="Date"></td>
									<td style="width:20%;padding:2px;"><input type="text" data-column="2"  class="form-control" placeholder="From"></td>	
									<td style="width:20%;padding:2px;"><input type="text" data-column="3"  class="form-control" placeholder="To"></td>	
									<td style="width:15%;padding:2px;"><input type="text" data-column="4"  class="form-control" placeholder="Qty"></td>	
									<td style="width:20%;padding:2px;"><input type="text" data-column="5"  class="form-control" placeholder="Transfd By"></td>	
								</tr>	
							</table>	
						</form>																					
						<table class="table table-bordered table-striped col-md-offset-3" style="width:50%" id="logs">
							<thead class="cf">
								<tr>
									<th style="width:15%;">Date</th>
									<th style="width:15%;">Time</th>
									<th style="width:20%;">From</th>
									<th style="width:20%;">To</th>
									<th style="width:10%;">Qty</th>
									<th style="width:20%;">Transfd By</th>
								</tr>
							</thead>
							<tbody>

							<?php
							foreach($logs as $log)
							{																												?>
								<tr>
									<td><?php echo date('d-m-Y',strtotime($log['transferred_on']));?></td>
									<td><?php echo date('h:i A',strtotime($log['transferred_on']));?></td>
									<td><?php echo $userMap[$log['user_from']];?></td>
									<td><?php echo $userMap[$log['user_to']];?></td>
									<td><?php echo $log['qty'];?></td>				
									<td><?php echo $userMap[$log['transferred_by']];?></td>									
								</tr>																								<?php
							}																										?>
							</tbody>
						</table>
					</section>
				</div>
			</div>
		</div>
		<script>
		$(document).ready(function() {
		
			var menu = $('.menu-navigation-dark');

			menu.slicknav();

			// Mark the clicked item as selected

			menu.on('click', 'a', function(){
				var a = $(this);

				a.siblings().removeClass('selected');
				a.addClass('selected');
			});			
			
			var table = $('#logs').DataTable({
				"iDisplayLength": 10000
			});
				
			$("#logs_filter").css("display","none");  // hiding global search box
			$('.form-control').on( 'keyup click', function () {   // for text boxes
				var i =$(this).attr('data-column');  // getting column index
				var v =$(this).val();  // getting search input value
				table.columns(i).search(v).draw();
			} );
			$('.select').on( 'change', function () {   // for select box
				var i =$(this).attr('data-column');  
				var v =$(this).val();  
				table.columns(i).search(v).draw();
			} );	

		} );
		</script>
	</body>
</html>
<?php
}
else
	header("Location:../index.php");
?>