<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
if(isset($_SESSION["user_name"]))
{
	require '../connect.php';
	require '../navbar.php';
	require 'deleteModal.php';
	
	$id = $_GET['id'];
	$passQuery = mysqli_query($con, "SELECT * FROM gate_pass WHERE id = '$id'") or die(mysqli_error($con).'Line 11');
	$pass = mysqli_fetch_array($passQuery, MYSQLI_ASSOC);	
	$passId = $pass['id'];
	
	$consignors = mysqli_query($con,"SELECT * FROM consignors") or die(mysqli_error($con));	
	$godowns = mysqli_query($con,"SELECT * FROM consignors") or die(mysqli_error($con));	
	
	?>
		
	<html>
	<head>
		<title>LR <?php echo $id; ?></title>
		<link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" rel="stylesheet" type="text/css">
		<link href="../css/styles.css" rel="stylesheet" type="text/css">
		<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/jquery.tablesorter.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/jquery.tablesorter.widgets.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.6.3/jquery-ui-timepicker-addon.min.js" integrity="sha512-s5u/JBtkPg+Ff2WEr49/cJsod95UgLHbC00N/GglqdQuLnYhALncz8ZHiW/LxDRGduijLKzeYb7Aal9h3codZA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	</head>
	<body>
		<nav class="navbar navbar-light bg-light sticky-top bottom-nav">
			<span class="navbar-brand" style="font-size:25px;margin-left:43%;"><i class="fas fa-key"></i> LR-<?php echo $pass['id']; ?></span>
		</nav>
		<br/><br/>
		<div id="snackbar"><i class="fa fa-check"></i>&nbsp;&nbsp;Updated successfull !!!</div>
		<form name="editForm" id="editForm" method="post" action="update.php">
			<input hidden name="id" id="id" value="<?php echo $pass['id'];?>">
			<div align="center" style="padding-bottom:5px;">			
				<div style="width:80%;margin-left:10%">
					<div class="card">
						<div class="card-header"><i class="fa fa-list-alt"></i></div>
						<div class="card-body" style="margin-left:15px;">
							<br/>
							<div class="row">					
								<div class="form-group row">						
									<div class="col col-md-3 offset-1">
										<div class="input-group mb-3">
											<span class="input-group-text" style="width:100px;">LR No</span>
											<input type="text" readonly class="form-control" value="<?php echo 'KNR/24-25/'.$pass['id'];?>">
										</div>
									</div>
									<div class="col col-md-4">
										<div class="input-group mb-3">
											<span class="input-group-text" style="width:100px;">Token No</span>
											<input type="text" name="token" id="token" class="form-control" autocomplete="off" value="<?php echo $pass['token_no'];?>">
										</div>
									</div>																					
									<div class="col col-md-3">
										<div class="input-group mb-3">
											<span class="input-group-text" style="width:100px;"><i class="fa fa-list-alt"></i>&nbsp;Date</span>
											<input type="date" name="date" id="dateId" class="form-control" value="<?php echo date('Y-m-d',strtotime($pass['date']));?>">
										</div>
									</div>
								</div>
							</div>
							<div class="row">					
								<div class="form-group row">						
									<div class="col col-md-3 offset-1">
										<div class="input-group mb-3">
											<span class="input-group-text" style="width:100px;">SL No</span>
											<input type="text" name="sl_no" id="sl_no" class="form-control" autocomplete="off" value="<?php echo $pass['sl_no'];?>">
										</div>
									</div>																					
									<div class="col col-md-4">
										<div class="input-group mb-3">
											<span class="input-group-text" style="width:100px;">Order No</span>
											<input type="text" name="order_no" id="order_no" class="form-control" autocomplete="off" value="<?php echo $pass['order_no'];?>">
										</div>
									</div>							
									<div class="col col-md-3">
										<div class="input-group mb-3">
											<span class="input-group-text" style="width:100px;">Time</span>
											<input type="text" name="time" id="time" class="form-control" autocomplete="off" value="<?php echo $pass['time'];?>">
										</div>
									</div>							
								</div>
							</div>
							<div class="row">					
								<div class="form-group row">						
									<div class="col col-md-3 offset-1">
										<div class="input-group mb-3">
											<span class="input-group-text" style="width:100px;">Consignor</span>
											<select name="consignor" required id="consignor" class="form-control" style="width:60%">
												<option value = "">---Select---</option>																						<?php
												foreach($consignors as $consignor) 
												{																							?>
													<option value="<?php echo $consignor['id'];?>" <?php if($consignor['id'] == $pass['consignor_id']) echo 'selected';?>><?php echo $consignor['name'];?></option>			<?php	
												}																							?>
											</select>
										</div>
									</div>																					
									<div class="col col-md-4">
										<div class="input-group mb-3">
											<span class="input-group-text" style="width:100px;">From</span>
											<select name="from_godown" id="from_godown" class="form-control" style="width:60%">
												<option value = "">---Select---</option>																						<?php
												foreach($godowns as $godown) 
												{																							?>
													<option value="<?php echo $godown['id'];?>" <?php if($godown['id'] == $pass['from_godown']) echo 'selected';?>><?php echo $godown['godown'];?></option>			<?php	
												}																							?>
											</select>
										</div>
									</div>							
									<div class="col col-md-4">
										<div class="input-group mb-3">
											<span class="input-group-text" style="width:100px;">Delivery At</span>
											<select name="delivery_at" id="delivery_at" class="form-control" style="width:60%">
												<option value = "Kannur">Kannur</option>
											</select>
										</div>
									</div>							
								</div>
							</div>
							<div class="row">					
								<div class="form-group row">						
									<div class="col col-md-3 offset-1">
										<div class="input-group mb-3">
											<span class="input-group-text" style="width:100px;">Driver</span>
											<input type="text" name="driver" id="driver" class="form-control" value="<?php echo $pass['driver'];?>">
										</div>
									</div>																					
									<div class="col col-md-4">
										<div class="input-group mb-3">
											<span class="input-group-text" style="width:100px;">Phone</span>
											<input type="text" name="driver_phone" id="driver_phone" class="form-control" autocomplete="off" value="<?php echo $pass['driver_phone'];?>">
										</div>
									</div>							
									<div class="col col-md-4">
										<div class="input-group mb-3">
											<span class="input-group-text" style="width:100px;">License No</span>
											<input type="text" name="driver_license_no" id="driver_license_no" class="form-control" autocomplete="off" value="<?php echo $pass['driver_license_no'];?>">
										</div>
									</div>							
								</div>
							</div>					
							<div class="row">					
								<div class="form-group row">						
									<div class="col col-md-4 offset-1">
										<div class="input-group mb-3">
											<span class="input-group-text">Vehicle</span>
											<input type="text" name="vehicle" id="vehicle" class="form-control" value="<?php echo $pass['vehicle'];?>">
										</div>
									</div>																					
							</div>												
							<br/><br/>
							<h3 style="margin-left:10%">Particulars</h3>
							<div class="row">					
								<div class="form-group row">						
									<div class="col col-md-5 offset-1">
										<div class="input-group mb-3">
											<span class="input-group-text" style="width:280px;">ULTRATECH PPC LAMINATED</span>
											<input type="text" name="ut_qty" class="form-control" value="<?php echo $pass['ut_qty'];?>">
										</div>
									</div>
								</div>
								<div class="form-group row">						
									<div class="col col-md-5 offset-1">
										<div class="input-group mb-4">
											<span class="input-group-text" style="width:280px;">ULTRATECH SUPER PPC LAMINATED</span>
											<input type="text" name="super_qty" class="form-control" value="<?php echo $pass['super_qty'];?>">
										</div>
									</div>
								</div>						
							</div>					
						</div>
					</div>
					<div class="card-footer" style="background-color:#5ca1bf;padding:1px;"></div>
					<p id="displayError" style="color:red;"></p>			
					<button id="updatebtn" class="btn" style="width:100px;font-size:18px;background-color:#5ca1bf;color:white;"><i class="fa fa-save"></i> Save</button>					
				</div>	
			</div>			
		</form>
		
		<button type="button" class="btn" style="float:right;margin-right:150px;background-color:#E6717C;color:#FFFFFF" data-toggle="modal" data-target="#deleteModal">
		<i class="far fa-trash-alt"></i>&nbsp;&nbsp;Delete</button>	
		<br/><br/>		
		<script src="edit.js"></script>
	</body>
	</html>																																								<?php
	mysqli_close($con);
}
else
	header("Location:../index/home.php");
