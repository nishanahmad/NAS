<?php
if(isset($_SESSION["user_name"]))
{
	require '../connect.php';
    
// Populate maps for SAP CODE and SHOP NAME
	$consignors = mysqli_query($con,"SELECT * FROM consignors") or die(mysqli_error($con));	
	$godowns = mysqli_query($con,"SELECT * FROM consignors") or die(mysqli_error($con));	
?>

	<style>
	#country-list{list-style:none;margin-top:-3px;margin-left:120px;padding:0;width:190px;}
	#country-list li{padding: 10px; background: #f0f0f0; border-bottom: #bbb9b9 1px solid;}
	#country-list li:hover{background:#ece3d2;cursor: pointer;}
	#phone{padding: 10px;border: #a8d4b1 1px solid;border-radius:4px;}	
	</style>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<div class="modal fade" id="passModal">
	  <div class="modal-dialog modal-xl modal-fullscreen-sm-down">
		<div class="modal-content">
			<div class="modal-header" style="background-color:#54698D;color:white">
				<h4 class="modal-title"><i class="fa fa-bolt"></i>&nbsp;&nbsp;New Gate Pass</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body">
				<br/>
				<p id="insertError" style="color:red;"></p>
				<form name="newForm" id="newForm" method="post" action="insert.php">
					<div align="center" style="padding-bottom:5px;">			
						<div style="width:90%;margin-left:5%">
							<div class="card">
								<div class="card-header"></div>
								<div class="card-body" style="margin-left:15px;">
									<br/>
									<div class="row">					
										<div class="form-group row">						
											<div class="col col-md-4">
												<div class="input-group mb-3">
													<span class="input-group-text" style="width:100px;">Token No</span>
													<input type="text" name="token" id="token" class="form-control" autocomplete="off">
												</div>
											</div>																					
											<div class="col col-md-4">
												<div class="input-group mb-3">
													<span class="input-group-text" style="width:100px;">Date</span>
													<input type="date" name="date" id="dateId" class="form-control" value="<?php echo date('Y-m-d');?>">
												</div>
											</div>
											<div class="col col-md-4">
												<div class="input-group mb-3">
													<span class="input-group-text">;Vehicle</span>
													<input type="vehicle" name="vehicle" id="dateId" class="form-control">
												</div>
											</div>											
										</div>
									</div>
									<div class="row">					
										<div class="form-group row">						
											<div class="col col-md-4">
												<div class="input-group mb-3">
													<span class="input-group-text" style="width:100px;">SL No</span>
													<input type="text" name="sl_no" id="sl_no" class="form-control" autocomplete="off">
												</div>
											</div>																					
											<div class="col col-md-4">
												<div class="input-group mb-3">
													<span class="input-group-text" style="width:100px;">Order No</span>
													<input type="text" name="order_no" id="order_no" class="form-control" autocomplete="off">
												</div>
											</div>							
											<div class="col col-md-3">
												<div class="input-group mb-3">
													<span class="input-group-text" style="width:100px;">Time</span>
													<input type="text" name="time" id="time" class="form-control" autocomplete="off">
												</div>
											</div>							
										</div>
									</div>
									<div class="row">					
										<div class="form-group row">						
											<div class="col col-md-4">
												<div class="input-group mb-3">
													<span class="input-group-text" style="width:100px;">Consignor</span>
													<select name="consignor" required id="consignor" class="form-control">
														<option value = "">---Select---</option>																						<?php
														foreach($consignors as $consignor) 
														{																							?>
															<option value="<?php echo $consignor['id'];?>"><?php echo $consignor['name'];?></option>			<?php	
														}																							?>
													</select>
												</div>
											</div>																					
											<div class="col col-md-4">
												<div class="input-group mb-3">
													<span class="input-group-text" style="width:100px;">From</span>
													<select name="from_godown" id="from_godown" class="form-control">
														<option value = "">---Select---</option>																						<?php
														foreach($godowns as $godown) 
														{																							?>
															<option value="<?php echo $godown['id'];?>"><?php echo $godown['godown'];?></option>			<?php	
														}																							?>
													</select>
												</div>
											</div>							
											<div class="col col-md-3">
												<div class="input-group mb-3">
													<span class="input-group-text" style="width:100px;">Delivery At</span>
													<select name="delivery_at" id="delivery_at" class="form-control">
														<option value = "Kannur">Kannur</option>
													</select>
												</div>
											</div>							
										</div>
									</div>
									<div class="row">					
										<div class="form-group row">						
											<div class="col col-md-4">
												<div class="input-group mb-3">
													<span class="input-group-text" style="width:100px;">Driver</span>
													<input type="text" name="driver" id="driver" class="form-control">
												</div>
											</div>																					
											<div class="col col-md-4">
												<div class="input-group mb-3">
													<span class="input-group-text" style="width:100px;">Phone</span>
													<input type="text" name="driver_phone" id="driver_phone" class="form-control" autocomplete="off">
												</div>
											</div>							
											<div class="col col-md-4">
												<div class="input-group mb-3">
													<span class="input-group-text" style="width:100px;">License No</span>
													<input type="text" name="driver_license_no" id="driver_license_no" class="form-control" autocomplete="off">
												</div>
											</div>							
										</div>
									</div>					
									<br/><br/>
									<h3 style="margin-left:10%">Particulars</h3>
									<div class="row">					
										<div class="form-group row">						
											<div class="col col-md-6 offset-1">
												<div class="input-group mb-3">
													<span class="input-group-text" style="width:280px;">ULTRATECH PPC LAMINATED</span>
													<input type="text" name="ut_qty" class="form-control" value="<?php echo '0';?>">
												</div>
											</div>
										</div>
										<div class="form-group row">						
											<div class="col col-md-6 offset-1">
												<div class="input-group mb-4">
													<span class="input-group-text" style="width:280px;">ULTRATECH SUPER PPC LAMINATED</span>
													<input type="text" name="super_qty" class="form-control" value="<?php echo '0';?>">
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
			</div>				
		</div>
		<div class="modal-footer"></div>
	  </div>
	</div>																																				<?php
}
else
	header( "Location: ../index/home.php" );	