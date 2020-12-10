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
	$todaySales = mysqli_query($con, "SELECT * FROM nas_sale WHERE entry_date = '$today'") or die(mysqli_error($con));
	foreach($todaySales as $sale)
	{
		if( fnmatch("BB*",$sale['bill_no']) || fnmatch("BC*",$sale['bill_no']) || fnmatch("GB*",$sale['bill_no']) || fnmatch("GC*",$sale['bill_no']) || fnmatch("PB*",$sale['bill_no']) || fnmatch("PC*",$sale['bill_no']))
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
	
	$query1 = mysqli_query($con,"SELECT SUM(qty) FROM nas_sale WHERE entry_date >='".$week1['Mon']."' AND entry_date <='".$week1['Sat']."'") or die(mysqli_error($con));		
	$sum1 = (int)mysqli_fetch_array($query1, MYSQLI_ASSOC);

	$query2 = mysqli_query($con,"SELECT SUM(qty) FROM nas_sale WHERE entry_date >='".$week2['Mon']."' AND entry_date <='".$week2['Sat']."'") or die(mysqli_error($con));		
	$sum2 = (int)mysqli_fetch_array($query2, MYSQLI_ASSOC);

	$query3 = mysqli_query($con,"SELECT SUM(qty) FROM nas_sale WHERE entry_date >='".$week3['Mon']."' AND entry_date <='".$week3['Sat']."'") or die(mysqli_error($con));		
	$sum3 = (int)mysqli_fetch_array($query3, MYSQLI_ASSOC);

	$query4 = mysqli_query($con,"SELECT SUM(qty) FROM nas_sale WHERE entry_date >='".$week4['Mon']."' AND entry_date <='".$week4['Sat']."'") or die(mysqli_error($con));		
	$sum4 = (int)mysqli_fetch_array($query4, MYSQLI_ASSOC);

	$query5 = mysqli_query($con,"SELECT SUM(qty) FROM nas_sale WHERE entry_date >='".$week5['Mon']."' AND entry_date <='".$week5['Sat']."'") or die(mysqli_error($con));		
	$sum5 = (int)mysqli_fetch_array($query5, MYSQLI_ASSOC);																																						?>

	<head>
		<link rel="stylesheet" href="home.css"/>
		<link href="../css/styles.css" rel="stylesheet" type="text/css">
		<link href="../css/navbarMobile.css" media="screen and (max-device-width: 768px)" rel="stylesheet" type="text/css">
		<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>		
		<script src="https://cdnjs.cloudflare.com/ajax/libs/easy-pie-chart/2.1.6/jquery.easypiechart.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
		<title>Home</title>
		<style>		
			.graphcontainer {
			  display: grid;
			  grid-template-columns: repeat(1, 160px);
			  grid-gap: 80px;
			  margin: auto 0;
			  background:#0d0c2d;
			}

			@media (min-width: 420px) and (max-width: 659px) {
			  .graphcontainer {
				grid-template-columns: repeat(2, 160px);
			  }
			}

			@media (min-width: 660px) and (max-width: 899px) {
			  .graphcontainer {
				grid-template-columns: repeat(3, 160px);
			  }
			}

			@media (min-width: 900px) {
			  .graphcontainer {
				grid-template-columns: repeat(4, 160px);
			  }
			}

			.graphcontainer .box {
			  margin:20px;		
			  width: 100%;
			}

			.graphcontainer .box h2 {
			  display: block;
			  text-align: center;
			  color: #fff;
			}

			.graphcontainer .box .chart {
			  position: relative;
			  width: 100%;
			  height: 100%;
			  text-align: center;
			  font-size: 40px;
			  line-height: 160px;
			  height: 160px;
			  color: #fff;
			}

			.graphcontainer .box canvas {
			  position: absolute;
			  top: 0;
			  left: 0;
			  width: 100%;
			  width: 100%;
			}
		</style>
	</head>
	<body>
		<nav class="navbar navbar-light bg-light sticky-top bottom-nav">
			<span class="navbar-brand offset-5" style="font-size:25px;"><i class="fa fa-home"></i> Home</span>
		</nav>	
		<br/><br/>
		<div>
			<div class="container">	
				<div class="col-8">
					<div class="row no-gutters">
						<div class="col-6">
							<div class="d-flex flex-row flex-wrap">																															<?php
								foreach($unlockedList as $unlocked)
								{
									if(getForwardStatus($unlocked['sale'],$con) == null)
									{
										$id = $unlocked['sale'];
										$query = mysqli_query($con, "SELECT * FROM nas_sale WHERE sales_id = $id") or die(mysqli_error($con));
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
						<div class="col-6">
							<div class="d-flex flex-row flex-wrap">																															<?php
								foreach($forwardedList as $forward)
								{
									$id = $forward['sale'];
									$query = mysqli_query($con, "SELECT * FROM nas_sale WHERE sales_id = $id") or die(mysqli_error($con));
									$sale = mysqli_fetch_array($query, MYSQLI_ASSOC);																							?>
									<a class="card list-group-item list-group-item-action" href="../reports/tallyVerification.php?date=<?php echo $sale['entry_date'];?>">
										<h3><?php echo $sale['bill_no'];?></h3>
										<p class="small"><?php 
											echo $clientNamesMap[$sale['ar_id']].'<br/>'.date('d-m-Y',strtotime($sale['entry_date'])).'<br/>'.$productDetailsMap[$sale['product']]['name'].' '.$sale['qty'].' bags';?>
										</p>
										Forwarded By : <?php echo $userMap[$forward['forwarded_by']].'<br/>'.$forward['remarks'];?>
										<div class="dimmer"></div>
										<div class="go-corner" href="#" style="background-color:#DE2F2F">
											<div class="go-arrow"><i class="fas fa-arrow-right fa-xs"></i></div>
										</div>
									</a>																																	<?php
								}																																			?>
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