<!DOCTYPE html>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<?php
session_start();
if(isset($_SESSION["user_name"]))
{
	require '../connect.php';
	require '../functions/monthMap.php';
	require '../navbar.php';
	
	$year = $_GET['year'];
	$month = $_GET['month'];

	$arObjects = mysqli_query($con, "SELECT * FROM ar_details WHERE isActive = 1 AND Type LIKE '%AR%' ORDER BY name ASC") or die(mysqli_error($con));
	foreach($arObjects as $ar)
	{
		$arMap[$ar['id']] = $ar['name'];
		$shopMap[$ar['id']] = $ar['shop_name'];
		$codeMap[$ar['id']] = $ar['sap_code'];
		$phoneMap[$ar['id']] = $ar['mobile'];
	}	
	
	$array = implode("','",array_keys($arMap));	
	
	$sql = "SELECT ar_id, target, rate, payment_perc, company_target FROM target WHERE year='$year' AND Month='$month' AND ar_id IN ('$array')";
	$result = mysqli_query($con, $sql) or die(mysqli_error($con));		

	$yearObjects = mysqli_query($con,"SELECT DISTINCT year FROM target ORDER BY year DESC");	
	foreach($yearObjects as $yearObj)
	{
		$yearList[] = (int)$yearObj['year'];
		$newYear =  $yearObj['year'] + 1;
	}
?>

<html>
<script type="text/javascript">
function rerender()
{
	var year = document.getElementById("jsYear").options[document.getElementById("jsYear").selectedIndex].value;

	var month=document.getElementById("jsMonth").value;

	var hrf = window.location.href;
	hrf = hrf.slice(0,hrf.indexOf("?"));

	window.location.href = hrf +"?year="+ year + "&month=" + month;
}
</script>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="../css/styles.css" rel="stylesheet" type="text/css">
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/jquery.tablesorter.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/jquery.tablesorter.widgets.min.js" ></script>
<title><?php echo getMonth($month);?> Target</title>
</head>
<body>
	<div style="width:100%;">
	<div align="center" style="padding-bottom:5px;">
	<br><br>
	<font size="5px"><b><?php echo $year;?></b></font>
	<br>

	<select id="jsYear" name="jsYear" onchange="return rerender();">																				<?php
	foreach($yearList as $yearIterator)
	{																																			?>
		<option  <?php if($yearIterator == $year) echo 'selected';?> value="<?php echo $yearIterator;?>"> <?php echo $yearIterator;?> </option>															<?php 
	}																																				?>
	</select>
	<select id="jsMonth" name="jsMonth" onchange="return rerender();">																				<?php
	for($i=1; $i<=12; $i++)
	{																																				?>
		<option value="<?php echo $i;?>" <?php if($month == $i) echo 'selected';?>><?php echo getMonth($i);?></option>								<?php
	}																																				?>
	</select>
	</div>
	<br><br>
	<form name="arBulkUpdate" method="post" action="updateServer.php">
		<table class="table table-hover table-bordered offset-2" style="width:70%">
			<thead>
				<tr class="table-success">
					<th style="width:20%">AR NAME</th>
					<th style="width:30%">SHOP</th>
					<th style="width:30%">MOBILE</th>
					<th style="width:10%">SAP</th>
					<th style="width:10%;text-align:center;">TARGET</th>
					<th style="width:10%;text-align:center;">RATE</th>
					<!--th style="width:10%;text-align:center;">PAYMENT %</th--> 
				</tr>	
			</thead>
			<tbody>	<?php
				$total = 0;	
				while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) 
				{
					$arId = $row['ar_id'];
					$target = $row['target'];
					$total = $total + $target;
					$rate = $row['rate'];
					$pp = $row['payment_perc'];
					$company_target = $row['company_target'];

					?>				
					<tr>
						<td><label align="center"><?php echo $arMap[$arId]; ?></td>	
						<td><label align="center"><?php echo $shopMap[$arId]; ?></td>	
						<td><label align="center"><?php echo $phoneMap[$arId]; ?></td>
						<td><label align="center"><?php echo $codeMap[$arId]; ?></td>						
						<td><?php echo $target; ?></td>	
						<td><?php echo $rate; ?></td>	
						<!--td style="text-align:center;"><input type="text" style="text-align:center;width:70px;border:0px;background-color: transparent;" name="<?php //echo $arId.'-target';?>" value="<?php //echo $target; ?>"></td>	
						<td style="text-align:center;"><input type="text" style="text-align:center;width:70px;border:0px;background-color: transparent;" name="<?php //echo $arId.'-rate';?>" value="<?php //echo $rate; ?>"></td-->
						<!--td style="text-align:center;"><input type="text" style="text-align:center;width:70px;border:0px;background-color: transparent;" name="<?php //echo $arId.'-pp';?>" value="<?php //echo $pp; ?>"></td-->		
					</tr>																												<?php
				}																														?>
				<tr>
					<th></th>
					<th></th>
					<th></th>
					<th><?php echo $total;?></th>
					<th></th>
					<th></th>
				</tr>
			</tbody>
		</table>
		<input type="hidden" name="year" value="<?php echo $year;?>">
		<input type="hidden" name="month" value="<?php echo $month;?>">		
	<br><br>
		<div align="center"><input type="submit" name="submit" value="Submit" onclick="return confirm('Are you sure you want to update?')"></div>		
	<br><br> 
	</div> 
</form>
</body>
<script>
$(function(){		
	$(".download-table").tablesorter({
		theme : 'bootstrap',
		widgets: ['filter'],
		filter_columnAnyMatch: true
	});	
});
</script>
</html>																								<?php
}
else
	header("Location:../index.php");
