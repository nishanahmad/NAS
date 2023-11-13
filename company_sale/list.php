<!DOCTYPE html>
<?php
session_start();

if(isset($_SESSION["user_name"]))
{
	require '../connect.php';
	require '../navbar.php';																																
	
	if(isset($_GET['date']))
		$urlDate = date('d-m-Y',strtotime($_GET['date']));	
	else
		$urlDate = date('d-m-Y');
		
	$date = date('Y-m-d',strtotime($urlDate));

	$arMap = array();
	$arList = mysqli_query($con, "SELECT * FROM ar_details WHERE id IS NOT NULL") or die(mysqli_error($con));
	foreach($arList as $ar)
		$arMap[$ar['id']] = $ar;
		
	$productMap = array();
	$productList = mysqli_query($con, "SELECT * FROM products") or die(mysqli_error($con));
	foreach($productList as $product)
		$productMap[$product['id']] = $product['name'];		
				
	$sales = mysqli_query($con,"SELECT * FROM company_sale WHERE date = '$date'");		?>
<html>
	<head>
		<link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" rel="stylesheet" type="text/css">
		<link href="../css/styles.css" rel="stylesheet" type="text/css">
		<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/jquery.tablesorter.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/jquery.tablesorter.widgets.min.js" ></script>
		<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
		<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>	
		<title>Company Sale</title>
	</head>
	<body>
		<nav class="navbar navbar-light bg-light sticky-top bottom-nav">
			<div class="btn-group" role="group" style="float:left;margin-left:2%;">
				<div class="btn-group" role="group">
					<button id="btnGroupDrop1" type="button" class="btn btn-outline-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
						<i class="far fa-calendar-alt"></i> Sales List
					</button>
					<ul class="dropdown-menu" aria-labelledby="btnGroupDrop1" style="cursor:pointer">									
						<li id="uploadButton" onclick="window.location.href = 'upload.php'"><a class="dropdown-item">Upload Sales</a></li>
					</ul>
				</div>	
			</div>	
			<span class="navbar-brand" style="font-size:25px;margin-right:45%;"><i class="fa fa-bolt"></i> Company Sale</span>
		</nav>
		<div style="width:100%;" class="mainbody">
			<div align="center">
				<br/><br/>
				<input style="width:10%" type="text" name="date" id="date" onchange="document.location.href = 'list.php?date=' + this.value" class="form-control datepicker col-md-1" autocomplete="off" value="<?php echo date('d-m-Y',strtotime($urlDate)); ?>">
				<br/><br/>
				Total sale records : <div id="getCurrentRows"></div>
				<table id="salesTable" class="ratetable table table-hover table-bordered" style="width:50%">
					<thead>
						<tr class="table-info">
							<th><i class="fa fa-calendar"></i> Date</th>
							<th><i class="fa fa-address-card-o"></i> AR</th>
							<th>Child Code</th>
							<th><i class="fa fa-shield"></i> Product</th>
							<th style="width:90px;"><i class="fab fa-buffer"></i> Qty</th>
						</tr>
					</thead>
					<tbody><?php				
						foreach($sales as $sale)
						{																														?>
							<tr>
								<td><?php echo date('d-m-Y',strtotime($sale['date']));?></td>								
								<td><?php echo $arMap[$sale['ar_id']]['name'];?></td>
								<td><?php echo $arMap[$sale['ar_id']]['child_code'];?></td>
								<td><?php echo $productMap[$sale['product']];?></td>
								<td><?php echo $sale['qty'];?></td>
							</tr>																											<?php
						}																														?>
					</tbody>																														
				</table>
			</div>
			<br/><br/><br/>
		</div>
		<script src="list.js"></script>
	</body>
</html>																																					<?php
}
else
	header("Location:../index.php");																													?>