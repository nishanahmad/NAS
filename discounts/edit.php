<?php
session_start();
if(isset($_SESSION["user_name"]))
{
	require '../connect.php';
    
	$products = mysqli_query($con,"SELECT * FROM products WHERE status = 1 ORDER BY name") or die(mysqli_error($con));
	$clients = mysqli_query($con,"SELECT * FROM ar_details ORDER BY name") or die(mysqli_error($con));
	
	if(!empty($_POST))
	{
		$date = date('Y-m-d',strtotime($_POST['date']));
		$product = (int)$_POST['product'];
		$type = $_POST['type'];
		$amount = (int)$_POST['amount'];
		$client = $_POST['client'];
		$remarks = $_POST['remarks'];

		if(empty($client))
			$client = 'null';
		else
			$client = (int)$client;
		
		if($amount == 0)
		{
			$insertQuery="INSERT INTO discounts (date, product, type, client, amount, status, remarks)
				 VALUES
				 ('$date', $product, '$type', $client, $amount, 0, '$remarks')";			
		}
		else
		{
			$insertQuery="INSERT INTO discounts (date, product, type, client, amount, status, remarks)
				 VALUES
				 ('$date', $product, '$type', $client, $amount, 1, '$remarks')";						
		}			

		$insert = mysqli_query($con, $insertQuery) or die(mysqli_error($con));				

		if($type != 'wd')
		{
			$updateQuery = "UPDATE discounts SET status = 0 WHERE client = '$client' AND product = '$product' AND date < '$date' AND type = '$type' AND status = 1";
			$update = mysqli_query($con, $updateQuery) or die(mysqli_error($con));							
		}
		
		header( "Location: list.php?status=1");
	}	
?>
<html>
	<head>
		<title>Discount</title>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="../css/jquery-ui.css">
		<link href="../css/bootstrap.min.css" rel="stylesheet">
		<link href="../css/dashio.css" rel="stylesheet">
		<link href="../css/dashio-responsive.css" rel="stylesheet">	
		<link href="../css/font-awesome.min.css" rel="stylesheet">		
		<script type="text/javascript" language="javascript" src="../js/jquery.js"></script>
		<script type="text/javascript" language="javascript" src="../js/jquery-ui.min.js"></script>
		<script type="text/javascript" src="../js/bootstrap.min.js"></script>
		<script>
		$(document).ready(function() {
				
				$('#type').on('change',function(){
					if( $(this).val()=="wd"){
						$("#client").removeAttr( "required");
						$("#clientLabel").hide();
					}
					else{
						$("#item").show();
					}
				});			
			});	
			
		</script>   				
	</head>
	<section class="wrapper">
		<div align="center" style="padding-bottom:5px;">
			<a href="../index.php" class="link"><img alt='home' title='home' src='../images/home.png' width='60px' height='60px'/> </a> &nbsp;&nbsp;&nbsp;
		</div>	
		<h2><i class="fa fa-gift" style="margin-right:.5em;margin-left:.5em;"></i>Update Discount</h3>
		<div class="row mt">
			<div class="col-lg-8">
				<div class="form-panel">
					<h4 class="mb"><i class="fa fa-angle-right" style="margin-right:.5em;"></i>Update Discount</h4>
					<form class="form-horizontal style-form"  action="" method="post">
						<div class="form-group">
							<label class="col-sm-2 col-sm-2 control-label">Date</label>
							<div class="col-sm-6">
								<input type="text" required name="date" id="date" value="<?php echo date('d-m-Y');?>" class="form-control">
							</div>
						</div>					
						<div class="form-group">
							<label class="col-sm-2 col-sm-2 control-label">Discount Type</label>
							<div class="col-sm-6">
								<select required name="type" id="type" class="form-control">
									<option value = "">---Select---</option>													
									<option value = "cd">Cash Discount</option>
									<option value = "sd">Special Discount</option>
									<option value = "wd">Wagon Discount</option>
								</select>
							</div>
						</div>											
						<div class="form-group">
							<label class="col-sm-2 col-sm-2 control-label">Product</label>
							<div class="col-sm-6">
								<select required name="product" class="form-control">
									<option value = "">---Select---</option>																			<?php
									foreach($products as $product) 
									{																													?>
											<option value="<?php echo $product['id'];?>"><?php echo $product['name'];?></option>										<?php								
									}																													?>
								</select>
							</div>
						</div>					
						<div class="form-group" id="clientLabel">
							<label class="col-sm-2 col-sm-2 control-label">Client</label>
							<div class="col-sm-6">
								<select required name="client" id="client" class="form-control">
									<option value = "">---Select---</option>																			<?php
									foreach($clients as $client) 
									{																													?>
											<option value="<?php echo $client['id'];?>"><?php echo $client['name'];?></option>										<?php								
									}																													?>
								</select>
							</div>
						</div>											
						<div class="form-group">
							<label class="col-sm-2 col-sm-2 control-label">Amount</label>
							<div class="col-sm-6">
								<input type="text" required name="amount" pattern="[0-9]+" title="Input a valid number" class="form-control">
							</div>
						</div>					
						<div class="form-group">
							<label class="col-sm-2 col-sm-2 control-label">Remarks</label>
							<div class="col-sm-6">
								<input type="text" name="remarks" id="remarks" class="form-control">
							</div>
						</div>											
						<button type="submit" class="btn btn-primary" style="margin-left:200px;" tabindex="4">Update</button> 
						<a href="list.php" class="btn btn-default" style="margin-left:10px;">Cancel</a>
						<br/><br/>
					</form>
				</div>
			</div>
		</div>
	</section>
</html>	
<?php
}
else
	header("Location:../index.php");
?>