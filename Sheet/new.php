<?php
session_start();
if(isset($_SESSION["user_name"]))
{
	require 'navbar.php';
	require '../connect.php';
	
	$areaList = mysqli_query($con,"SELECT id,name FROM sheet_area ORDER BY name ASC");
	$shopList = mysqli_query($con,"SELECT id,shop_name FROM ar_details WHERE shop_name IS NOT NULL AND shop_name != '' ORDER BY shop_name ASC");	?>
<html>
	<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="../css/styles.css" rel="stylesheet" type="text/css">
	<script>
		$(function() {
			var pickerOpts = { dateFormat:"dd-mm-yy"}; 
					
			$( ".datepicker" ).datepicker(pickerOpts);
		
			if(window.location.href.includes('success')){
				var x = document.getElementById("snackbar");
				x.className = "show";
				setTimeout(function(){ x.className = x.className.replace("show", ""); }, 2000);
			}	

			$("#driver_area").select2();			
		});
	</script>	
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet"/>
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>			
	<title>Sheet Request New</title>
	</head>
	<body>
		<div id="snackbar"><i class="fa fa-paper-plane"></i>&nbsp;&nbsp;Request sent successfully !!!</div>
		<form class="form" id="form1" method="post" action="insert.php" autocomplete="off">
			<div style="width:100%;">
				<div align="center" style="padding-bottom:5px;">
					<br/><br/>
					<div class="card" style="width:40%;">
						<div class="card-header" style="background-color:#3498db;font-size:20px;font-weight:bold;color:white">New Sheet Request</div>
						<div class="card-body">
							<br/>
							<div class="col col-md-10 offset-1">
								<div class="input-group mb-3">
									<span class="input-group-text col-md-5"><i class="far fa-calendar-alt"></i>&nbsp;Date</span>
									<input name="date" required type="text" class="form-control datepicker"/>
								</div>
							</div>
							<div class="col col-md-10 offset-1">
								<div class="input-group mb-3">
									<span class="input-group-text col-md-5"><i class="far fa-user"></i>&nbsp;Customer Name</span>
									<input name="customer_name" type="text" class="form-control"/>
								</div>
							</div>
							<div class="col col-md-10 offset-1">
								<div class="input-group mb-3">
									<span class="input-group-text col-md-5"><i class="fas fa-mobile-alt"></i>&nbsp;Customer Phone</span>
									<input name="customer_phone" type="text" class="form-control"/>
								</div>
							</div>
							<div class="col col-md-10 offset-1">
								<div class="input-group mb-3">
									<span class="input-group-text col-md-5"><i class="far fa-user"></i>&nbsp;Mason Name</span>
									<input name="mason_name" type="text" class="form-control"/>
								</div>
							</div>
							<div class="col col-md-10 offset-1">
								<div class="input-group mb-3">
									<span class="input-group-text col-md-5"><i class="fas fa-mobile-alt"></i>&nbsp;Mason Phone</span>
									<input name="mason_phone" type="text" class="form-control"/>
								</div>
							</div>							
							<div class="col col-md-10 offset-1">
								<div class="input-group mb-3">
									<span class="input-group-text col-md-5"><i class="fab fa-buffer"></i>&nbsp;No.of Bags</span>
									<input name="bags" required type="text" class="form-control"/>
								</div>
							</div>														
							<div class="col col-md-10 offset-1">
								<div class="input-group mb-3">
									<span class="input-group-text col-md-5"><i class="fa fa-address-card-o"></i>&nbsp;Shop</span>
									<select name="shop1" id="shop1" class="form-control">
										<option value="">---- SELECT SHOP ---</option>									<?php
										foreach($shopList as $shop) 
										{																								?>
											<option value="<?php echo $shop['id'];?>"><?php echo $shop['shop_name'];?></option>			<?php	
										}																								?>
									</select>
								</div>
							</div>							
							<div class="col col-md-10 offset-1">
								<div class="input-group mb-3">
									<span class="input-group-text col-md-5"><i class="fa fa-map-o"></i>&nbsp;Area</span>
									<select name="driver_area" required id="driver_area" class="form-control">
										<option value="">---- SELECT AREA ---</option>									<?php
										foreach($areaList as $area) 
										{																								?>
											<option value="<?php echo $area['id'];?>"><?php echo $area['name'];?></option>			<?php	
										}																								?>
									</select>
								</div>
							</div>																																			
							<div class="col col-md-10 offset-1">
								<div class="input-group mb-3">
									<span class="input-group-text col-md-5"><i class="fas fa-map-marker-alt"></i>&nbsp;Address</span>
									<textarea required name="area" class="form-control"></textarea>
								</div>
							</div>																												
							<div class="col col-md-10 offset-1">
								<div class="input-group mb-3">
									<span class="input-group-text col-md-5"><i class="far fa-comment-dots"></i>&nbsp;Remarks</span>
									<textarea name="remarks" class="form-control"></textarea>
								</div>
							</div>
							<br/>
							<div class="col col-md-10 offset-2">
								<div style="float:left">
								  <input class="form-check-input" type="checkbox" id="priority" name="priority">
								  <label class="form-check-label" for="flexCheckDefault">Priority</label>
								</div>	  
								<div style="float:right;margin-right:20px;">
								  <input class="form-check-input" type="checkbox" id="block" name="block">
								  <label class="form-check-label" for="flexCheckDefault">Concrete Block</label>
								</div>	  								
							</div>
							<br/><br/>
							<button type="submit" class="btn" style="width:150px;font-size:18px;background-color:#3498db;color:white;"><i class="fa fa-paper-plane"></i> REQUEST</button>
						</div>
						<div class="card-footer" style="background-color:#3498db;padding:1px;"></div>
					</div>
				</div>
			</div>
			<br/><br/><br/><br/>		
		</form>		
	</body>	
</html>
																										<?php
}	
else
	header("Location:../index.php");