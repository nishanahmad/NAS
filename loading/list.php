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
	$godownMap = getGodownNames($con);
	
	$queryString = null;
	
	if(isset($_GET['date']))
		$date = date('Y-m-d',strtotime($_GET['date']));
	else	
		$date = date('Y-m-d');
		
	$sales = array();
	$salesQuery = mysqli_query($con,"SELECT * FROM nas_sale WHERE entry_date = '$date' AND (bill_no LIKE 'BB%' OR bill_no LIKE 'BC%' OR bill_no LIKE 'GB%' OR bill_no LIKE 'GC%' OR bill_no LIKE 'PB%' OR bill_no LIKE 'PC%')") or die(mysqli_error($con));
	foreach($salesQuery as $sale)
	{
		$sales[$sale['sales_id']] = $sale;
		if($queryString == null)
			$queryString = $sale['sales_id'];
		else
			$queryString = $queryString.','.$sale['sales_id'];
	}
	$array=array_map('intval', explode(',', $queryString));
	$array = implode("','",$array);

	$clearedList = mysqli_query($con,"SELECT * FROM loading WHERE status = 'cleared' AND cleared_sale IN ('".$array."')") or die(mysqli_error($con));
	foreach($clearedList as $clear)
		$clearedMap[$clear['cleared_sale']] = $clear['qty'];
	
	$pendingList = mysqli_query($con,"SELECT * FROM loading WHERE status = 'pending' ORDER BY date ASC,time ASC") or die(mysqli_error($con));

	$godownQtyMap = array();
	$godownQtyQuery = mysqli_query($con,"SELECT SUM(qty),godown FROM nas_sale WHERE entry_date = '$date' AND (bill_no LIKE 'BB%' OR bill_no LIKE 'BC%' OR bill_no LIKE 'GB%' OR bill_no LIKE 'GC%' OR bill_no LIKE 'PB%' OR bill_no LIKE 'PC%') GROUP BY godown") or die(mysqli_error($con));
	foreach($godownQtyQuery as $sale)
	{
		$godownQtyMap[$sale['godown']] = $sale['SUM(qty)'];
	}																																											?>
	
<html>
	<head>
    	<meta charset="utf-8">
	    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
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
			  <div class="input-group" style="width:15%;margin-top:50px;margin-left:40%">
				  <span class="input-group-text col-md-5"><i class="far fa-calendar-alt"></i>&nbsp;Date</span>
				  <input type="text" required name="date" id="searchDate" class="form-control datepicker" value="<?php echo date('d-m-Y',strtotime($date)); ?>">
			  </div>	
			<br/><br/>
			<div class="col-3" style="margin-left:35%;">
				<table class="table table-hover table-bordered table-sm">
					<thead>
						<tr class="table-success">
							<th style="width:100px;"><i class="fas fa-warehouse"></i> Godown</th>
							<th style="width:80px;"><i class="fab fa-buffer"></i> Qty</th>
						</tr>
					</thead>
					<tbody><?php				
					foreach($godownQtyMap as $godown => $qty)
					{																																?>
						<tr>
							<td><?php if(isset($godownMap[$godown])) echo $godownMap[$godown]; else echo 'NIL'?></td>
							<td><?php echo $qty;?></td>
						</tr>																														<?php
					}																																?>
					</tbody>																														
				</table>						  			  
			</div>	
			  <div id="main" class="row">
				  <div class="col-5" style="margin-left:5%;">
					  <div class="card">
						<div class="card-body">
							<h4 style="margin-left:35%">Total : <span class="total"></span></h4>
							<table class="table table-hover table-bordered sorttable">
								<thead>
									<tr class="table-success">
										<th style="width:100px;"><i class="fa fa-truck-moving"></i> Truck</th>
										<th style="width:100px;"><i class="fa fa-shield"></i> Product</th>
										<th style="width:80px;"><i class="fab fa-buffer"></i> Qty</th>
										<th style="width:120px;"><i class="fas fa-warehouse"></i> Godown</th>
									</tr>
								</thead>
								<tbody><?php				
								foreach($sales as $saleId => $sale)
								{																																?>
									<tr>
										<td><?php if(isset($truckNumbersMap[$sale['truck']])) echo $truckNumbersMap[$sale['truck']];?></td>
										<td><?php echo $productDetailsMap[$sale['product']]['name'];?></td>										<?php 									
										if(isset($clearedMap[$saleId]))
										{																														?>
											<td><?php echo $sale['qty'] - $clearedMap[$saleId];?></td>															<?php
										}
										else
										{																														?>
											<td><?php echo $sale['qty'];?></td>																					<?php
										}																														?>
										<td><?php if(isset($godownMap[$sale['godown']])) echo $godownMap[$sale['godown']];?></td>
									</tr>																														<?php
								}																																?>
								</tbody>																														
							</table>						  
						</div>
					  </div>
				  </div>			  
				  <div class="col-5" style="margin-left:10%;">
					  <div class="card">
						<div class="card-body">
							<h4 style="margin-left:35%">Loaded Trucks</span></h4>
							<table class="table table-hover table-bordered sorttable">
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
			</div>	  
		</div>
		<script src="list.js"></script>
	</body>
</html>																																						<?php
}
else
	header("Location:../index.php");																														?>