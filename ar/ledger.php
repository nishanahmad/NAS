<!DOCTYPE html>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<?php
session_start();
if(isset($_SESSION["user_name"]))
{
	require '../connect.php';
	require '../functions/monthMap.php';
	require '../functions/targetFormula.php';

	if(isset($_GET['id']))
		$urlId = $_GET['id'];
	else
		$urlId = 1;
	
	if(isset($_GET['year']))
		$urlYear = $_GET['year'];
	else
		$urlYear = date("Y");
	
	$arMap = array();
	$arList = mysqli_query($con, "SELECT id,name,isActive FROM ar_details ORDER BY name ASC" ) or die(mysqli_error($con));		
	foreach($arList as $ar)
	{
		if($ar['id'] == $urlId)
		{
			$arName = $ar['name'];
			$isActive = $ar['isActive'];
		}
	}
	$yearList = mysqli_query($con, "SELECT DISTINCT YEAR(entry_date) FROM nas_sale ORDER BY entry_date DESC" ) or die(mysqli_error($con));
	foreach($yearList as $year) 
	{
		$yearMap[] = $year['YEAR(entry_date)'];
	}	
	
	$targetMap = array();
	$targetObjects = mysqli_query($con,"SELECT month, target, payment_perc,rate FROM target WHERE Year='$urlYear' AND ar_id = '$urlId' ") or die(mysqli_error($con));		 
	foreach($targetObjects as $target)
	{
		$targetMap[$target['month']]['target'] = $target['target'];
		$targetMap[$target['month']]['rate'] = $target['rate'];
		$targetMap[$target['month']]['payment_perc'] = $target['payment_perc'];
	}
	
	$specialTargetMap = array();
	$specialTargetObjects = mysqli_query($con,"SELECT * FROM special_target WHERE YEAR(fromDate)='$urlYear' AND ar_id = '$urlId' ORDER BY fromDate") or die(mysqli_error($con));		 
	foreach($specialTargetObjects as $target)
	{
		$month = (int)date("m",strtotime($target['fromDate']));
		$from = date("Y-m-d",strtotime($target['fromDate']));
		$to = date("Y-m-d",strtotime($target['toDate']));
		$dateString = date('d',strtotime($target['fromDate'])). ' to ' .date('d',strtotime($target['toDate']));
		$specialTargetMap[$month][$dateString]['target'] = $target['special_target'];
		
		$sql = mysqli_query($con, "SELECT ar_id,SUM(srp),SUM(srh),SUM(f2r),SUM(return_bag) FROM nas_sale WHERE entry_date >= '$from' AND entry_date <= '$to' AND ar_id = '$urlId'" ) or die(mysqli_error($con));
		$sale = mysqli_fetch_array($sql,MYSQLI_ASSOC);
		$specialTargetMap[$month][$dateString]['sale'] = $sale['SUM(srp)'] + $sale['SUM(srh)'] + $sale['SUM(f2r)'] - $sale['SUM(return_bag)'];
		
		$sql = mysqli_query($con, "SELECT SUM(qty) FROM extra_bags WHERE date >= '$from' AND date <= '$to' AND ar_id = '$urlId'" ) or die(mysqli_error($con));
		$extraBags = mysqli_fetch_array($sql,MYSQLI_ASSOC);		
		$specialTargetMap[$month][$dateString]['extra'] =  $extraBags['SUM(qty)'];
	}	
	
	$redemptionMap = array();
	$redemptionObjects = mysqli_query($con,"SELECT * FROM redemption WHERE YEAR(date)='$urlYear' AND ar_id = '$urlId' ") or die(mysqli_error($con));		 
	foreach($redemptionObjects as $redemption)
	{
		$redMonth = (int)date('m',strtotime($redemption['date']));
		$redemptionMap[$redMonth][] = $redemption;
	}
	
	$saleMap = array();	
	$salesList = mysqli_query($con, "SELECT SUM(srp),SUM(srh),SUM(f2r),SUM(return_bag),MONTH(entry_date) FROM nas_sale WHERE YEAR(entry_date) = '$urlYear' AND ar_id = '$urlId' GROUP BY MONTH(entry_date) ORDER BY MONTH(entry_date) ASC" ) or die(mysqli_error($con));
	foreach($salesList as $sale) 
	{
		$saleMap[$sale['MONTH(entry_date)']] = $sale['SUM(srp)'] + $sale['SUM(srh)'] + $sale['SUM(f2r)'] - $sale['SUM(return_bag)'];
	}
	
	$mainArray = array();
	foreach($saleMap as $month => $total)
	{
		$mainArray[$month]['points'] = null;
		$mainArray[$month]['actual_perc'] = null;
		$mainArray[$month]['point_perc'] = null;
		$mainArray[$month]['achieved_points'] = null;
		$mainArray[$month]['payment_points'] = null;					

		if(isset($targetMap[$month]['target']) && $isActive && $targetMap[$month]['target'] >0)
		{
			$points = round($total * $targetMap[$month]['rate'],0);
			$actual_perc = round($total * 100 / $targetMap[$month]['target'],0);
			$point_perc = getPointPercentage($actual_perc,$urlYear,$month);			 
			$achieved_points = round($points * $point_perc/100,0);
			
			if($total > 0)		
				$payment_points = round($achieved_points * $targetMap[$month]['payment_perc']/100,0);
			else
				$payment_points = 0;			

			$mainArray[$month]['points'] = $points;
			$mainArray[$month]['actual_perc'] = $actual_perc;
			$mainArray[$month]['point_perc'] = $point_perc;
			$mainArray[$month]['achieved_points'] = $achieved_points;
			$mainArray[$month]['payment_points'] = $payment_points;			
		}		
	}
?>

<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" type="text/css" href="../css/loader.css">
<link rel="stylesheet" type="text/css" href="../css/responstable.css">
<script type="text/javascript" language="javascript" src="../js/jquery.js"></script>
<script type="text/javascript">
function rerender()
{
	var ar = document.getElementById("ar").options[document.getElementById("ar").selectedIndex].value;
	var year = document.getElementById("year").options[document.getElementById("year").selectedIndex].value;

	var hrf = window.location.href;
	hrf = hrf.slice(0,hrf.indexOf("?"));

	window.location.href = hrf +"?id="+ ar + "&year=" + year;
}
</script>
<title>Ledger</title>
</head>
<body>
<div id="main" class="main">
<div align="center" style="padding-bottom:5px;">
<a href="../index.php" class="link"><img alt='home' title='home' src='../images/home.png' width='60px' height='60px'/> </a>
<br><br>
	<select id="ar" name="ar" onchange="return rerender();">																							<?php	
	foreach($arList as $ar) 
	{																																										?>			
		<option <?php if($urlId == $ar['id']) echo 'selected';?> value="<?php echo $ar['id'];?>"><?php echo $ar['name'];?></option>															<?php	
	}																																									?>	
	</select>					
	&nbsp;&nbsp;
	<select id="year" name="year" onchange="return rerender();">																							<?php	
	foreach($yearMap as $index => $year) 
	{																																										?>			
		<option <?php if($urlYear == $year) echo 'selected';?> value="<?php echo $year;?>"><?php echo $year;?></option>															<?php	
	}																																									?>	
	</select>						
<br/><br/>	
<h1><?php echo $arName . ', ' .$urlYear ;?></h1>
</div>
<table align="center" class="responstable" style="width:50%;">
<tr>
	<th style="text-align:left;">Month</th>
	<th style="width:10%;">Target</th>
	<th style="width:10%;">Sale</th>
	<th style="width:10%;">Points</th>
	<th>Remarks</th>
</tr>
<?php
foreach($targetMap as $month => $target) 
{
	if(isset($specialTargetMap[$month]))
	{
		foreach($specialTargetMap[$month] as $dateString => $subArray)
		{																														?>
			<tr>
				<td style="text-align:left;"><?php echo getMonth($month).' '.$dateString;?></td>
				<td><?php echo $subArray['target'];?></td>
				<td><?php echo $subArray['sale'];?></td>
				<td><?php if($subArray['sale'] + $subArray['extra'] >= $subArray['target']) echo $subArray['sale'];else echo '0';?></td>
				<td></td>
			</tr>																												<?php			
		}	
	}																															?>
	<tr>
		<td style="text-align:left;"><?php echo getMonth($month);?></td>
		<td><?php echo $target['target'];?></td>
		<td><?php if(isset($saleMap[$month]))echo $saleMap[$month]; else echo '0';?></td>
		<td><?php if(isset($mainArray[$month]['payment_points'])) echo $mainArray[$month]['payment_points']; else echo '0';?></td>															
		<td></td>
	</tr>																													<?php
	if(isset($redemptionMap[$month]))
	{
		foreach($redemptionMap[$month] as $redemption)
		{																														?>
			<tr>
				<td style="text-align:left;"><?php echo date('F d',strtotime($redemption['date']));?></td>
				<td colspan="2">Redemption</td>
				<td><?php echo $redemption['points'];?></td>
				<td><?php echo $redemption['remarks'];?></td>
			</tr>																												<?php			
		}	
	}																															?>	
	<tr><td colspan="5" style="background-color:#167F92;"></td></tr>															<?php																		
}																																?>
</table>
<br><br>
</div>
</body>
</html>																														<?php

}
else
	header("Location:../index.php");
