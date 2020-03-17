<!DOCTYPE html>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<?php
session_start();
if(isset($_SESSION["user_name"]))
{
	require '../connect.php';
?>

<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script type="text/javascript" language="javascript" src="../js/jquery.js"></script>
<script type="text/javascript" language="javascript" src="../js/jquery-ui.min.js"></script>
<link href="../css/bootstrap.min.css" rel="stylesheet">
<script type="text/javascript" src="../js/bootstrap.min.js"></script>
<link rel="stylesheet" type="text/css" href="../css/jquery-ui.css">
<script src="../js/TableSorter.js"></script>
<script src="../js/TablesorterWidgets.js"></script>	
<link rel="stylesheet" href="../css/TableSorterBlueTheme.css">						
<title>AR List</title>
</head>
<body>
<div id="main" class="main">
<div align="center" style="padding-bottom:5px;">
<a href="../index.php" class="link"><img alt='home' title='home' src='../images/home.png' width='60px' height='60px'/> </a>
<br><br>
</div>
<div align="center">
<table style="width:60%;">
<?php
	$sql = "SELECT * FROM ar_details WHERE type LIKE '%AR%' ORDER BY name ASC ";
	$result = mysqli_query($con, $sql) or die(mysqli_error($con));?>
	<thead>
	<tr>
		<th style="width:3%">Id</th>
		<th style="width:20%">Name</th>
		<th style="width:20%">Shop</th>
		<th style="text-align:center;width:8%">SAP</th>
		<th>Mobile</th>
		<th>Area</th>
		<th>Status</th>
	</tr>
	</thead>
	<tbody>
	<?php
	while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) 
	{
		$arId = $row['id'];
		$arname = $row['name'];
		$shopName = $row['shop_name'];
		$sapCode = $row['sap_code'];
		$area = $row['area'];
		$mobile = $row['mobile'];
		$status = $row['isActive'];
	?>	
	<tr>
		<td><a style="color:grey;" href="view.php?id=<?php echo $row['id'];?>"><?php echo $row['id'];?></a></td>	
		<td><?php echo $arname; ?></td>	
		<td><?php echo $shopName; ?></td>	
		<td style="text-align:center;width:8%"><label align="center"><?php echo $sapCode; ?></td>	
		<td style="text-align:center;width:10%"><?php echo $mobile;?></td>		
		<td style=""><?php echo $area;?></td>	
		<td style="text-align:center;width:8%"><?php if($status == 1 ) echo 'Active'; else echo 'InActive';?></td>
	</tr>																													<?php
	}																																																										?>
	</tbody>	
</table>
</div>
<br/><br/>
<div align="center"><input type="submit" name="submit" value="Submit" onclick=" return showLoader()"></div>
<br/><br/>
</div>
</body>
<script>
$(document).ready(function() {		
	$("table").tablesorter({
		dateFormat : "ddmmyyyy",
		theme : 'blue',
		widgets: ['filter'],
		filter_columnAnyMatch: true,
	}); 
} );
</script>
</html>																														<?php

}
else
	header("Location:../index.php");
