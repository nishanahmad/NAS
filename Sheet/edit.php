<?php 
session_start();
if(isset($_SESSION["user_name"]))
{
	require '../connect.php';
	require 'navbar.php';
	
	$designation = $_SESSION['role'];
	$id = $_GET['id'];
	$areaList = mysqli_query($con,"SELECT id,name FROM sheet_area ORDER BY name ASC");
	$shopList = mysqli_query($con,"SELECT id,shop_name FROM ar_details WHERE shop_name IS NOT NULL AND shop_name != '' ORDER BY shop_name ASC");
	$sql = mysqli_query($con,"SELECT * FROM sheets WHERE id='$id'") or die(mysqli_error($con));
	$request = mysqli_fetch_array($sql,MYSQLI_ASSOC);
	
	?>
	<html>
	<head>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet"/>
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>				
	<script>
		$(function() {
			var pickerOpts = { dateFormat:"dd-mm-yy"}; 
			$( ".datepicker" ).datepicker(pickerOpts);

			$("#driver_area").select2();	
		});
	</script>
	<?php
		
		if($request['status'] == 'requested')
		{																													?>
			<title>Request Edit</title><?php
		}
		else
		{									 ?>
			<title>Delivery Edit</title><?php
		}									 ?>	

	</head>
	<body>
		<br/><br/>
		<form class="form" id="form1" method="post" action="update.php">
			<input name="id" type="hidden" value="<?php echo $request['id'];?>"/>
			<div align="center">
			<div class="card col-md-5 col-sm-12">
				<div class="card-header" style="background-color:#3498db;font-size:20px;font-weight:bold;color:white">Edit Sheet Request</div>
				<div class="card-body">
					<br/>																																													<?php 
					if($request['status'] == 'requested')
					{																																														?>
						<div class="col col-md-10 col-sm-12">
							<div class="input-group mb-3">
								<span class="input-group-text col-md-4 col-sm-3"><i class="far fa-calendar-alt"></i>&nbsp;Date</span>
								<input name="date" type="text" placeholder="Date" class="form-control datepicker" value="<?php echo date("d-m-Y",strtotime($request['date']));?>"/>
							</div>
						</div>																																							<?php
					}
					if($request['status'] == 'delivered' && $_SESSION['role'] != 'driver')
					{																																									?>
						<div class="col col-md-10 col-sm-12">
							<div class="input-group mb-3">
								<span class="input-group-text col-md-4 col-sm-3"><i class="far fa-calendar-alt"></i>&nbsp;Date</span>
								<input name="delivered_on" type="text" class="feedback-input" placeholder="Date" class="form-control datepicker" value="<?php echo date("d-m-Y",strtotime($request['date']));?>"/>
							</div>
						</div>																																							<?php
					}																																									?>								
					
					<div class="col col-md-10 col-sm-12">
						<div class="input-group mb-3">
							<span class="input-group-text col-md-4 col-sm-3"><i class="far fa-user"></i>&nbsp;Customer</span>
							<input name="customer_name" type="text" class="form-control" placeholder="Customer Name" value="<?php echo $request['customer_name'];?>"/>
						</div>
					</div>
					<div class="col col-md-10 col-xs-12">
						<div class="input-group mb-3">
							<span class="input-group-text col-md-4"><i class="fas fa-mobile-alt"></i>&nbsp;Cust Phone</span>
							<input name="customer_phone" type="text" class="form-control" placeholder="Customer Phone" value="<?php echo $request['customer_phone'];?>"/>
						</div>
					</div>
					<div class="col col-md-10">
						<div class="input-group mb-3">
							<span class="input-group-text col-md-4"><i class="far fa-user"></i>&nbsp;Mason</span>
							<input name="mason_name" type="text" class="form-control" placeholder="Mason Name" value="<?php echo $request['mason_name'];?>"/>
						</div>
					</div>
					<div class="col col-md-10">
						<div class="input-group mb-3">
							<span class="input-group-text col-md-4"><i class="fas fa-mobile-alt"></i>&nbsp;Mason Phone</span>
							<input name="mason_phone" type="text" class="form-control" placeholder="Mason Phone" value="<?php echo $request['mason_phone'];?>"/>
						</div>
					</div><?php
					if($request['status'] == 'requested')
					{																														?>
						<div class="col col-md-10">
							<div class="input-group mb-3">
								<span class="input-group-text col-md-4"><i class="fab fa-buffer"></i>&nbsp;No.of Bags</span>
								<input name="bags" type="text" class="form-control" value="<?php echo $request['bags'];?>"/>
							</div>
						</div>																												<?php
					}
					if($request['status'] == 'delivered' && $designation != 'driver')
					{																														?>
						<div class="col col-md-10">
							<div class="input-group mb-3">
								<span class="input-group-text col-md-4"><i class="fab fa-buffer"></i>&nbsp;Sheets</span>
								<input name="qty" type="text" class="form-control" value="<?php echo $request['qty'];?>"/>
							</div>
						</div>																												<?php
					}																														?>							
					<div class="col col-md-10">
						<div class="input-group mb-3">
							<span class="input-group-text col-md-4"><i class="fa fa-address-card-o"></i>&nbsp;Shop</span>
							<select name="shop1" id="shop1" class="form-control">
								<option value="">---- SELECT SHOP ---</option>															<?php
								foreach($shopList as $shop) 
								{																										?>
									<option <?php if($request['shop1'] == $shop['id']) echo 'selected';?> value="<?php echo $shop['id'];?>"><?php echo $shop['shop_name'];?></option>						<?php
								}																										?>
							</select>
						</div>
					</div>																																													
					<div class="col col-md-10">
						<div class="input-group mb-3">
							<span class="input-group-text col-md-4"><i class="fa fa-map-o"></i>&nbsp;Area</span>
							<select name="driver_area" required id="driver_area" class="form-control">
								<option value="">---- SELECT AREA ---</option>															<?php
								foreach($areaList as $area) 
								{																										?>
									<option <?php if($request['driver_area'] == $area['id']) echo 'selected';?> value="<?php echo $area['id'];?>"><?php echo $area['name'];?></option>						<?php
								}																										?>
							</select>
						</div>
					</div>																																								
					<div class="col col-md-10">
						<div class="input-group mb-3">
							<span class="input-group-text col-md-4"><i class="fas fa-map-marker-alt"></i>&nbsp;Address</span>
							<textarea name="area" class="form-control"><?php echo $request['area'];?></textarea>
						</div>
					</div>																	<div class="col col-md-10">
						<div class="input-group mb-3">
							<span class="input-group-text col-md-4"><i class="far fa-comment-dots"></i>&nbsp;Remarks</span>
							<textarea name="remarks" class="form-control" placeholder="Remarks" ><?php echo $request['remarks'];?></textarea>
						</div>
					</div>																																			
					<br/>
					<div class="col col-md-10 offset-1">
						<div style="float:left">
						  <input class="form-check-input" type="checkbox" id="priority" name="priority" <?php if ( $request['priority'] ) echo 'checked="checked" '; ?>>
						  <label class="form-check-label" for="flexCheckDefault">Priority</label>
						</div>	  
						<div style="float:right;margin-right:20px;">
						  <input class="form-check-input" type="checkbox" id="block" name="block" <?php if ( $request['coveringBlock'] ) echo 'checked="checked" '; ?>>
						  <label class="form-check-label" for="flexCheckDefault">Concrete Block</label>
						</div>	  								
					</div>
					<br/><br/>

					<button type="submit" class="btn" style="width:150px;font-size:18px;background-color:#3498db;color:white;"><i class="fa fa-paper-plane"></i> UPDATE</button>
				</div>
				<div class="card-footer" style="background-color:#3498db;padding:1px;"></div>
			</div>
			</div>
		</form>
		</body>
	</html> 																																		<?php
}
else
	header("Location:../index.php");
