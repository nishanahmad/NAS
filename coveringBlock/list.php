<!DOCTYPE html>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<?php
session_start();
if(isset($_SESSION["user_name"]))
{
	require '../connect.php';
	require '../navbar.php';
	require 'listHelper.php';
	require 'viewModal.php';
	
	$arNamesMap = getARNames($con);
	$shopNamesMap = getShopNames($con);
	$productDetailsMap = getProductDetails($con);
	$truckNumbersMap = getTruckNumbers($con);	
	
	$today = date('Y-m-d');
	$tomorrow = date('Y-m-d', strtotime(' +1 day'));

	$saleNumbers = array();
	$sheetShops = array();
	$sheetMasons = array();
	
	$sales = mysqli_query($con,"SELECT * FROM nas_sale WHERE deleted IS NULL AND entry_date = '$today' AND product = 6 ORDER BY bill_no") or die(mysqli_error($con));
	foreach($sales as $sale)
	{
		$saleShops[$sale['sales_id']] = $sale['ar_id'];
		if(!empty($sale['customer_phone']))
			$saleNumbers[$sale['sales_id']] = trim($sale['customer_phone']);
	}
	
	$sheets = mysqli_query($con,"SELECT * FROM sheets WHERE coveringBlock = 1 AND (date = '$today' OR date = '$tomorrow') AND ignore_dup = 0 AND status != 'cancelled'") or die(mysqli_error($con));
	foreach($sheets as $sheet)
	{
		if($sheet['shop1'] > 0)
			$sheetShops[$sheet['id']] = $sheet['shop1'];
		if(!empty($sheet['customer_phone']))
			$sheetCustomers[$sheet['id']] = trim($sheet['customer_phone']);
		if(!empty($sheet['mason_phone']))
			$sheetMasons[$sheet['id']] = trim($sheet['mason_phone']);
	}
	
	$duplicateShops = array_intersect($saleShops,$sheetShops);
	$duplicateCustomers = array_intersect($saleNumbers,$sheetCustomers);
	$duplicateMasons = array_intersect($saleNumbers,$sheetMasons);
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
<style>
    input{
        border: none;
    }
</style>

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
							<th></th>
							<th style="width:20px;">Status</th>
							<th style="min-width:110px;"><i class="far fa-calendar-alt"></i> Date</th>
							<th style="width:250px;"><i class="fa fa-address-card-o"></i> AR</th>
							<th style="width:70px;"><i class="fa fa-shield"></i> PRO</th>
							<th style="width:70px;"><i class="fab fa-buffer"></i> QTY</th>
							<th style="width:120px;"><i class="far fa-file-alt"></i> BILL NO</th>
							<th style="width:95px;"><i class="fas fa-truck-moving"></i> TRUCK</th>
							<th style="width:180px;"><i class="far fa-user"></i> CUSTOMER</th>
							<th><i class="fas fa-map-marker-alt"></i> ADDRESS</th>
						</tr>	
					</thead>
					<tbody>	<?php
						foreach($sales as $sale) 
						{																																				?>	
							<tr>
								<td style="text-align:center">																																	<?php 
									if(array_key_exists($sale['sales_id'],$duplicateCustomers) || array_key_exists($sale['sales_id'],$duplicateMasons))
									{																																	?>
										<button class="btn viewDuplicate"  style="color:#FFFFFF;background-color:#BA0517" data-id="<?php echo $sale['sales_id'];?>"><i class="fas fa-clone"></i>&nbsp;Duplicate?</button><?php
									}
									else if(array_key_exists($sale['sales_id'],$duplicateShops))
									{																														?>
										<button class="btn viewDuplicate"  style="color:#FFFFFF;background-color:#F88960" data-id="<?php echo $sale['sales_id'];?>"><i class="fas fa-clone"></i>&nbsp;Duplicate?</button><?php
									}																																							?>
								</td>
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
								<td><?php echo $sale['bill_no']; ?></td>
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

$('.viewDuplicate').click(function () {
	var saleId = $(this).data('id');
	$.ajax({
		type: "POST",
		url: "getDetails.php",
		data:'saleId='+saleId,
		success: function(response){
			if(response.status == true){
				console.log(response);
				$("#sheet_id").val(response.sheet_id);
				$("#area").val(response.area);
				$("#driver_area").val(response.driver_area);
				$("#customer_name").val(response.customer_name);
				$("#customer_phone").val(response.customer_phone);
				$("#mason_name").val(response.mason_name);
				$("#mason_phone").val(response.mason_phone);
				$("#sheet_date").val(response.sheet_date);
				$("#bags").val(response.bags);
				$("#shop").val(response.shop);
				$("#requested_by").val(response.requested_by);
				$("#created_on").val(response.created_on);
				$("#remarks").val(response.remarks);
				$("#assigned_to").val(response.assigned_to);
				
				if(response.priority == true)
					$("#priority").show();
				else
					$("#priority").hide();
					
				var viewModal = new bootstrap.Modal(document.getElementById('viewModal'), {})
				viewModal.show();				
			}
			else{
				console.log(response);
				alert('Some error occured. Try again');
				location.reload();				
			}
		},
		error: function (jqXHR, exception) {
			var msg = '';
			if (jqXHR.status === 0) {
				msg = 'Not connect.\n Verify Network.';
			} else if (jqXHR.status == 404) {
				msg = 'Requested page not found. [404]';
			} else if (jqXHR.status == 500) {
				msg = 'Internal Server Error [500].';
			} else if (exception === 'parsererror') {
				msg = 'Requested JSON parse failed.';
			} else if (exception === 'timeout') {
				msg = 'Time out error.';
			} else if (exception === 'abort') {
				msg = 'Ajax request aborted.';
			} else {
				msg = 'Uncaught Error.\n' + jqXHR.responseText;
			}
			console.log(jqXHR.responseText);
			return false;
		}								
	});	
 });

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
