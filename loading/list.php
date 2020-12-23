<!DOCTYPE html>
<?php
session_start();
if(isset($_SESSION["user_name"]))
{
	require '../connect.php';
	require '../sales/listHelper.php';
	require '../navbar.php';
	require 'newModal.php';
	
	$productDetailsMap = getProductDetails($con);
	$truckNumbersMap = getTruckNumbers($con);

	$pendingList = mysqli_query($con,"SELECT * FROM loading WHERE status = 'pending' ORDER BY date ASC,time ASC") or die(mysqli_error($con));
	$clearedList = mysqli_query($con,"SELECT * FROM loading WHERE status = 'cleared' AND DATE(last_updated) = CURDATE() ORDER BY time ASC") or die(mysqli_error($con));	?>
	
<html>
	<head>
    	<meta charset="utf-8">
	    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">	
		<meta http-equiv="Refresh" content="120"> 
		<link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" rel="stylesheet" type="text/css">
		<link href="../css/styles.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="../css/loading-cards.css">
		<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.9.1/jquery.tablesorter.min.js" integrity="sha512-mWSVYmb/NacNAK7kGkdlVNE4OZbJsSUw8LiJSgGOxkb4chglRnVfqrukfVd9Q2EOWxFp4NfbqE3nDQMxszCCvw==" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.9.1/jquery.tablesorter.widgets.min.js" integrity="sha512-6I1SQyeeo+eLGJ9aSsU43lGT+w5HYY375ev/uIghqqVgmSPSDzl9cqiQC4HD6g8Ltqz/ms1kcf0takjBfOlnig==" crossorigin="anonymous"></script>		
		<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
		<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.4.0/bootbox.min.js"></script>		
		<title>Loading</title>
	</head>
	<body>
		<nav class="navbar navbar-light bg-light sticky-top bottom-nav">
			<div class="btn-group" role="group" aria-label="Button group with nested dropdown" style="float:left;margin-left:2%;">
				<div class="btn-group" role="group">
					<button id="btnGroupDrop1" type="button" class="btn btn-outline-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Loading</button>
					<ul class="dropdown-menu" aria-labelledby="btnGroupDrop1" style="cursor:pointer">									
						<li id="trucks"><a class="dropdown-item" href="../trucks/list.php">Trucks</a></li>		
					</ul>
				</div>
			</div>		
			<span class="navbar-brand" style="font-size:25px;"><i class="fas fa-dolly"></i> Loading</span>
			<a href="#" class="btn btn-sm" role="button" style="background-color:#54698D;color:white;float:right;margin-right:40px;" data-toggle="modal" data-target="#newModal"><i class="fas fa-dolly"></i> New Loading</a>			
		</nav>
		<div style="width:100%;" class="mainbody">	
   			  <div id="snackbar"><i class="fas fa-dolly"></i>&nbsp;&nbsp;New Loading inserted successfully !!!</div>		
			  <div id="main" class="row">
				  <div class="col-4" style="margin-left:15%;">
					  <div class="header">
						<h2>Pending</h2>
					  </div>				  				  
					  <div class="card">
						<div class="card-body">
							<table class="table table-hover table-bordered">
								<thead>
									<tr style="background-color:#E9696E;color:#FFFFFF;">
										<th style="width:100px;"><i class="fa fa-truck-moving"></i> Truck</th>
										<th style="width:100px;"><i class="fa fa-shield"></i> Product</th>
										<th style="width:80px;"><i class="fab fa-buffer"></i> Qty</th>
										<th><i class="fa fa-calendar"></i> Loaded On</th>
									</tr>
								</thead>
								<tbody><?php				
								foreach($pendingList as $pending)
								{																													?>
									<tr>
										<td><?php echo $truckNumbersMap[$pending['truck']];?></td>
										<td><?php echo $productDetailsMap[$pending['product']]['name'];?></td>
										<td><?php echo $pending['qty'];?></td>
										<td><?php echo date('d-M',strtotime($pending['date'])).' '.date('h:i A',strtotime($pending['time']));?><i class="far fa-arrow-alt-circle-down loadId" data-id="<?php echo $pending['id'];?>" title="Unload" style="font-size:18px;float:right;cursor:pointer;"></i></td>
									</tr>																											<?php
								}																														?>
								</tbody>																														
							</table>				
						</div>						
					  </div>
				  </div>
				  <div class="col-4">
					  <div class="header">
						<h2>Cleared Today</h2>
					  </div>				  				  
					  <div class="card">
						<div class="card-body">
							<table class="table table-hover table-bordered">
								<thead>
									<tr class="table-success">
										<th style="width:100px;"><i class="fa fa-truck-moving"></i> Truck</th>
										<th style="width:100px;"><i class="fa fa-shield"></i> Product</th>
										<th style="width:80px;"><i class="fab fa-buffer"></i> Qty</th>
										<th><i class="fa fa-clock"></i> Cleared On</th>
									</tr>
								</thead>
								<tbody><?php				
								foreach($clearedList as $cleared)
								{																													?>
									<tr>
										<td><?php echo $truckNumbersMap[$cleared['truck']];?></td>
										<td><?php echo $productDetailsMap[$cleared['product']]['name'];?></td>
										<td><?php echo $cleared['qty'];?></td>
										<td><?php echo date('h:i A',strtotime($cleared['last_updated']));?></td>
									</tr>																											<?php
								}																														?>
								</tbody>																														
							</table>						  
						</div>
					  </div>
				  </div>
			</div>	  
		</div>
		<script src="list.js"></script>
	</body>
</html>																																						<?php
}
else
	header("Location:../index.php");																														?>