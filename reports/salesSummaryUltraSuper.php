<!DOCTYPE html>
<?php
session_start();
if(isset($_SESSION["user_name"]))
{
	require '../connect.php';
	require '../navbar.php';
  	
	if(isset($_GET['from']))
		$fromDate = date("Y-m-d", strtotime($_GET['from']));		
	else
		$fromDate = date("Y-m-d");		

	if(isset($_GET['to']))		
		$toDate = date("Y-m-d", strtotime($_GET['to']));		
	else
		$toDate = date("Y-m-d");		
	
	$products = mysqli_query($con, "SELECT * FROM products" ) or die(mysqli_error($con));	
	foreach($products as $pro)
	{
		$productMap[$pro['id']] = $pro['name'];
	}
	
	$ultraMap = array();
	$superMap = array();
	$arList = array();
	$salesQuery = mysqli_query($con, "SELECT ar_id,product,SUM(qty),SUM(return_bag) FROM nas_sale WHERE 
									deleted IS NULL AND 
									entry_date >= '$fromDate' AND 
									entry_date <= '$toDate'
									GROUP BY ar_id,product" ) or die(mysqli_error($con));		
	foreach($salesQuery as $sale)
	{
		//var_dump($sale);
		$arList[] = $sale['ar_id'];
		if($productMap[$sale['product']] == 'ULTRA')
			$ultraMap[$sale['ar_id']] = $sale['SUM(qty)'] - $sale['SUM(return_bag)'];
		if($productMap[$sale['product']] == 'ULTRA SUPER')
			$superMap[$sale['ar_id']] = $sale['SUM(qty)'] - $sale['SUM(return_bag)'];
	}

	
	$activeList = array();
	$arObjects = mysqli_query($con, "SELECT * FROM ar_details order by name ASC" ) or die(mysqli_error($con));	
	foreach($arObjects as $ar)
	{
		$arNameMap[$ar['id']] = $ar['name'];
		$arParentCodeMap[$ar['id']] = $ar['parent_code'];
		$arChildCodeMap[$ar['id']] = $ar['child_code'];
		$arUltraCodeMap[$ar['id']] = $ar['ultra_code'];
		$arShopMap[$ar['id']] = $ar['shop_name'];
		$arPhoneMap[$ar['id']] = $ar['mobile'];
		if($ar['status'] == 'Active')
			$activeList[] = $ar['id'];	
	}
	
	if (in_array(33290, $activeList))
		echo "INARRAY";
		
	$userObjects = mysqli_query($con, "SELECT * FROM users" ) or die(mysqli_error($con));
	foreach($userObjects as $user)
		$userMap[$user['user_id']] = $user['user_name'];	
		
	if($_POST)
	{
		$URL='salesSummaryUltra.php?from='.$_POST['fromDate'].'&to='.$_POST['toDate'];
		echo "<script type='text/javascript'>document.location.href='{$URL}';</script>";
		echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $URL . '">';				
	}	
?>
	<html>
		<head>
			<title>Ultra Report</title>
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
				var pickerOpts = { dateFormat:"dd-mm-yy"}; 
				$( "#fromDate" ).datepicker(pickerOpts);
				
				var pickerOpts2 = { dateFormat:"dd-mm-yy"}; 
				$( "#toDate" ).datepicker(pickerOpts2);		

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
							Ultra Report
						</button>
						<ul class="dropdown-menu" aria-labelledby="btnGroupDrop1" style="cursor:pointer">									
							<li><a href="salesSummary.php" class="dropdown-item">Summary Report</a></li>							
							<li><a href="truckReport.php" class="dropdown-item">Truck Report</a></li>							
							<li><a href="monthlyPriceSummary.php" class="dropdown-item">Price Report</a></li>							
						</ul>
					</div>
				</div>								
				<span class="navbar-brand" style="font-size:25px;margin-right:45%"><i class="fa fa-line-chart"></i> Ultra Report</span>
			</nav>
			<div style="width:100%;" class="mainbody">	
				<br/><br/>
				<div align="center">
					<form method="post" action="" autocomplete="off">
						<div class="row" style="margin-left:38.5%">
							<div style="width:220px;">
								<div class="input-group">
									<span class="input-group-text col-md-5"><i class="far fa-calendar-alt"></i>&nbsp;From</span>
									<input type="text" required name="fromDate" id="fromDate" class="form-control" autocomplete="off" value="<?php echo date('d-m-Y',strtotime($fromDate)); ?>">
								</div>
							</div>
							<div style="width:220px;">
								<div class="input-group">
									<span class="input-group-text col-md-5"><i class="far fa-calendar-alt"></i>&nbsp;To</span>
									<input type="text" required name="toDate" id="toDate" class="form-control" value="<?php echo date('d-m-Y',strtotime($toDate)); ?>">
								</div>
							</div>
						</div>
						<br/>
						<div class="justify-content-center">
							<input type="submit" class="btn" style="background-color:#54698D;color:white;" value="Search">		
						</div>			
					</form>																													
					<br/>
					<br/>
					<div class="col-md-8 table-responsive-sm">
					<table class="maintable table table-hover table-bordered table-responsive">
						<thead>
							<tr class="table-success">
								<th style="text-align:left;" class="header" scope="col"><i class="fas fa-store"></i> Shop Name</th>									
								<th style="text-align:left;" class="header" scope="col"> Ultra Code</th>
								<th style="width:15%;" class="header" scope="col"><i class="fa fa-mobile"></i> Phone</th>
								<th style="width:12%;text-align:center" class="header" scope="col"><i class="fab fa-buffer"></i> Ultra</th>
								<th style="width:12%;text-align:center" class="header" scope="col"><i class="fab fa-buffer"></i> Super</th>								
								<th style="width:12%;text-align:center" class="header" scope="col"><i class="fab fa-buffer"></i> Total</th>								
							</tr>
						</thead>																								
						<tbody class="tablesorter-no-sort">																		<?php
						$ultraTotal = 0;
						$superTotal = 0;
						foreach($arList as $ar)
						{
							if(isset($ultraMap[$ar]))
								$ultra = $ultraMap[$ar];
							else	
								$ultra = 0;
							
							if(isset($superMap[$ar]))
								$super = $superMap[$ar];
							else	
								$super = 0;							
							
							$ultraTotal = $ultraTotal + $ultra;	
							$superTotal = $superTotal + $super;																													?>
							
							<tr id="<?php echo $arNameMap[$ar];?>">
								<td style="text-align:left;"><?php echo $arShopMap[$ar];?></td>
								<td style="text-align:left;"><?php echo $arUltraCodeMap[$ar];?></td>
								<td><?php echo $arPhoneMap[$ar];?></td>
								<td style="text-align:center"><?php  echo $ultra;?></td>
								<td style="text-align:center"><?php echo $super;?></td>								
								<td style="text-align:center"><b><?php echo $ultra + $super;?></b></td>								
							</tr>																																				<?php	
						}																																						?>	
							<tr style="line-height:50px;background-color:#BEBEBE !important;font-family: Arial Black;">
								<td colspan="3" style="text-align:right" >TOTAL</td>
								<td style="text-align:center"><?php echo $ultraTotal;?></td>
								<td style="text-align:center"><?php echo $superTotal;?></td>
								<td style="text-align:center"><?php echo $ultraTotal + $superTotal;?></td>
							</tr>
						</tbody>
					</table>
					</div>
				</div>
				<br/><br/><br/>
			</div>
			<script>
				function callAjax(ar){
					const queryString = window.location.search;
					const urlParams = new URLSearchParams(queryString);
					var date = urlParams.get('to');
					if(!date)
					{
						date = new Date();
						var dd = String(date.getDate()).padStart(2, '0');
						var mm = String(date.getMonth() + 1).padStart(2, '0'); //January is 0!
						var yyyy = date.getFullYear();

						date = dd + '-' + mm + '-' + yyyy;
					}
					$.ajax({
						type: "POST",
						url: "ajax/updateDayTally.php",
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
	</html>																																											<?php
}
else
	header("Location:../index.php");	
?>