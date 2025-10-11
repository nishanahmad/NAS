<!DOCTYPE html>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<?php
session_start();
if(isset($_SESSION["user_name"]))
{
	require '../connect.php';
	require '../navbar.php';
	
	$brand = $_GET['brand'] ;
?>

<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="../css/styles.css" rel="stylesheet" type="text/css">	
<link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" rel="stylesheet" type="text/css">
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/jquery.tablesorter.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/jquery.tablesorter.widgets.min.js"></script>
<title>AR List</title>
<style>
#tableDiv {
    overflow-x: auto;
}

</style>
</head>
<body>
<div id="main" class="main">
	<?php if($brand == 'ut'){include '../sidebar_ut.php';} else {include '../sidebar_acc.php';} ?>
    <div class="container">
		<nav class="navbar navbar-light bg-light sticky-top bottom-nav" style="margin-left:13%;width:100%">
			<span class="navbar-brand" style="font-size:25px;margin-left:40%;"><i class="fa fa-address-card-o"></i> AR List</span>
		</nav>	
		<div align="center" id="tableDiv">
		<br/><br/>
		<table class="maintable table table-hover table-bordered" style="width:85%;margin-left:15%;">
		<?php
			$sql = "SELECT * FROM ar_details WHERE type != 'Engineer' ORDER BY name ASC";
			$result = mysqli_query($con, $sql) or die(mysqli_error($con));																					?>
			<thead>
				<tr class="table-success">
					<th>Id</th>
					<th style="width:15%">Name</th>
					<th style="width:15%">Shop</th>
					<th style="text-align:center;width:8%">SAP/Old SAP</th>
					<th>Mobile</th>
					<th>Whatsapp</th>
					<th>Type</th>
					<th>Child/Parent</th>
					<th>Status</th>
				</tr>
			</thead>
			<tbody>																																			<?php
			while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) 
			{
				$arId = $row['id'];
				$arname = $row['name'];
				$shopName = $row['shop_name'];
				$sapCode = $row['sap_code'];
				$oldSap = $row['old_sap'];
				$mobile = $row['mobile'];
				$whatsapp = $row['whatsapp'];
				$type = $row['type'];
				$parentCode = $row['parent_code'];
				$childCode = $row['child_code'];
				$status = $row['status'];
			?>	
			<tr>
				<td><?php echo $arId; ?></td>
				<td><a href="view.php?id=<?php echo $arId;?>"><?php echo $arname; ?></a></td>	
				<td><?php echo $shopName; ?></td>
				<td style="text-align:center;width:8%"><label align="center"><?php echo $sapCode.'<br/>'.$oldSap; ?></td>	
				<td style="text-align:center;width:10%"><?php echo $mobile;?></td>		
				<td style="text-align:center;width:10%"><?php echo $whatsapp;?></td>		
				<td><?php echo $type;?></td>
				<td><?php echo $childCode.'<br/>'.$parentCode;?></td>
				<td><?php echo $status;?></td>
			</tr>																																			<?php
			}																																																										?>
			</tbody>	
		</table>
		</div>
	</div>
	<br/><br/>
</div>
</body>
<script>
$(document).ready(function() {		
	$("table").tablesorter({
		dateFormat : "ddmmyyyy",
		theme : 'bootstrap',
		widgets: ['filter'],
		filter_columnAnyMatch: true
	}); 
} );
</script>
</html>																														<?php

}
else
	header("Location:../index/home.php");
