<!DOCTYPE html>
<?php
session_start();
if(isset($_SESSION['user_name']))
{
	require '../connect.php';
    		
	$users = mysqli_query($con,"SELECT * FROM users") or die(mysqli_error($con));
	foreach($users as $user)
		$userMap[$user['user_id']] = $user['user_name'];		
	
	if(isset($_GET['status']))
		$status = $_GET['status'];
	else
		$status = 'closed';		
	
	if($status == 'closed')
		$sheets = mysqli_query($con,"SELECT * FROM sheets WHERE status = 'closed' ORDER BY closed_on DESC") or die(mysqli_error($con));
	else
		$sheets = mysqli_query($con,"SELECT * FROM sheets WHERE status = 'cancelled' ORDER BY closed_on DESC") or die(mysqli_error($con));												?>
<html>
	<head>
		<title>Closed/Cancelled</title>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" type="text/css" href="../css/jquery-ui.css">	
		<link rel="stylesheet" href="../css/navigation-dark.css">
		<link rel="stylesheet" href="../css/slicknav.min.css">	
		<link rel="stylesheet" href="../css/TableSorterBlueTheme.css">			
		<script src="https://kit.fontawesome.com/742221945b.js" crossorigin="anonymous"></script>
		<script type="text/javascript" language="javascript" src="../js/jquery.js"></script>
		<script type="text/javascript" language="javascript" src="../js/jquery-ui.min.js"></script>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>		
		<script src="../js/jquery.slicknav.min.js"></script>
		<script src="../js/TableSorter.js"></script>
		<script src="../js/TablesorterWidgets.js"></script>		
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
		<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
		<script>
		$(document).ready(function(){
			$('#status').selectpicker();			
		});
		</script>
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
				<a href="transfer_logs.php"><i class="fa fa-file-text"></i><span>Transfer Logs</span></a>
				<a href="#" class="selected"><i class="fa fa-check-square"></i><span>Closed</span></a><?php
			}?>	
		</nav>		
		<br/><br/>		
		<div class="row mt">
			<div class="content-panel">
				<div align="center">
					<select name="status" id="status" onchange="document.location.href = 'closed.php?status=' + this.value" class="form-control col-2">
						<option value = "closed" <?php if($status == 'closed') echo 'selected';?> data-content="<i class='fa fa-check' aria-hidden='true'></i> Closed"></option>
						<option value = "cancelled" <?php if($status == 'cancelled') echo 'selected';?> data-content="<i class='fa fa-trash' aria-hidden='true'></i> Deleted"></option>
					</select>			
				</div>
				<br/>																																			<?php 
				if($status == 'closed')
				{																																					?>
					<h2 style="margin-left:44%;" ><i class="fa fa-check"></i> Closed</i></h2><br/><?php
				}
				else
				{																											?>
					<h2 style="margin-left:44%;" ><i class="fa fa-trash"></i> Deleted</i></h2><br/>						<?php
				}																											?>
				
				<section style="margin-top:20px;margin-left:100px;margin-right:100px;">
					<table class="tablesorter" style="width:70%" id="logs">
						<thead class="cf">
							<tr>
								<th>Area</th>
								<th>Customer Name</th>
								<th>Customer Phone</th>
								<th>Mason Name</th>
								<th>Mason Phone</th>																		<?php 
								if($status == 'closed')
								{																							?>
									<th>Qty</th>																			<?php
								}																							?>
								<th style="min-width:100px;"><?php if($status == 'closed') echo 'Closed On'; else echo 'Cancelled On';?></th>
								<th><?php if($status == 'closed') echo 'Closed By'; else echo 'Cancelled By';?></th><?php
								if($status == 'cancelled')
								{																							?>
									<th style="min-width:250px;">Cancel Reason</th>											<?php
								}																							?>									
							</tr>
						</thead>
						<tbody>

						<?php
						foreach($sheets as $sheet)
						{?>
							<tr>
								<td><?php echo $sheet['area'];?></td>
								<td><?php echo $sheet['customer_name'];?></td>
								<td><?php echo $sheet['customer_phone'];?></td>														
								<td><?php echo $sheet['mason_name'];?></td>
								<td><?php echo $sheet['mason_phone'];?></td>																							<?php 
								if($status == 'closed')
								{																							?>
									<td style="text-align:center;"><?php echo $sheet['qty'];?></td>							<?php
								}																							?>
								<td><?php echo date('d-m-Y',strtotime($sheet['closed_on']));?></td>
								<td><?php echo $userMap[$sheet['closed_by']];?></td>										<?php
								if($status == 'cancelled')
								{																							?>
									<td><?php echo $sheet['cancel_reason'];?></td>							<?php
								}																							?>									
							</tr>																											<?php
						}																													?>
						</tbody>
					</table>
				</section>
			</div>
		</div>
		<div class="tooltip">Hover over me
		  <span class="tooltiptext">Tooltip text</span>
		</div> 						
        <script>        
        $(function(){
            $("table").tablesorter({
                dateFormat : "ddmmyyyy",
                theme : 'blue',
                widgets: ['filter'],
                filter_columnAnyMatch: true,
            }); 
        });
        </script>       
	</body>
</html>
<?php
}
else
	header("Location:../index.php");
?>