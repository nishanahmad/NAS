<!DOCTYPE html>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<?php
session_start();
if(isset($_SESSION["user_name"]))
{
	require '../connect.php';
	require '../functions/monthMap.php';			

	if(isset($_GET['id']))
		$urlId = $_GET['id'];
	else
		$urlId = 1;
	
	if(isset($_GET['year']))
		$urlYear = $_GET['year'];
	else
		$urlYear = date("Y");	
	
	$arMap = array();
	$arList = mysqli_query($con, "SELECT id,ar_name FROM ar_details ORDER BY ar_name ASC" ) or die(mysqli_error($con));		
	foreach($arList as $ar) 
	{
		$arMap[$ar['id']] = $ar['ar_name'];
	}
	$yearList = mysqli_query($con, "SELECT DISTINCT YEAR(entry_date) FROM nas_sale WHERE ar_id = '$urlId' ORDER BY entry_date DESC" ) or die(mysqli_error($con));
	foreach($yearList as $year) 
	{
		$yearMap[] = $year['YEAR(entry_date)'];
	}	
	
	$salesMap = array();	
	$salesList = mysqli_query($con, "SELECT SUM(srp),SUM(srh),SUM(f2r),SUM(return_bag),MONTH(entry_date) FROM nas_sale WHERE YEAR(entry_date) = '$urlYear' AND ar_id = '$urlId' GROUP BY MONTH(entry_date) ORDER BY MONTH(entry_date) ASC" ) or die(mysqli_error($con));
	foreach($salesList as $sale) 
	{
		$saleMap[$sale['MONTH(entry_date)']] = $sale['SUM(srp)'] + $sale['SUM(srh)'] + $sale['SUM(f2r)'] - $sale['SUM(return_bag)'];
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
	foreach($arMap as $arId => $arName) 
	{																																										?>			
		<option <?php if($urlId == $arId) echo 'selected';?> value="<?php echo $arId;?>"><?php echo $arName;?></option>															<?php	
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
<h1><?php echo $arMap[$urlId] . ', ' .$urlYear ;?></h1>
</div>
<table align="center" class="responstable" style="width:25%;">
<tr><th style="text-align:left;width:60%">Month</th><th style="text-align:center;">Total Sale</th></tr>
<?php
foreach($saleMap as $month => $sale) 
{																																		?>
	<tr>
		<td style="text-align:left;"><?php echo getMonth($month);?></th>
		<td><?php echo $sale;?></th>
	</tr>																													<?php																		
}																															?>
</table>
<br><br>
</div>
</body>
</html>																														<?php

}
else
	header("Location:../index.php");
