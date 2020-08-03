<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require '../connect.php';
if(isset($_SESSION["user_name"]))
{			
	$clients = mysqli_query($con,"SELECT id,name FROM clients ORDER BY name ASC");
	$products= mysqli_query($con,"SELECT id,name FROM products WHERE isActive = 1 ORDER BY name ASC");																?>
	
	<div class="modal fade" id="editSaleModal">
	  <div class="modal-dialog modal-xl" style="width:70%">
		<div class="modal-content">
			<div class="modal-header" style="background-color:#E6D478;color:white">
				<h4 class="modal-title"><i class="fa fa-bolt"></i>&nbsp;&nbsp;Edit Sale</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body editSaleBody">
				<form name="editSaleForm" id="editSaleForm" method="post" action="update.php" onsubmit="return validateForm()">
						<div class="row">
							<div class="col col-md-4 offset-1">
								<div class="input-group mb-3">
									<span class="input-group-text col-md-5"><i class="far fa-calendar-alt"></i>&nbsp;Date</span>
									<input type="text" required name="date" id="date-edit" class="form-control datepicker" autocomplete="off">
								</div>
							</div>
							<div class="col col-md-4 offset-2">
								<div class="input-group mb-3">
									<span class="input-group-text col-md-4"><i class="far fa-file-alt"></i>&nbsp;Bill No</span>
									<input type="text" name="bill" id="bill-edit" class="form-control">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col col-md-5 offset-1">
								<div class="input-group mb-3">
									<span class="input-group-text col-md-4"><i class="fa fa-address-card-o"></i>&nbsp;Client</span>
									<select required name="client" id="client-edit" class="form-control" style="line-height:20px;">
										<option value = "">---Select---</option>																			<?php
										foreach($clients as $client) 
										{																													?>
											<option value="<?php echo $client['id'];?>"><?php echo $client['name'];?></option>										<?php	
										}																													?>
									</select>
								</div>
							</div>
							<div class="col col-md-4 offset-1">
								<div class="input-group mb-3">
									<span class="input-group-text col-md-4"><i class="fas fa-truck-moving"></i>&nbsp;Truck No</span>
									<input type="text" name="truck" id="truck-edit" class="form-control">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col col-md-5 offset-1">
								<div class="input-group mb-3">
									<span class="input-group-text col-md-4"><i class="fa fa-shield"></i>&nbsp;Product</span>
									<select required name="product" id="product-edit" class="form-control" style="line-height:20px;">
										<option value = "">---Select---</option>																			<?php
										foreach($products as $product) 
										{																													?>
											<option value="<?php echo $product['id'];?>"><?php echo $product['name'];?></option>										<?php	
										}																													?>
									</select>
								</div>
							</div>
							<div class="col col-md-4 offset-1">
								<div class="input-group mb-3">
									<span class="input-group-text col-md-4"><i class="far fa-user"></i></i>&nbsp;Customer</span>
									<input type="text" name="customer" id="customer-edit" class="form-control">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col col-md-4 offset-1">
								<div class="input-group mb-3">
									<span class="input-group-text col-md-5"><i class="fab fa-buffer"></i>&nbsp;Quantity</span>
									<input type="text" required name="qty" id="qty-edit" class="form-control">
								</div>
							</div>
							<div class="col col-md-4 offset-2">
								<div class="input-group mb-3">
									<span class="input-group-text col-md-4"><i class="fas fa-mobile-alt"></i></i>&nbsp;Phone</span>
									<input type="text" name="phone" id="phone-edit" class="form-control">
								</div>
							</div>
						</div>							
						<div class="row">
							<div class="col col-md-4 offset-1">
								<div class="input-group mb-3">
									<span class="input-group-text col-md-5"><i class="fa fa-tags"></i>&nbsp;Bill Disc.</span>
									<input type="text" name="discount" id="discount-edit" class="form-control">
								</div>
							</div>
							<div class="col col-md-4 offset-2">
								<div class="input-group mb-3">
									<span class="input-group-text col-md-4"><i class="fa fa-rupee-sign"></i>&nbsp;Final Rate</span>
									<input type="text" id="final-rate-edit" readonly class="form-control">
								</div>
							</div>
						</div>													
						<div class="row">
							<div class="col col-md-5 offset-1">
								<div class="input-group mb-3">
									<span class="input-group-text col-md-4"><i class="fas fa-map-marker-alt"></i>&nbsp;Address</span>
									<textarea name="address" id="address-edit" class="form-control"></textarea>
								</div>
							</div>							
						</div>
						<div class="row">
							<div class="col col-md-5 offset-1">
								<div class="input-group mb-3">
									<span class="input-group-text col-md-4"><i class="far fa-comment-dots"></i>&nbsp;Remarks</span>
									<textarea name="remarks" id="remarks-edit" class="form-control"></textarea>
								</div>
							</div>							
						</div>							
						<br/><br/>
						<div class="row">
							<div class="col col-md-5 offset-5">
								<div class="input-group mb-3">
									<button type="submit" class="btn" style="width:120px;font-size:18px;background-color:#E6D478;color:white;"><i class="fa fa-save"></i> Update</button>&nbsp;&nbsp;&nbsp;
									<button class="btn btn-error" id="delete" style="width:120px;font-size:18px;background-color:#E6717C;color:white;"><i class="fa fa-trash"></i> Delete</button>
								</div>
							</div>							
						</div>														
					<input readonly hidden name="id" id="id-edit"/>
					<input readonly hidden id="rate-edit"/>
					<input readonly hidden id="wd-edit"/>
					<input readonly hidden id="cd-edit"/>
					<input readonly hidden id="sd-edit"/>
				</form>
			</div>
		</div>
		<div class="modal-footer">
		</div>
		</div>
	  </div>
	  <?php
}
else
	header( "Location: ../index.php" );