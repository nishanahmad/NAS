<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
if(isset($_SESSION["user_name"]))
{
	require '../connect.php';
	
	if(isset($_POST['fromDate']))
		$fromDate = date('Y-m-d',strtotime($_POST['fromDate']));
	else
	{
		$date = new DateTime('FIRST DAY OF PREVIOUS MONTH');
		$fromDate = $date->format('Y-m-d');
	}
	if(isset($_POST['toDate']))
		$toDate = date('Y-m-d',strtotime($_POST['toDate']));
	else
		$toDate = date('Y-m-d');
	
	if(isset($_POST['arId']))
	{
		$arId = $_POST['arId'];
		if(empty($arId))
		{
			$arId = 'All';			
			$sales = mysqli_query($con,"SELECT * FROM nas_sale WHERE entry_date >= '$fromDate' AND entry_date <= '$toDate' ORDER BY sales_id DESC") or die(mysqli_error($con));		
		}
		else		
			$sales = mysqli_query($con,"SELECT * FROM nas_sale WHERE (ar_id = $arId OR eng_id = $arId) AND entry_date >= '$fromDate' AND entry_date <= '$toDate' ORDER BY sales_id DESC") or die(mysqli_error($con));
	}
	else
	{
		$arId = 'All';
		$sales = mysqli_query($con,"SELECT * FROM nas_sale WHERE entry_date >= '$fromDate' AND entry_date <= '$toDate' ORDER BY sales_id DESC") or die(mysqli_error($con));		
	}	

	$arObjects = mysqli_query($con,"SELECT * FROM ar_details ORDER BY name") or die(mysqli_error($con));
	foreach($arObjects as $ar)
		$arMap[$ar['id']] = $ar['name'];

	$productObjects = mysqli_query($con,"SELECT * FROM products") or die(mysqli_error($con));
	foreach($productObjects as $product)
		$productMap[$product['id']] = $product['name'];																																			?>
		
<!DOCTYPE html>
<html>
<head>
	<title>Sales List</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css">	
	<link rel="stylesheet" type="text/css" href="../css/jquery-ui.css">
	<script type="text/javascript" language="javascript" src="../js/jquery.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
	<script type="text/javascript" language="javascript" src="../js/TableSorter.js"></script>
	<script type="text/javascript" language="javascript" src="../js/TablesorterWidgets.js"></script>
	<link href='../select2/dist/css/select2.min.css' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" type="text/css" href="../css/TableSorterBlueTheme.css">
	<script src='../select2/dist/js/select2.min.js' type='text/javascript'></script>
</head>
<body>
	<div align="center">
		<a href="../index.php" class="link"><img alt='home' title='home' src='../images/home.png' width='60px' height='60px'/> </a> &nbsp;&nbsp;&nbsp;
		<a href="new.php" class="link"><img alt='Add' title='Add New' src='../images/addnew.png' width='60px' height='60px'/></a>
	</div>
	<div align="center">
		<br/><br/>
		<form method="post" action="" autocomplete="off" style="width:30%;">
			
			<select name="arId" id="arId" class="form-control">
				<option value = "" <?php if($arId == 'All') echo 'selected';?> >ALL</option>													    	<?php
				foreach($arMap as $id => $name)
				{																																			?>
					<option value="<?php echo $id;?>" <?php if($arId == $id) echo 'selected';?>><?php echo $name;?></option> 						<?php
				}																																			?>
			</select>
			<br/><br/>
			<div class="input-group">
				<input type="text" id="fromDate" class="form-control" name="fromDate" required value="<?php echo date('d-m-Y',strtotime($fromDate)); ?>"/>
				<span class="input-group-addon"> to </span>
				<input type="text" id="toDate" class="form-control" name="toDate" required value="<?php echo date('d-m-Y',strtotime($toDate)); ?>"/>					
			</div>
			<br/><br/>
			<input type="submit" class="btn btn-primary" name="submit" value="Update">
			<br/><br/><br/>
		</form>	
		<font size="5"><b>TOTAL : <span class='total'></span></b></font>
		<br/><br/>
		<table id="sales-table" style="width:95%;">
			<thead>
				<tr>
					<th style="width:100px;">Date</th>
					<th>AR</th>
					<!--th>Rate</th-->	
					<th style="width:30px;">Product</th>
					<th style="width:30px;">Qty</th>
					<th>Bill</th>							
					<th>Truck</th>
					<th>Customer</th>							
					<th>Engineer</th>							
					<th>Remarks</th>							
				</tr>
			</thead>
			<tbody><?php
			foreach($sales as $sale)
			{?>
				<tr>
					<td><a href="edit.php?sales_id=<?php echo $sale['sales_id'];?>&list=all"</a><?php echo date('d-m-Y',strtotime($sale['entry_date']));?></td>
					<td><?php echo $arMap[$sale['ar_id']];?></td>
					<td><?php echo $productMap[$sale['product']];?></td>
					<td><?php echo $sale['qty'];?></td>
					<td><?php echo $sale['bill_no'];?></td>
					<td><?php echo $sale['truck_no'];?></td>
					<td><?php echo $sale['customer_name'];?></td>
					<td><?php if(!empty($sale['eng_id'])) echo $arMap[$sale['eng_id']];?></td>
					<td><?php echo $sale['remarks'];?></td>
				</tr><?php
			}?>
			</tbody>
		</table>
	</div>
	<script>        
	$(function(){
		$("#arId").select2();
		
		$('table').on('initialized filterEnd', function(){
			var total = 0;
			$(this).find('tbody tr:visible').each(function(){
				total += parseFloat( $(this).find('td:eq(3)').text() );
			});
			$('.total').text(total);
		})      
	
		$("table").tablesorter({
			dateFormat : "ddmmyyyy",
			theme : 'blue',
			widgets: ['filter'],
			filter_columnAnyMatch: true,
		}); 

		var pickerOpts = { dateFormat:"dd-mm-yy"}; 
		$( "#fromDate" ).datepicker(pickerOpts);
		$( "#toDate" ).datepicker(pickerOpts);				
	});
	</script>       	
</body>
</html>																				<?php																				
	mysqli_close($con);
}
else
	header("Location:../index.php");
