<!DOCTYPE html>
<?php
session_start();
if(isset($_SESSION["user_name"]))
{
	require '../connect.php';

	$urlId = $_GET['ar'];
	
	$products = mysqli_query($con,"SELECT id,name FROM products WHERE status = 1 ORDER BY id ASC");
	foreach($products as $product)
	{
		$productMap[$product['id']] = $product['name'];
	}
	
	$arObjects = mysqli_query($con,"SELECT id,name FROM ar_details ORDER BY name ASC") or die(mysqli_error($con));	
	foreach($arObjects as $ar)
	{
		$arMap[$ar['id']] = $ar['name']; 
	}

	if($_GET['ar'] != 'all')
		$result = mysqli_query($con,"SELECT * FROM nas_sale WHERE ar_id='" . $_GET['ar'] . "' and entry_date = CURDATE() order by bill_no asc  ") or die(mysqli_error($con));
	else
		$result = mysqli_query($con,"SELECT * FROM nas_sale WHERE entry_date = CURDATE() order by bill_no asc  ") or die(mysqli_error($con));
	
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
	<div style="width:100%;">
	<div align="center" style="padding-bottom:5px;">
		<a href="../index.php" class="link"><img alt='home' title='home' src='../images/home.png' width='60px' height='60px'/> </a> &nbsp;&nbsp;&nbsp;
		<a href="new.php" class="link"><img alt='Add' title='Add New' src='../images/addnew.png' width='60px' height='60px'/></a>
	</div>
	<br>
	<div align="center">
		<select name="ar" id="ar" onchange="document.location.href = 'todayList.php?ar=' + this.value" class="txtField">
			<option value = "all" <?php if($urlId == 'all') echo 'selected';?> >ALL</option>													    	<?php
			foreach($arMap as $arId => $arName)
			{																																			?>
				<option value="<?php echo $arId;?>" <?php if($urlId == $arId) echo 'selected';?>><?php echo $arName;?></option> 						<?php
			}																																			?>
		</select>
			  
		<h3> Date :  <?php echo date("d-m-Y") ?></h3>	  
	</div>	  

	<br>
	<div align="center">
	<table width="20%;" class="rateTable">																											<?php
		$total = 0;
		$sumQuery = mysqli_query($con,"SELECT product,SUM(qty) FROM nas_sale WHERE entry_date = CURDATE() GROUP BY product  ") or die(mysqli_error($con));	
		foreach($sumQuery as $sum)
		{	
			$total = $total + $sum['SUM(qty)'];																												?>	
			<tr>
				<td><?php echo $productMap[$sum['product']];?></td>
				<td><?php echo $sum['SUM(qty)'];?></td>				
			</tr>																																<?php				
		}																																	?>
		<tr>
			<th>Total</th>
			<th><?php echo $total;?></th>
		</tr>
	</table>
	<br/><br/>
	<table width="98%" class="table-responsive">
		<tr class="tableheader">
			<th>AR</th>
			<th width="50px">PRODUCT</th>
			<th width="50px;">QTY</th>
			<th>BILL NO</th>
			<th class="desktop">TRUCK NO</th>
			<th>CUST. NAME</th>
			<th class="desktop">CUST. PHONE</th>
			<th>REMARKS</th>
			<th class="desktop">ADDRESS1</th>
			<th class="desktop">ADDRESS2</th>
		</tr>
		<?php
			while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) 
			{																																?>
				<tr>
					<td ><a href="edit.php?sales_id=<?php echo $row['sales_id'];?>"</a><?php echo $arMap[$row["ar_id"]]; ?></td>
					<td align="center"><?php echo $productMap[$row["product"]]; ?></td>
					<td align="center"><?php echo $row["qty"]; ?></td>
					<td><?php echo $row["bill_no"]; ?></td>
					<td class="desktop"><?php echo $row["truck_no"]; ?></td>
					<td><?php echo $row["customer_name"]; ?></td>
					<td class="desktop"><?php echo $row["customer_phone"]; ?></td>
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

