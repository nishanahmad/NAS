<!DOCTYPE html>
<?php
session_start();
if(isset($_SESSION["user_name"]))
{
	require '../connect.php';
	require '../functions/rate.php';

	$urlId = $_GET['ar'];
	
	$products = mysqli_query($con,"SELECT id,name FROM products WHERE status = 1 ORDER BY id ASC");
	foreach($products as $product)
	{
		$productNameMap[$product['id']] = $product['name'];
	}
	
	$arObjects = mysqli_query($con,"SELECT id,name,type FROM ar_details ORDER BY name ASC") or die(mysqli_error($con));	
	foreach($arObjects as $ar)
	{
		$arMap[$ar['id']]['name'] = $ar['name']; 
		$arMap[$ar['id']]['type'] = $ar['type']; 
	}

	if($_GET['ar'] != 'all')
		$result = mysqli_query($con,"SELECT * FROM nas_sale WHERE ar_id='" . $_GET['ar'] . "' and entry_date = CURDATE() order by bill_no asc  ") or die(mysqli_error($con));
	else
		$result = mysqli_query($con,"SELECT * FROM nas_sale WHERE entry_date = CURDATE() order by bill_no asc  ") or die(mysqli_error($con));
	
	$rates = mysqli_query($con,"SELECT * FROM rate INNER JOIN products ON rate.product = products.id ORDER BY date DESC,products.name ASC") or die(mysqli_error($con));
	foreach($rates as $rate)
	{
		if(!isset($rateMap[$rate['product']]))
			$rateMap[$rate['product']] = $rate['rate'];
	}	
	
	$companyRateMap = array();
	$companyRates = mysqli_query($con,"SELECT * FROM company_rate INNER JOIN products ON company_rate.product = products.id ORDER BY date DESC,products.name ASC") or die(mysqli_error($con));
	foreach($companyRates as $cRate)
	{
		if(!isset($companyRateMap[$cRate['product']]))
		{
			$companyRateMap[$cRate['product']]['rate'] = $cRate['rate'];
			$companyRateMap[$cRate['product']]['recommended'] = $cRate['recommended'];
		}
	}		
	
	$discountMap = array();
	$discounts = mysqli_query($con,"SELECT * FROM discounts WHERE type = 'wd' AND date = CURDATE()") or die(mysqli_error($con));
	foreach($discounts as $discount)
	{
		$discountMap[$discount['product']] = $discount['amount'];
	}	

	$i=0;
	$productMap = array();
	$mainMap = array();
	$total = 0;
	while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) 
	{ 
		$productId = $row['product'];
		if($productId == 2)
		{
			var_dump($row);
		}
		if (array_key_exists($productId,$productMap))
		{   
			$productMap[$productId] = $productMap[$productId] + $row["qty"];
			$total = $total + $row["qty"];
		}	
		else
		{
			$productMap[$productId] = $row["qty"];
			$total = $total + $row["qty"];
		}	

		$mainMap[$i]['sales_id'] = $row['sales_id'];
		$mainMap[$i]['entry_date'] = $row['entry_date'];
		$mainMap[$i]['ar_id'] = $row['ar_id'];
		$mainMap[$i]['truck'] = $row['truck_no'];
		$mainMap[$i]['product'] = $productNameMap[$row['product']];
		$mainMap[$i]['productId'] = $row['product'];
		$mainMap[$i]['qty'] = $row['qty'];
		$mainMap[$i]['discount'] = $row['discount'];
		$mainMap[$i]['bill'] = $row['bill_no'];
		$mainMap[$i]['name'] = $row['customer_name'];
		$mainMap[$i]['phone'] = $row['customer_phone'];
		$mainMap[$i]['remarks'] = $row['remarks'];
		$mainMap[$i]['address1'] = $row['address1'];
		$mainMap[$i]['address2'] = $row['address2'];
		
		$i++;
	}	
?>

<html>
<head>
	<title>Today Sales List</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css" />
	<link rel="stylesheet" type="text/css" href="../css/styles.css" />
	<style>
		@media only screen and (max-width: 900px) {
			.desktop{
				display: none;
			}	

		.rateTable{
			width:50%;
			border-collapse:collapse;
		}
		.rateTable th{
			padding: 5px;
			color : #000000;
		}
		.rateTable td{
			padding: 5px;
			color : #000000;
		}	
	</style>
</head>
<body>
<form name="frmsales" method="post" action="" >
	<div align="center" style="padding-bottom:5px;">
		<a href="../index.php" class="link"><img alt='home' title='home' src='../images/home.png' width='60px' height='60px'/> </a> &nbsp;&nbsp;&nbsp;
		<a href="new.php" class="link"><img alt='Add' title='Add New' src='../images/addnew.png' width='60px' height='60px'/></a>
	</div>
	<br>
	<div align="center">
		<select name="ar" id="ar" onchange="document.location.href = 'todayList.php?ar=' + this.value" class="txtField">
			<option value = "all" <?php if($urlId == 'all') echo 'selected';?> >ALL</option>													    	<?php
			foreach($arMap as $arId => $ar)
			{																																			?>
				<option value="<?php echo $arId;?>" <?php if($urlId == $arId) echo 'selected';?>><?php echo $ar['name'];?></option> 						<?php
			}																																			?>
		</select>
			  
		<h3> Date :  <?php echo date("d-m-Y") ?></h3>	  
	</div>	  

	<br>
	<div align="center">
		<table class="rateTable" width="50%">
			<tr>
				<th>Product</th>			
				<th>Rate</th>
				<th style="width:20%;">Discount</th>									
				<th>Qty</th>
				<th style="border-color:white black white black;width:7%;"></th>
				<th style="width:15%;">Company Rate</th>
				<th style="width:15%;">Company Recommended</th>					
			</tr>			
		<?php				
		foreach($rateMap as $product=>$rate)
		{																																		?>
			<tr>
				<td><?php echo $productNameMap[$product];?></td>			
				<td><?php echo $rate.'/-';?></td>						
				<td><?php if(isset($discountMap[$product])) echo $discountMap[$product].'/-';?></td>										
				<td><?php if(isset($productMap[$product])) echo $productMap[$product];?></td>						
				<td style="border-color:white black white black;"></td>										<?php 
				if(isset($companyRateMap[$product]))
				{																							?>
					<td> <?php echo $companyRateMap[$product]['rate'].'/-';?></td>						
					<td><?php echo $companyRateMap[$product]['recommended'].'/-';?></td>					<?php						
				}																							?>

			</tr>
							<?php
		}?> 
			<tr>
				<th colspan="3">Total</th>
				<th><?php echo $total;?></th>
				<th style="border-color:white white white white;"></th>
				<th style="border-color:white white white white;"></th>
				<th style="border-color:white white white white;"></th>
			</tr>
		</table>
	<br/><br/>
	<table width="98%" class="table-responsive">
		<tr class="tableheader">
			<th>AR</th>
			<th width="60px">Rate</th>
			<th width="50px">Product</th>
			<th width="50px;">Qty</th>
			<th>Bill</th>
			<th class="desktop">Truck</th>
			<th>Cust Name</th>
			<th class="desktop">Cust Phone</th>
			<th>Remarks</th>
			<th class="desktop">Address1</th>
			<th class="desktop">Address2</th>
		</tr>		
		<?php			
			$rateMap = array();
			$wdMap = array();
			foreach($productMap as $id => $qty)
			{
				$rateMap[$id] = getRate(date('Y-m-d'),$id);
				$wdMap[$id] = getWD(date('Y-m-d'),$id);
			}
								
			foreach($mainMap as $row) 
			{
				$rowRate = $rateMap[$row['productId']];
				if($rowRate == null)
					$rowRate = 0;					
				
				if($arMap[$row['ar_id']]['type'] == 'AR/SR')
				{
					$rowWD = $wdMap[$row['productId']];
					if($rowWD == null)
						$rowWD = 0;							
				}
				else
					$rowWD = 0;							
			
				$rowCD = getCD($row['entry_date'],$row['productId'],$row['ar_id']);
				if($rowCD == null)
					$rowCD = 0;					
				
				$rowSD = getSD($row['entry_date'],$row['productId'],$row['ar_id']);
				if($rowSD == null)
					$rowSD = 0;										

				$finalRate = $rowRate - $rowWD - $rowCD - $rowSD -$row['discount'];																											?>			
				<tr>
					<td ><a href="edit.php?sales_id=<?php echo $row['sales_id'];?>"</a><?php echo $arMap[$row["ar_id"]]['name']; ?></td>
					<td align="center"><?php echo $finalRate.'/-'; ?></td>
					<td align="center"><?php echo $row["product"]; ?></td>
					<td align="center"><?php echo $row["qty"]; ?></td>
					<td><?php echo $row["bill"]; ?></td>
					<td class="desktop"><?php echo $row["truck"]; ?></td>
					<td><?php echo $row["name"]; ?></td>
					<td class="desktop"><?php echo $row["phone"]; ?></td>
					<td><?php echo $row["remarks"]; ?></td>
					<td class="desktop"><?php echo $row["address1"]; ?></td>
					<td class="desktop"><?php echo $row["address2"]; ?></td>
				</tr>																														<?php
			}																													?>
	</table>
</form>
<br><br>
</div>
</body>
</html>
<?php
}

else
	header("Location:../index.php");

