<?php
session_start();
if(isset($_SESSION["user_name"]))
{
	require '../connect.php';
    
	$products = mysqli_query($con,"SELECT * FROM products WHERE status = 1 ORDER BY name") or die(mysqli_error($con));
	
	if(!empty($_POST))
	{
		$date = date('Y-m-d',strtotime($_POST['date']));
		$product = $_POST['product'];
		$rate = (int)$_POST['rate'];
		
		$query = mysqli_query($con,"SELECT * FROM rate WHERE date = '$date' AND product = '$product'") or die(mysqli_error($con));	
		if(mysqli_num_rows($query) >0 )
		{
			$row = mysqli_fetch_array($query,MYSQLI_ASSOC);
			$id = (int)$row['id'];
			$updateQuery ="UPDATE rate SET rate = $rate WHERE id = $id "; 
			$update = mysqli_query($con, $updateQuery) or die(mysqli_error($con));	
		}	
		else
		{
			$insertQuery="INSERT INTO rate (date, product, rate)
				 VALUES
				 ('$date', '$product', $rate)";
			$insert = mysqli_query($con, $insertQuery) or die(mysqli_error($con));				
		}

		header( "Location: list.php");
	}	
?>
<html>
	<head>
		<title>Rate</title>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="../css/jquery-ui.css">
		<link href="../css/bootstrap.min.css" rel="stylesheet">
		<link href="../css/dashio.css" rel="stylesheet">
		<link href="../css/dashio-responsive.css" rel="stylesheet">	
		<link href="../css/font-awesome.min.css" rel="stylesheet">		
		<script type="text/javascript" language="javascript" src="../js/jquery.js"></script>
		<script type="text/javascript" language="javascript" src="../js/jquery-ui.min.js"></script>
		<script type="text/javascript" src="../js/bootstrap.min.js"></script>
	</head>
	<section class="wrapper">
		<div align="center" style="padding-bottom:5px;">
			<a href="../index.php" class="link"><img alt='home' title='home' src='../images/home.png' width='60px' height='60px'/> </a> &nbsp;&nbsp;&nbsp;
		</div>	
		<h2><i class="fa fa-inr" style="margin-right:.5em;margin-left:.5em;"></i>Update Rate</h3>
		<div class="row mt">
			<div class="col-lg-8">
				<div class="form-panel">
					<h4 class="mb"><i class="fa fa-angle-right" style="margin-right:.5em;"></i>Update rate</h4>
					<form class="form-horizontal style-form"  action="" method="post">
						<div class="form-group">
							<label class="col-sm-2 col-sm-2 control-label">Date</label>
							<div class="col-sm-6">
								<input type="text" required name="date" id="date" value="<?php echo date('d-m-Y');?>" class="form-control">
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
						<div class="form-group">
							<label class="col-sm-2 col-sm-2 control-label">Rate</label>
							<div class="col-sm-6">
								<input type="text" required name="rate" pattern="[0-9]+" title="Input a valid number" class="form-control">
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