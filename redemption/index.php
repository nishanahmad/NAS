<?php
session_start();
if(isset($_SESSION["user_name"]))
{
	require '../connect.php';
	
	$arObjects = mysqli_query($con,"SELECT * FROM ar_details ORDER BY name") or die(mysqli_error($con));
	foreach($arObjects as $ar)
		$arMap[$ar['id']] = $ar['name'];
		
	$redemptionList = mysqli_query($con,"SELECT * FROM redemption ORDER BY date DESC" ) or die(mysqli_error($con));
?>	
<!DOCTYPE html>
<html>
	<title>Redemption List</title>
	<head>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css">	
		<link rel="stylesheet" type="text/css" href="../css/jquery-ui.css">
		<script type="text/javascript" language="javascript" src="../js/jquery.js"></script>
		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
		<script type="text/javascript" language="javascript" src="../js/TableSorter.js"></script>
		<script type="text/javascript" language="javascript" src="../js/TablesorterWidgets.js"></script>
		<link href='../select2/dist/css/select2.min.css' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" type="text/css" href="../css/TableSorterBlueTheme.css">
		<script type="text/javascript" language="javascript">
			$(function(){
				$("table").tablesorter({
					dateFormat : "ddmmyyyy",
					theme : 'blue',
					widgets: ['filter'],
					filter_columnAnyMatch: true,
				}); 
			});			
		</script>

	</head>
	<body>
		<div align="center">
					<a href="../index.php" class="link"><img alt='home' title='home' src='../images/home.png' width='60px' height='60px'/> </a> &nbsp;&nbsp;&nbsp;
					<a href="new.php" class="link"><img alt='Add' title='Add New' src='../images/addnew.png' width='60px' height='60px'/></a
		
		</div>
<div align="center" class="gradient">
<font size=5>
<br>
</b></font>

		<br><br>
			<table style="width:60%">
					<thead>
						<tr>
							<th style="width:90px">Date</th>
							<th style="width:200px">AR</th>
							<th style="width:50px">Points</th>	
							<th>Remarks</th>			
							<th style="width:90px">Entered On</th>
						</tr>
					</thead>
					<tbody>																														<?php
					foreach($redemptionList as $redemption)
					{																															?>
						<tr>
							<td><?php echo date('d-m-Y',strtotime($redemption['date']));?></td>
							<td><?php echo $arMap[$redemption['ar_id']];?></td>
							<td><?php echo $redemption['points'];?></td>
							<td><?php echo $redemption['remarks'];?></td>
							<td><?php echo date('d-m-Y',strtotime($redemption['entered_on']));?></td>																	
						</tr>																													<?php
					}																															?>
					</tbody>	
			</table>
		</div>
	</body>
</html>																																														<?php
}
else
	header("Location:../index.php");
