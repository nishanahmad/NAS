<?php
session_start();
if(isset($_SESSION["user_name"]))
{
	require '../connect.php';
	require '../navbar.php';
	require 'newModal.php';
	require '../functions/monthMap.php';
	
	$arObjects = mysqli_query($con,"SELECT * FROM ar_details ORDER BY name") or die(mysqli_error($con));
	
	foreach($arObjects as $ar)
		$arMap[$ar['id']] = $ar['name'];
	
	$ppList = mysqli_query($con,"SELECT * FROM custom_point_perc ORDER BY id DESC" ) or die(mysqli_error($con));
?>	
<!DOCTYPE html>
<html>
	<title>Custom Point %</title>
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
		<script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.4.0/bootbox.min.js" integrity="sha512-8vfyGnaOX2EeMypNMptU+MwwK206Jk1I/tMQV4NkhOz+W8glENoMhGyU6n/6VgQUhQcJH8NqQgHhMtZjJJBv3A==" crossorigin="anonymous"></script>
		<title>Rate</title>
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
		<aside class="sidebar">
			<nav class="nav">
				<ul>
					<li><a href="../ar/list.php">AR List</a></li>
					<li class="active"><a href="#">Target</a></li>
					<li><a href="../SpecialTarget/list.php?">Special Target</a></li>
					<li><a href="../gold_points/list.php?">Gold Points</a></li>
					<li><a href="../redemption/list.php?">Redemption</a></li>
				</ul>
			</nav>
		</aside>						
		<nav class="navbar navbar-light bg-light sticky-top bottom-nav" style="margin-left:18%">
			<div class="btn-group" role="group" aria-label="Button group with nested dropdown" style="float:left;margin-left:2%;">
				<div class="btn-group" role="group">
					<button id="btnGroupDrop1" type="button" class="btn btn-outline-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
						Point %
					</button>
					<ul class="dropdown-menu" aria-labelledby="btnGroupDrop1" style="cursor:pointer">
						<li><a href="../Target/list.php?" class="dropdown-item">Monthly Points</a></li>
						<li><a href="../points_full/mainPage.php?" class="dropdown-item">Accumulated Points</a></li>
						<li><a href="../Target/edit.php?" class="dropdown-item">Update Target</a></li>
						<li><a href="../targetBags/list.php" class="dropdown-item">Target Bags</a></li>
					</ul>
				</div>
			</div>							
			<span class="navbar-brand" style="font-size:25px;">Custom Point %</span>
			<a href="#" class="btn btn-sm" role="button" style="background-color:#54698D;color:white;float:right;margin-right:40px;" data-toggle="modal" data-target="#newModal"> New Point %</a>			
		</nav>
		<div style="width:100%;" class="mainbody">	
			<div id="snackbar"><i class="fas fa-hand-holding-usd"></i>&nbsp;&nbsp;Redemption inserted successfully !!!</div>		
			<div align="center" style="margin-left:15%">
				<br/><br/>
				<table class="maintable table table-hover table-bordered" style="width:50%">
					<thead>
						<tr class="table-info">
							<th style="width:100px">Year</th>
							<th style="width:120px">Month</th>
							<th>AR</th>
							<th style="width:90px">Point%</th>	
							<th style="width:150px"></th>
						</tr>
					</thead>
					<tbody>																														<?php
					foreach($ppList as $pp)
					{																															?>
						<tr>
							<td><?php echo $pp['year'];?></td>
							<td><?php echo getMonth($pp['month']);?></td>
							<td><?php if(isset($arMap[$pp['ar']])) echo $arMap[$pp['ar']]; else echo $pp['ar'];?></td>
							<td><?php echo $pp['percentage'].'%';?></td>
							<td style="text-align:center"><button class="btn" onclick="dlt(<?php echo $pp['id'];?>)" style="background-color:#E6717C;color:#FFFFFF;"><i class="far fa-trash-alt"></i> Delete</button></td>
						</tr>																													<?php
					}																															?>
					</tbody>	
			</table>
		</div>
	</body>
	<script src="list.js"></script>
</html>																																														<?php
}
else
	header("Location:../index/home.php");
