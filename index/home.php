<!DOCTYPE html>
<html>
<?php
session_start();

if(isset($_SESSION["user_name"]))
{
	require '../connect.php';
	require '../navbar.php';
	require '../sales/listHelper.php';   // imported to get clientNamesMap and productDetailsMap
	
	$clientNamesMap = getClientNames($con);
	$productDetailsMap = getProductDetails($con);	
	$unlockedList = mysqli_query($con, "SELECT * FROM tally_sale_check WHERE status = 'UNLOCKED' ORDER BY id") or die(mysqli_error($con));
	
	$forwardedList = mysqli_query($con, "SELECT * FROM tally_check_forwards WHERE status = 1 ORDER BY id") or die(mysqli_error($con));	
	
	$users = mysqli_query($con,"SELECT * FROM users") or die(mysqli_error($con));	
	foreach($users as $user)
		$userMap[$user['user_id']] = $user['user_name'];
		
	function getForwardStatus($saleId,$con)
	{
		$result = mysqli_query($con, "SELECT * FROM tally_check_forwards WHERE sale = '$saleId'") or die(mysqli_error($con));
		if(mysqli_num_rows($result) > 0)
		{
			$forward = mysqli_fetch_array($result, MYSQLI_ASSOC);
			if($forward['status'])
				return $forward['forwarded_by'];
			else
				return null;
		}
		else
			return null;
	}

	$billed = 0;
	$unbilled =0;
	$billedCount = 0;
	$unbilledCount = 0;
	$today = date('Y-m-d');
	$todaySales = mysqli_query($con, "SELECT * FROM nas_sale WHERE deleted IS NULL AND entry_date = '$today'") or die(mysqli_error($con));
	foreach($todaySales as $sale)
	{
		if( fnmatch("B*",$sale['bill_no']) || fnmatch("C*",$sale['bill_no']) || fnmatch("D*",$sale['bill_no']) || fnmatch("GB*",$sale['bill_no']) || fnmatch("GC*",$sale['bill_no']) || fnmatch("PB*",$sale['bill_no']) || fnmatch("PC*",$sale['bill_no']))
		{
			$billed = $billed + $sale['qty'];
			$billedCount++;
		}
		else	
		{
			$unbilledCount++;
		}
	}
	if($billed + $unbilled >0)
		$percentage = round($billed/($billed + $unbilled),0);
	else
		$percentage	= 0;
		
	
	
	/**************************************				 Find weekly sales fof last 4 weeks for Bar chart	 		**************************************/
	
	function getWeekMonSat($weekOffset) {
		$dt = new DateTime();
		$dt->setIsoDate($dt->format('o'), $dt->format('W') + $weekOffset);
		return array(
			'Mon' => $dt->format('Y-m-d'),
			'Sat' => $dt->modify('+5 day')->format('Y-m-d'),
		);
	}
	
	$week1 = getWeekMonSat(-4);
	$week2 = getWeekMonSat(-3);
	$week3 = getWeekMonSat(-2);
	$week4 = getWeekMonSat(-1);
	$week5 = getWeekMonSat(0);
	
	$query1 = mysqli_query($con,"SELECT SUM(qty) FROM nas_sale WHERE deleted IS NULL AND entry_date >='".$week1['Mon']."' AND entry_date <='".$week1['Sat']."'") or die(mysqli_error($con));		
	$sum1 = (int)mysqli_fetch_array($query1, MYSQLI_ASSOC);

	$query2 = mysqli_query($con,"SELECT SUM(qty) FROM nas_sale WHERE deleted IS NULL AND entry_date >='".$week2['Mon']."' AND entry_date <='".$week2['Sat']."'") or die(mysqli_error($con));		
	$sum2 = (int)mysqli_fetch_array($query2, MYSQLI_ASSOC);

	$query3 = mysqli_query($con,"SELECT SUM(qty) FROM nas_sale WHERE deleted IS NULL AND entry_date >='".$week3['Mon']."' AND entry_date <='".$week3['Sat']."'") or die(mysqli_error($con));		
	$sum3 = (int)mysqli_fetch_array($query3, MYSQLI_ASSOC);

	$query4 = mysqli_query($con,"SELECT SUM(qty) FROM nas_sale WHERE deleted IS NULL AND entry_date >='".$week4['Mon']."' AND entry_date <='".$week4['Sat']."'") or die(mysqli_error($con));		
	$sum4 = (int)mysqli_fetch_array($query4, MYSQLI_ASSOC);

	$query5 = mysqli_query($con,"SELECT SUM(qty) FROM nas_sale WHERE deleted IS NULL AND entry_date >='".$week5['Mon']."' AND entry_date <='".$week5['Sat']."'") or die(mysqli_error($con));		
	$sum5 = (int)mysqli_fetch_array($query5, MYSQLI_ASSOC);																																						

	$today = date('Y-m-d');	
	$sql = "SELECT * FROM nas_sale WHERE deleted IS NULL AND entry_date = '$today' ORDER BY bill_no ASC";
	
	?>

	<head>
		<link rel="stylesheet" href="home.css"/>
		<link href="../css/styles.css" rel="stylesheet" type="text/css">
		<link href="../css/navbarMobile.css" media="screen and (max-device-width: 768px)" rel="stylesheet" type="text/css">
		<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>		
		<script src="https://cdnjs.cloudflare.com/ajax/libs/easy-pie-chart/2.1.6/jquery.easypiechart.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
		<title>Home</title>
	</head>
	<body>
		<nav class="navbar navbar-light bg-light sticky-top bottom-nav">
			<span class="navbar-brand offset-5" style="font-size:25px;"><i class="fa fa-home"></i> Home</span>
		</nav>	
		<br/><br/>
		<div>
			<div class="container">	
				<div class="col-12">
					<div class="row no-gutters">
						<div class="col-3">
							<div class="d-flex flex-row flex-wrap">																															<?php
								foreach($unlockedList as $unlocked)
								{
									if(getForwardStatus($unlocked['sale'],$con) == null)
									{
										$id = $unlocked['sale'];
										$query = mysqli_query($con, "SELECT * FROM nas_sale WHERE deleted IS NULL AND sales_id = $id") or die(mysqli_error($con));
										$sale = mysqli_fetch_array($query, MYSQLI_ASSOC);																							?>
										<a class="card list-group-item list-group-item-action" href="../reports/tallyVerification.php?date=<?php echo $sale['entry_date'];?>">
											<h3><?php echo $sale['bill_no'];?></h3>
											<p class="small"><?php 
												echo $clientNamesMap[$sale['ar_id']].'<br/>'.date('d-m-Y',strtotime($sale['entry_date'])).'<br/>'.$productDetailsMap[$sale['product']]['name'].' '.$sale['qty'].' bags';?>
											</p>
											Unlocked By : <?php echo $userMap[$unlocked['unlocked_by']];?>
											<div class="dimmer"></div>
											<div class="go-corner" href="#">
												<div class="go-arrow"><i class="fa fa-unlock fa-xs"></i></div>
											</div>
										</a>																																	<?php									
									}
								}																																			?>
							</div>
						</div>
						<div class="col-3">
							<div class="d-flex flex-row flex-wrap">																															<?php
								foreach($forwardedList as $forward)
								{
									$id = $forward['sale'];
									$query = mysqli_query($con, "SELECT * FROM nas_sale WHERE deleted IS NULL AND sales_id = $id") or die(mysqli_error($con));
									$sale = mysqli_fetch_array($query, MYSQLI_ASSOC);																							?>
									<div class="card list-group-item list-group-item-action" href="../reports/tallyVerification.php?date=<?php echo $sale['entry_date'];?>">
										<h3><a href="../sales/edit.php?sales_id=<?php echo $sale['sales_id'];?>&range=Today&sql=<?php echo $sql;?>"><?php echo $sale['bill_no'];?></a></h3>
										<p class="small"><?php 
											echo $clientNamesMap[$sale['ar_id']].'<br/>'.date('d-m-Y',strtotime($sale['entry_date'])).'<br/>'.$productDetailsMap[$sale['product']]['name'].' '.$sale['qty'].' bags';?>
										</p>
										Forwarded By : <?php echo $userMap[$forward['forwarded_by']].'<br/>'.$forward['remarks'];?>
										<div class="dimmer"></div>
										<div class="go-corner" href="#" style="background-color:#DE2F2F">
											<div class="go-arrow"><i class="fas fa-arrow-right fa-xs"></i></div>
										</div>
									</div>																																	<?php
								}																																			?>
							</div>
						</div>
						<div class="col-6">
							<div class="d-flex flex-row flex-wrap">
								<div class="card" style="width:100% !important;margin-left:10%;">
									<h5 class="card-header" style="background-color:#49C9A7;color:white"><i class="fas fa-box"></i>&nbsp;&nbsp;Holdings</h5>
									<div class="card-body">
										<table class="ratetable table table-hover table-bordered" style="background-color:white;">
											<thead>
												<tr>
													<th><i class="fa fa-address-card-o"></i> AR</th>
													<th style="width:100px;"><i class="fa fa-shield"></i> Product</th>
													<th style="width:70px;"><i class="fab fa-buffer"></i> Qty</th>
													<th style="width:100px;"><i class="far fa-file-alt"></i> Bill</th>
												</tr>
											</thead>
											<tbody><?php
												$holdings = mysqli_query($con,"SELECT * FROM holdings WHERE cleared_sale IS NULL") or die(mysqli_error($con));
												foreach($holdings as $holding)
												{
													$saleQuery = mysqli_query($con,"SELECT * FROM nas_sale WHERE deleted IS NULL AND sales_id = ".$holding['returned_sale']) or die(mysqli_error($con));
													$sale = mysqli_fetch_array($saleQuery, MYSQLI_ASSOC);
													?>
													<tr>
														<td><?php echo $clientNamesMap[$holding['ar']];?></td>
														<td><?php echo $productDetailsMap[$holding['product']]['name'];?></td>
														<td><?php echo $holding['qty'];?></td>
														<td><?php echo $sale['bill_no'];?></td>
													</tr>																											<?php
												}																													?>
											</tbody>																														
										</table>
									</div>
								</div>
							</div>
						</div>						
					</div>
				</div>			
			</div>
		</div>
	</body>
</html>																																					<?php
}
else
	header("Location:../sessions/loginPage.php");																													?>