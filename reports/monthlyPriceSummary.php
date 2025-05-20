<!DOCTYPE html>
<?php
session_start();
if(isset($_SESSION["user_name"]))
{
	require '../connect.php';
	require '../navbar.php';
	require '../functions/monthMap.php';

	$products = mysqli_query($con, "SELECT * FROM products WHERE name = 'ULTRA' OR name='ULTRA SUPER'" ) or die(mysqli_error($con));	
	foreach($products as $pro)
	{
		$productMap[$pro['id']] = $pro['name'];
	}
  		
	if(isset($_GET['product']))		
		$product = (float)$_GET['product'];
	else
		$product = 10;
	
	$rateList = mysqli_query($con, "SELECT date,rate FROM rate WHERE product = $product ORDER BY date" ) or die(mysqli_error($con));	
	foreach($rateList as $rate)
		$rateMap[$rate['date']] = $rate['rate'];

	
	foreach($rateMap as $date => $rate)
	{
		for($i=1;$i<1000;$i++)
		{
			$nextDay = date('Y-m-d', strtotime($date. ' + '.$i.' days'));
			if (array_key_exists($nextDay,$rateMap))
				break;
			else
				$rateMap[$nextDay] = $rate;			
		}
	}
	
	//var_dump($rateMap);
	
	$monthlyQty = array();
	$monthlyPrice = array();
	$salesList = mysqli_query($con, "SELECT sales_id,entry_date,qty,return_bag,discount,order_no,freight,MONTH(entry_date) FROM nas_sale WHERE 
									 deleted IS NULL AND product = $product AND YEAR(entry_date) = 2025" ) or die(mysqli_error($con));	
	foreach($salesList as $sale)
	{
		$month = $sale['MONTH(entry_date)'];
		$qty = $sale['qty'] - $sale['return_bag']; 
		$rate = $rateMap[$sale['entry_date']];
		$discount = $sale['discount'];
		$freight = $sale['order_no'] + $sale['freight'];
		$price = ($rate * $qty) - ($discount * $qty) - $freight;
		
		if(isset($monthlyQty[$month]))
		{
			$monthlyQty[$month] = $monthlyQty[$month] + $qty;
			$monthlyPrice[$month] = $monthlyPrice[$month] + $price;
		}
		else
		{
			$monthlyQty[$month] = $qty;
			$monthlyPrice[$month] = $price;			
		}
	}
	if($_POST)
	{
		/*
		$URL='priceReport.php?year='.$_POST['year']';
		echo "<script type='text/javascript'>document.location.href='{$URL}';</script>";
		echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $URL . '">';				
		*/
	}	
?>
	<html>
		<head>
			<title>Monthly Price Report</title>
			<meta name="viewport" content="width=device-width, initial-scale=1">
			<link href="../css/styles.css" rel="stylesheet" type="text/css">
			<link href="../css/navbarMobile.css" media="screen and (max-device-width: 768px)" rel="stylesheet" type="text/css">
			<link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" rel="stylesheet" type="text/css">
			<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
			<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
			<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
			<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/jquery.tablesorter.min.js"></script>
			<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/jquery.tablesorter.widgets.min.js"></script>
			<script>
			$(function() {
				$(".maintable").tablesorter();
				var $table = $('.maintable');
			});
			</script>	
			<style> 
				.green{
					font-weight:bold;
					font-style:italic;
					color:LimeGreen			
				}
			</style> 
			
		</head>
		<body>
			<nav class="navbar navbar-light bg-light sticky-top bottom-nav">
				<div class="btn-group" role="group" style="float:left;margin-left:2%;">
					<div class="btn-group" role="group">
						<button id="btnGroupDrop1" type="button" class="btn btn-outline-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
							Monthly Price Report
						</button>
						<ul class="dropdown-menu" aria-labelledby="btnGroupDrop1" style="cursor:pointer">									
							<li><a href="salesSummary.php" class="dropdown-item">Summary Report</a></li>							
							<li><a href="truckReport.php" class="dropdown-item">Truck Report</a></li>							
						</ul>
					</div>
				</div>								
				<span class="navbar-brand" style="font-size:25px;margin-right:45%"><i class="fa fa-line-chart"></i> Monthly Price Report</span>
			</nav>
			<div style="width:100%;" class="mainbody">	
				<br/><br/>
				<div align="center">
					<br/>
					<div class="col-md-8 table-responsive-sm">
					<table class="table">
					  <thead>
						<tr>
						  <th scope="col">Month</th>
						  <th scope="col">Price</th>
						</tr>
					  </thead>
					  <tbody>
						<?php foreach($monthlyPrice as $month => $price)
						{?>
							<tr>
							  <td><?php echo getMonth($month);?></td>
							  <td><?php echo round($price/$monthlyQty[$month],1);?></td>
							</tr><?php
						}?>
					  </tbody>
					</table>

					</div>
				</div>
				<br/><br/><br/>
			</div>
		</body>	
	</html>																																											<?php
}
else
	header("Location:../index.php");	
?>