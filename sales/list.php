<!DOCTYPE html>
<?php
session_start();
if(isset($_SESSION["user_name"]))
{
	require '../connect.php';
	require 'listHelper.php';
	require '../navbar.php';
	require 'newModal.php';
	require 'rateModal.php';
	require 'filterModal.php';

	$currentRateMap = getCurrentRates($con);
	$clientNamesMap = getClientNames($con);
	$productNamesMap = getProductNames($con);
	$discountMap = getDiscounts($con);
	$truckNumbersMap = getTruckNumbers($con);
	
	$filterSql = null;
	if(isset($_GET['sql']))
		$filterSql = $_GET['sql'];
	
	if(isset($_GET['range']))
		$range = $_GET['range'];
	else
		$range = 'Custom Filter';

	$mainMap = array();
	if(isset($filterSql))
	{
		$productSumMap = getProductSum($con,$filterSql);
		$mainMap = getSales($con,$filterSql);
	}

	$rateMap = getRateMap();
	$cdMap = getCDMap();
	$wdMap = getWDMap();
	
	$productDates = mysqli_query($con, "SELECT * FROM rate ORDER BY date") or die(mysqli_error($con));				 	 
	foreach($productDates as $rate)
		$productDateMap[$rate['product']][] = strtotime($rate['date']);

	$arObjects = mysqli_query($con,"SELECT id,name,type,shop_name FROM ar_details ORDER BY name") or die(mysqli_error($con));	
	foreach($arObjects as $ar)
	{
		if($ar['type'] != 'Engineer Only')
			$arMap[$ar['id']] = $ar['name']; 
		if($ar['type'] == 'Engineer' || $ar['type'] == 'Contractor' || $ar['type'] == 'Engineer Only')
			$engMap[$ar['id']] = $ar['name'];
		
		$shopName = strip_tags($ar['shop_name']); 
		$shopNameMap[$ar['id']] = $shopName;

		$shopNameArray = json_encode($shopNameMap);
		$shopNameArray = str_replace('\n',' ',$shopNameArray);
		$shopNameArray = str_replace('\r',' ',$shopNameArray);		
	}																																				?>	

<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">	
		<link href="../css/styles.css" rel="stylesheet" type="text/css">	
		<link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" rel="stylesheet" type="text/css">
		<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.9.1/jquery.tablesorter.min.js" integrity="sha512-mWSVYmb/NacNAK7kGkdlVNE4OZbJsSUw8LiJSgGOxkb4chglRnVfqrukfVd9Q2EOWxFp4NfbqE3nDQMxszCCvw==" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.9.1/jquery.tablesorter.widgets.min.js" integrity="sha512-6I1SQyeeo+eLGJ9aSsU43lGT+w5HYY375ev/uIghqqVgmSPSDzl9cqiQC4HD6g8Ltqz/ms1kcf0takjBfOlnig==" crossorigin="anonymous"></script>
		<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet"/>
		<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
		<title>Sales</title>
		<style>
			@media only screen and (max-width: 900px) {
				.desktop-only{display: none;}	
			}
		
			.select2-selection__rendered {
				line-height: 33px !important;
			}
			.select2-container .select2-selection--single {
				height: 38px !important;
			}
			.select2-selection__arrow {
				height: 37px !important;
			}

			/* REQUIRED CSS: change your reflow breakpoint here (35em below) */
			@media ( max-width: 35em ) {

			  table.ui-table-reflow thead { display: none; }

			  /* css for reflow & reflow2 widgets */
			  .ui-table-reflow td,
			  .ui-table-reflow th {
				-webkit-box-sizing: border-box;
				-moz-box-sizing: border-box;
				box-sizing: border-box;
				float: right;
				/* if not using the stickyHeaders widget (not the css3 version)
				 * the "!important" flag, and "height: auto" can be removed */
				width: 100% !important;
				height: auto !important;
			  }

			  /* reflow widget only */
			  .ui-table-reflow tbody td[data-title]:before {
				color: #469;
				font-size: .9em;
				content: attr(data-title);
				float: left;
				width: 50%;
				white-space: pre-wrap;
				text-align: bottom;
				display: inline-block;
			  }

			  /* reflow2 widget only */
			  table.ui-table-reflow .ui-table-cell-label.ui-table-cell-label-top {
				display: block;
				padding: .4em 0;
				margin: .4em 0;
				text-transform: uppercase;
				font-size: .9em;
				font-weight: 400;
			  }
			  table.ui-table-reflow .ui-table-cell-label {
				padding: .4em;
				min-width: 30%;
				display: inline-block;
				margin: -.4em 1em -.4em -.4em;
			  }

			} /* end media query */

			/* reflow2 widget */
			.ui-table-reflow .ui-table-cell-label {
			  display: none;
			}			
		</style>			
	</head>
	<body>
		<nav class="navbar navbar-light bg-light sticky-top bottom-nav">
			<div class="btn-group" role="group" aria-label="Button group with nested dropdown" style="float:left;margin-left:2%;">
				<div class="btn-group" role="group">
					<button id="btnGroupDrop1" type="button" class="btn btn-outline-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
						<i class="far fa-calendar-alt"></i> <?php echo $range;?>
					</button>
					<ul class="dropdown-menu" aria-labelledby="btnGroupDrop1" style="cursor:pointer">									
						<li id="todayFilter"><a class="dropdown-item">Today</a></li>							
						<li id="10DaysFilter"><a class="dropdown-item">10 Days</a></li>
						<li id="customFilter" class="dropdown-item">Custom Filter</a></li>				
					</ul>
				</div>
			</div>					
			<span class="navbar-brand" style="font-size:25px;"><i class="fa fa-bolt"></i> Sales</span>
			<a href="#" class="btn btn-sm" role="button" style="background-color:#54698D;color:white;float:right;margin-right:3%;" data-toggle="modal" data-target="#saleModal"><i class="fa fa-bolt"></i> New Sale</a>			
		</nav>
		<div style="width:100%;" class="mainbody">	
			<div id="snackbar"><i class="fa fa-bolt"></i>&nbsp;&nbsp;Sale detail saved successfully !!!</div>
			<div align="center">
				<br/><br/>
				<table class="ratetable table table-hover table-bordered" <?php if($range == 'Today') echo 'style="width:35%;"'; else echo 'style="width:20%;"';?> id="ratetable">
					<thead>
						<tr class="table-info">
							<th><i class="fa fa-shield"></i> Product</th>
							<th style="width:90px;"><i class="fab fa-buffer"></i> Qty</th>															<?php 
							if($range == 'Today')
							{																														?>
								<th style="width:90px;"><i class="fa fa-rupee-sign"></i> Rate</th>
								<th style="width:110px;"><i class="fa fa-tags"></i> Discount</th>													<?php
							}																														?>
						</tr>
					</thead>
					<tbody id="ratebody"><?php				
						foreach($currentRateMap as $product=>$rate)
						{
							if(isset($productSumMap[$product]))
							{																														?>
								<tr>
									<td><?php echo $productNamesMap[$product];?></td>
									<td><?php echo $productSumMap[$product];?></td><?php 
									if($range == 'Today')
									{																												?>
										<td><?php echo $rate.'/-';?></td>
										<td><?php if(isset($discountMap[$product])) echo $discountMap[$product].'/-';?></td>						<?php
									}																												?>	
								</tr>																												<?php
							}	
						}																															?>
					</tbody>																														<?php 
					if($range == 'Today')
					{																																?>
						<tfoot id="ratefoot">
							<tr>
								<td colspan="4" style="text-align:center"><a href="#" class="link-success" data-toggle="modal" data-target="#rateModal">All Product Rates</a></td>
							</tr>			
						</tfoot>																														<?php
					}																																?>
				</table>
			</div>
			<br/><br/>
			<table class="maintable table table-hover table-bordered ui-table-reflow" style="width:95%;margin-left:2%;">
				<thead>
					<tr class="table-success">
						<th style="width:110px;"><i class="far fa-calendar-alt"></i> Date</th>
						<th><i class="fa fa-address-card-o"></i> AR</th>
						<th style="width:70px;"><i class="fa fa-shield"></i> PRO</th>
						<th style="width:70px;"><i class="fab fa-buffer"></i> QTY</th>
						<th style="width:70px;"><i class="fa fa-rupee-sign"></i> RATE</th>
						<th style="width:120px;" class="desktop-only"><i class="far fa-file-alt"></i> BILL NO</th>
						<th style="width:95px;" class="desktop-only"><i class="fas fa-truck-moving"></i> TRUCK</th>
						<th style="width:180px;"><i class="far fa-user"></i> CUSTOMER</th>
						<th class="desktop-only"><i class="far fa-comment-dots"></i> REMARKS</th>
						<th class="desktop-only"><i class="fas fa-map-marker-alt"></i> ADDRESS</th>
					</tr>	
				</thead>
				<tbody>	<?php
					foreach($mainMap as $index => $sale) 
					{
						$date = $productDateMap[$sale['product']][closestDate($productDateMap[$sale['product']],strtotime($sale['date']))];
						$date = date('Y-m-d',$date);
						
						if(isset($rateMap[$sale['product']][$date]))
							$rate = $rateMap[$sale['product']][$date];
						else
							$rate = 0;
						
						if(isset($cdMap[$sale['product']][$sale['client']][$sale['date']]))
							$cd = $cdMap[$sale['product']][$sale['client']][$sale['date']];
						else
							$cd = 0;
						
						if(isset($wdMap[$sale['product']][$sale['date']]))
							$wd = $wdMap[$sale['product']][$sale['date']];
						else
							$wd = 0;
						
						$finalRate = $rate - $cd - $wd - $sale['discount'];																					?>	
						
						<tr data-id="<?php echo $sale['id'];?>" data-params="<?php echo explode('?',$_SERVER['REQUEST_URI'])[1];?>" class="saleId" style="cursor:pointer;">
							<td data-title="Date"><?php echo date('d-m-Y',strtotime($sale['date'])); ?></td>
							<td data-title="AR"><?php echo $clientNamesMap[$sale['client']]; ?></td>
							<td data-title="Product"><?php echo $productNamesMap[$sale['product']];?></td>
							<td data-title="Qty"><?php echo $sale['qty']; ?></td>
							<td data-title="Rate"><?php if($finalRate > 0 ) echo $finalRate.'/-';?></td>							
							<td data-title="Bill" class="desktop-only"><?php echo $sale['bill']; ?></td>
							<td data-title="Truck" class="desktop-only"><?php if(isset($truckNumbersMap[$sale['truck']])) echo $truckNumbersMap[$sale['truck']]; ?></td>
							<td data-title="Cusomer"><?php echo $sale['name'].'<br/><font class="desktop-only">'.$sale['phone'].'</font>'; ?></td>
							<td data-title="Remarks" class="desktop-only"><?php echo $sale['remarks']; ?></td>
							<td data-title="Address" class="desktop-only"><?php echo $sale['address']; ?></td>
						</tr>																																		<?php				
					}																																				?>
				</tbody>	
			</table>
			<br/><br/><br/>
		</div>
		<script src="list.js"></script>
		<script src="newModal.js"></script>
		<script>	
			var shopNameList = '<?php echo $shopNameArray;?>';
			var shopName_array = JSON.parse(shopNameList);
			var shopNameArray = shopName_array;											
		</script>
	</body>
</html>																																					<?php
}
else
	header("Location:../index/home.php");																													?>