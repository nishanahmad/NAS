<!DOCTYPE html>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<?php
session_start();
if(isset($_SESSION["user_name"]))
{
	require '../connect.php';
	require '../navbar.php';
	require 'listHelper.php';
	
	$arNamesMap = getARNames($con);
	$shopNamesMap = getShopNames($con);
	$productDetailsMap = getProductDetails($con);
	$truckNumbersMap = getTruckNumbers($con);	
	
	$today = date('Y-m-d');
	$sales = mysqli_query($con,"SELECT * FROM nas_sale WHERE deleted IS NULL AND entry_date = '$today' ORDER BY product DESC,bill_no") or die(mysqli_error($con));
?>

<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="../css/styles.css" rel="stylesheet" type="text/css">	
<link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" rel="stylesheet" type="text/css">
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/jquery.tablesorter.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/jquery.tablesorter.widgets.min.js"></script>
<title>Covering Block</title>
</head>
<body>
<div id="main" class="main">
    <div class="container">
		<nav class="navbar navbar-light bg-light sticky-top bottom-nav">
			<span class="navbar-brand" style="font-size:25px;margin-left:47%;"><i class="fa fa-th"></i> Covering Block</span>
		</nav>		
		<div align="center">
		<br/><br/>
			<div id="content-desktop">
				<br/><br/>
				<table class="maintable table table-hover table-bordered" style="width:95%;margin-left:2%;">
					<thead>
						<tr class="table-success">
							<th style="width:20px;">Status</th>
							<th style="min-width:110px;"><i class="far fa-calendar-alt"></i> Date</th>
							<th style="width:250px;"><i class="fa fa-address-card-o"></i> AR</th>
							<th style="width:70px;"><i class="fa fa-shield"></i> PRO</th>
							<th style="width:70px;"><i class="fab fa-buffer"></i> QTY</th>
							<th style="width:95px;"><i class="fas fa-truck-moving"></i> TRUCK</th>
							<th style="width:180px;"><i class="far fa-user"></i> CUSTOMER</th>
							<th><i class="fas fa-map-marker-alt"></i> ADDRESS</th>
						</tr>	
					</thead>
					<tbody>	<?php
						foreach($sales as $sale) 
						{																																				?>	
							<tr>
								<td><div class="form-check">
										<input class="form-check-input check1" type="checkbox" id="check1" name="<?php echo $sale['sales_id'];?>" 
										<?php if($sale['coveringblock']) echo 'checked';?> />
									</div>
								</td>
								<td><?php echo date('d-m-Y',strtotime($sale['entry_date'])).' '; ?></td>
								<td><?php echo $arNamesMap[$sale['ar_id']];?><br/>
									<?php echo $shopNamesMap[$sale['ar_id']];?>
								</td>
								<td><?php echo $productDetailsMap[$sale['product']]['name'];?></td>
								<td><?php echo $sale['qty']; ?></td>
								<td><?php if(isset($truckNumbersMap[$sale['truck']])) echo $truckNumbersMap[$sale['truck']]; ?></td>
								<td><?php echo $sale['customer_name'].'<br/><font>'.$sale['customer_phone'].'</font>'; ?></td>
								<td><?php echo $sale['address1']; ?></td>
							</tr>																																		<?php				
						}																																				?>
					</tbody>	
				</table>
			</div>
		</div>
	</div>
	<br/><br/>
</div>
</body>
<script>
$(document).ready(function() {		
	$("table").tablesorter({
		dateFormat : "ddmmyyyy",
		theme : 'bootstrap',
		widgets: ['filter'],
		filter_columnAnyMatch: true
	}); 
} );


$('.check1').change(function () {
    if(this.checked)
		checked = 'True';
	else
		checked = 'False';
	
	var saleId = this.name;	

	$.ajax({
		type: "POST",
		url: "updateCoveringBlock.php",
		data:'checked='+checked + '&saleId='+saleId,
		success: function(response){
			if(response == false){
				alert('Some error occured. Try again');
				location.reload();
			}
		}
	});
 });
</script>
</html>																														<?php

}
else
	header("Location:../index/home.php");
