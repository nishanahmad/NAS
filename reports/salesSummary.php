<?php
session_start();
if(isset($_SESSION["user_name"]))
{
	require '../connect.php';
  
	if(isset($_GET['from']))
		$fromDate = date("Y-m-d", strtotime($_GET['from']));		
	else
		$fromDate = date("Y-m-d");		

	if(isset($_GET['to']))		
		$toDate = date("Y-m-d", strtotime($_GET['to']));		
	else
		$toDate = date("Y-m-d");		
	
	if(isset($_GET['product']))		
		$product = (float)$_GET['product'];
	else
		$product = 'All';

	$products = mysqli_query($con, "SELECT * FROM products" ) or die(mysqli_error($con));	
	foreach($products as $pro)
	{
		$productMap[$pro['id']] = $pro['name'];
	}
	
	$arObjects = mysqli_query($con, "SELECT * FROM ar_details order by name ASC" ) or die(mysqli_error($con));	
	foreach($arObjects as $ar)
	{
		$arNameMap[$ar['id']] = $ar['name'];
		$arCodeMap[$ar['id']] = $ar['sap_code'];
		$arShopMap[$ar['id']] = $ar['shop_name'];
		$arPhoneMap[$ar['id']] = $ar['mobile'];
	}
	
	$tallyFlag = false;
	if($fromDate == $toDate && $product == 'All')
	{
		$tallyFlag = true;
		$tallyObjects = mysqli_query($con, "SELECT * FROM tally_day_check WHERE date = '$toDate'" ) or die(mysqli_error($con));
		foreach($tallyObjects as $tally)
			$tallyMap[$tally['ar']] = $tally['checked_by'];
	}
	
	$userObjects = mysqli_query($con, "SELECT * FROM users" ) or die(mysqli_error($con));
	foreach($userObjects as $user)
		$userMap[$user['user_id']] = $user['user_name'];	
		
	if($_POST)
	{
		header("Location:salesSummary.php?from=".$_POST['fromDate']."&to=".$_POST['toDate']."&product=".$_POST['product']);	
	}	
?>
<html>
<head>
	<title>AR Sale Date Wise</title>
	<link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" rel="stylesheet" type="text/css">
	<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/jquery.tablesorter.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/jquery.tablesorter.widgets.min.js"></script>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css" integrity="sha384-r4NyP46KrjDleawBgD5tp8Y7UzmLA05oM1iAEQ17CSuDqnUK2+k9luXQOfXJCJ4I" crossorigin="anonymous"/>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js" integrity="sha384-oesi62hOLfzrys4LxRF63OJCXdXDipiYWBnvTl9Y9/TRlw5xlKIEHpNyvvDShgf/" crossorigin="anonymous"></script>
	<script src="https://kit.fontawesome.com/742221945b.js" crossorigin="anonymous"></script>	
	<script>
	$(function() {
		var pickerOpts = { dateFormat:"dd-mm-yy"}; 
		$( "#fromDate" ).datepicker(pickerOpts);
		
		var pickerOpts2 = { dateFormat:"dd-mm-yy"}; 
		$( "#toDate" ).datepicker(pickerOpts2);		

	});
	
	$(document).ready(function() {	
		$(".maintable").tablesorter(); 
		var $table = $('.maintable');
	});		

	</script>	
    <style> 
        .header { 
            position: sticky; 
            top:0; 
        } 
        .container { 
            width: 600px; 
            height: 300px; 
            overflow: auto; 
        }     
		.green{
			font-weight:bold;
			font-style:italic;
			color:LimeGreen			
		}
	</style> 
	
</head>
<body>
<div align="center">
<br><br>
<a href="../index.php" class="link"><img alt='home' title='home' src='../images/home.png' width='50px' height='50px'/> </a> &nbsp;&nbsp;&nbsp;
<br><br><br><br>
<form method="post" action="" autocomplete="off">
	<div class="row" style="margin-left:25%">
		<div class="col col-md-3">
			<div class="input-group">
				<span class="input-group-text col-md-5"><i class="far fa-calendar-alt"></i>&nbsp;From</span>
				<input type="text" required name="fromDate" id="fromDate" class="form-control datepicker" autocomplete="off" value="<?php echo date('d-m-Y',strtotime($fromDate)); ?>">
			</div>
		</div>
		<div class="col col-md-3">
			<div class="input-group">
				<span class="input-group-text col-md-5"><i class="far fa-calendar-alt"></i>&nbsp;To</span>
				<input type="text" required name="toDate" id="toDate" class="form-control" value="<?php echo date('d-m-Y',strtotime($toDate)); ?>">
			</div>
		</div>
		<div class="col col-md-3">
			<div class="input-group">
				<span class="input-group-text col-md-5"><i class="fa fa-shield"></i>&nbsp;Product</span>
					<select name="product" id="product" required class="textarea">
						<option value="all">ALL</option>																<?php
						foreach($products as $pro) 
						{																								?>
							<option <?php if($product == $pro['id']) echo 'selected';?> value="<?php echo $pro['id'];?>"><?php echo $pro['name'];?></option>		<?php	
						}																								?>
					</select>					
			</div>
		</div>	
	</div>
	<br/>
	<div class="col col-md-2 offset-1">
		<div class="input-group">		
			<input type="submit" class="btn" style="background-color:#54698D;color:white;" value="Search">		
		</div>
	</div>			
	<br/><br/>
</form>
<br>
<table class="maintable table table-hover table-bordered" style="width:50%;margin-left:40px;">
<thead style="position: sticky;top: 0">
	<tr class="table-success">
		<th style="text-align:left;" class="header" scope="col"><i class="fa fa-map-o"></i> AR</th>
		<th style="text-align:left;" class="header" scope="col"><i class="fas fa-store"></i> Shop Name</th>	
		<th style="width:12%;" class="header" scope="col"><i class="fa fa-address-card-o"></i> SAP</th>	
		<th style="width:15%;" class="header" scope="col"><i class="fa fa-mobile"></i> Phone</th>
		<th style="width:12%;text-align:center" class="header" scope="col"><i class="fab fa-buffer"></i> Qty</th>					<?php
		if($tallyFlag == true)
		{																								?>
			<th class="header" scope="col">VerifiedBy</th>				<?php
		}																								?>
	</tr>
</thead>
<?php
	if($product == 'All')
		$salesList = mysqli_query($con, "SELECT ar_id,SUM(qty),SUM(return_bag) FROM nas_sale WHERE entry_date >= '$fromDate' AND entry_date <= '$toDate' GROUP BY ar_id" ) or die(mysqli_error($con));
	else
		$salesList = mysqli_query($con, "SELECT ar_id,product,SUM(qty),SUM(return_bag) FROM nas_sale WHERE entry_date >= '$fromDate' AND entry_date <= '$toDate' AND product = $product GROUP BY ar_id,product" ) or die(mysqli_error($con));
	$total = 0;
	foreach($salesList as $arSale)
	{
?>		<tr id="<?php echo $arNameMap[$arSale['ar_id']];?>">
			<td style="text-align:left;"><?php echo $arNameMap[$arSale['ar_id']];?></td>
			<td style="text-align:left;"><?php echo $arShopMap[$arSale['ar_id']];?></td>			
			<td><?php echo $arCodeMap[$arSale['ar_id']];?></td>			
			<td><?php echo $arPhoneMap[$arSale['ar_id']];?></td>						
			<td style="text-align:center"><b><?php echo $arSale['SUM(qty)'] - $arSale['SUM(return_bag)'];?></b></td>										<?php
			if($tallyFlag == true)
			{		
				if(isset($tallyMap[$arSale['ar_id']]))
				{		
					$userId = $tallyMap[$arSale['ar_id']];																									?>
					<td><font style="font-weight:bold;font-style:italic;"><?php echo $userMap[$userId];?></font></td>						<?php
				}
				else
				{																																			?>
					<td><button class="btn" value="<?php echo $arSale['ar_id'];?>" style="background-color:#E6717C;color:white;" onclick="callAjax(this.value)">Verify</button></td>																											<?php			
				}
			}																																				?>																																
		</tr>
<?php	
		$total = $total + $arSale['SUM(qty)'] - $arSale['SUM(return_bag)'];
	}
?>	
	<tbody class="tablesorter-no-sort">
		<tr style="line-height:50px;background-color:#BEBEBE !important;font-family: Arial Black;">
			<td colspan="4" style="text-align:right" >TOTAL</td>
			<td colspan="2"><?php echo $total;?></td>
		</tr>
	</tbody>
</table>
<br><br><br><br><br><br>
</div>
<script>
	function callAjax(ar){
		const queryString = window.location.search;
		const urlParams = new URLSearchParams(queryString);
		const date = urlParams.get('to')
		$.ajax({
			type: "POST",
			url: "ajax/updateTallyCheck.php",
			data:'ar='+ar +'&date='+date,
			success: function(response){
				if(response != false){
					$('#'+response).find('td').eq(5).text('VERIFIED!');
					$('#'+response).find('td').eq(5).addClass("green")
				}
				else{
					alert('Some error occured. Try again');
					location.reload();
				}
			}
		});	  
	}
</script>
</body>			
<?php
}
else
	header("Location:../index.php");	
?>