<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
if(isset($_SESSION["user_name"]) && $_SESSION["role"] != 'marketing')
{
	require '../connect.php';
	require '../navbar.php';
	

	$result = mysqli_query($con,"SELECT * FROM truck_details WHERE id='" . $_GET["id"] . "'") or die(mysqli_error($con));
	$row= mysqli_fetch_array($result,MYSQLI_ASSOC);	

	$vehicle_types = mysqli_query($con,"SELECT * FROM vehicle_type");
	$vehicle_areas = mysqli_query($con,"SELECT * FROM vehicle_area");
	?>
	
	
	<html>
	<head>
		<title>Edit Truck <?php echo $row['number']; ?></title>
		<link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" rel="stylesheet" type="text/css">
		<link href="../css/styles.css" rel="stylesheet" type="text/css">
		<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/jquery.tablesorter.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/jquery.tablesorter.widgets.min.js"></script>
		<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet"/>
		<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
		<script src='edit.js' type='text/javascript'></script>
	</head>
	<body>
		<nav class="navbar navbar-light bg-light sticky-top bottom-nav">
			<span class="navbar-brand" style="font-size:25px;margin-left:40%;"><i class="fa fa-bolt"></i> Truck</span>
		</nav>
		<br/><br/>
		<div id="snackbar"><i class="fa fa-check"></i>&nbsp;&nbsp;Updated successfull !!!</div>
		<form name="editForm" id="editForm" method="post" action="update.php">
			<input hidden name="id" id="id" value="<?php echo $row['id'];?>">
			<div style="width:100%;">
				<div style="padding-bottom:5px;margin-left:30%">
					<div class="card" style="width:45%;">
						<div class="card-header" style="background-color:#f2cf5b;font-size:20px;font-weight:bold;color:white">Truck <?php echo $row['number']; ?></div>
						<div class="card-body">
							<p id="insertError" style="color:red;"></p>							
							<div class="col col-md-8 offset-1">
								<div class="input-group mb-3">
									<span class="input-group-text col-md-4"><i class="fa fa-truck"></i>&nbsp;Number</span>
									<input type="text" name="number" id="number" class="form-control" autocomplete="off" value="<?php echo $row['number'];?>">
								</div>
							</div>
							<div class="col col-md-8 offset-1">
								<div class="input-group mb-3">
									<span class="input-group-text col-md-4"><i class="fa fa-user"></i>&nbsp;Driver</span>
										<input type="text" name="driver" id="driver" class="form-control" autocomplete="off" value="<?php echo $row['driver'];?>">
								</div>
							</div>
							<div class="col col-md-8 offset-1">
								<div class="input-group mb-3">
									<span class="input-group-text col-md-4"><i class="fa fa-mobile"></i>&nbsp;Phone</span>
									<input type="text" name="phone" id="phone" class="form-control" autocomplete="off" value="<?php echo $row['phone'];?>">
								</div>
							</div>
							<div class="col col-md-10 offset-1">
								<div class="input-group mb-3">
									<span class="input-group-text col-md-4"><i class="fa fa-address-card"></i>&nbsp;License No</span>
									<input type="text" name="license_no" id="license_no" class="form-control" autocomplete="off" value="<?php echo $row['license_no'];?>">
								</div>
							</div>
							<div class="col col-md-10 offset-1">
								<div class="input-group mb-3">
									<span class="input-group-text col-md-4"><i class="fa fa-square"></i>&nbsp;Vehicle Type</span>
									<select name="vehicle_type" id="vehicle_type" class="form-control" style="width:60%">
										<option value = "">---Select---</option>																						<?php
										foreach($vehicle_types as $vehicle_type) 
										{																							?>
											<option value="<?php echo $vehicle_type['id'];?>" <?php if($vehicle_type['id'] == $row['vehicle_type']) echo 'selected';?>><?php echo $vehicle_type['type'];?></option>			<?php	
										}																							?>
									</select>
								</div>
							</div>
							<div class="col col-md-10 offset-1">
								<div class="input-group mb-3">
									<span class="input-group-text col-md-4"><i class="fa fa-map"></i>&nbsp;Vehicle Area</span>
									<select name="vehicle_area" id="vehicle_area" class="form-control" style="width:60%">
										<option value = "">---Select---</option>																						<?php
										foreach($vehicle_areas as $vehicle_area) 
										{																							?>
											<option value="<?php echo $vehicle_area['id'];?>" <?php if($vehicle_area['id'] == $row['vehicle_area']) echo 'selected';?>><?php echo $vehicle_area['area'];?></option>			<?php	
										}																							?>
									</select>
								</div>
							</div>
							<br/><br/>
							<div class="row">
								<div class="col col-md-5 offset-5">
									<div class="input-group mb-3">
										<button type="submit" class="btn" id="saveNew" style="width:100px;font-size:18px;background-color:#54698D;color:white;"><i class="fa fa-save"></i> Save</button>
									</div>
								</div>
							</div>
						</div>
						<div class="card-footer" style="background-color:#f2cf5b;padding:1px;"></div>
					</div>						
				</div>
			</div>
			<br/><br/><br/><br/>		
		</form>
		<br/><br/><br/><br/>
	</body>
	</html>																																								<?php
	mysqli_close($con);
}
else
	header("Location:../index/home.php");
