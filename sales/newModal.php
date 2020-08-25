<?php
if(isset($_SESSION["user_name"]))
{
	require '../connect.php';
    
// Populate maps for SAP CODE and SHOP NAME
	$products = mysqli_query($con,"SELECT id,name FROM products WHERE status = 1 ORDER BY id ASC");

	$arObjects = mysqli_query($con,"SELECT id,name,sap_code,shop_name,type FROM ar_details WHERE type <> 'Engineer Only' OR type IS NULL ORDER BY name ASC");
	foreach($arObjects as $arObject)
	{
		$arId = $arObject['id'];
		
		$shopName = strip_tags($arObject['shop_name']);
		$shopNameMap[$arId] = $shopName;
	}
	
	$shopNameArray = json_encode($shopNameMap);
	$shopNameArray = str_replace('\n',' ',$shopNameArray);
	$shopNameArray = str_replace('\r',' ',$shopNameArray);	
	
	$engineerObjects = mysqli_query($con,"SELECT id,name,sap_code,shop_name FROM ar_details WHERE type LIKE '%Engineer%' OR type = 'Contractor' ORDER BY name ASC");					?>
	
	<div class="modal fade" id="saleModal">
	  <div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header" style="background-color:#54698D;color:white">
				<h4 class="modal-title"><i class="fa fa-bolt"></i>&nbsp;&nbsp;New Sale</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body">
				<form name="newSaleForm" method="post" action="insert.php" onsubmit="return validateForm()">
						<div class="row">
							<div class="col col-md-4 offset-1">
								<div class="input-group mb-3">
									<span class="input-group-text col-md-5"><i class="far fa-calendar-alt"></i>&nbsp;Date</span>
									<input type="text" id="date" class="form-control datepicker" name="date" required value="<?php echo date('d-m-Y'); ?>" autocomplete="off"/>
								</div>
							</div>
							<div class="col col-md-4 offset-2">
								<div class="input-group mb-3">
									<span class="input-group-text col-md-4"><i class="far fa-file-alt"></i>&nbsp;Bill No</span>
									<input type="text" name="bill" class="form-control">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col col-md-5 offset-1">
								<div class="input-group mb-3">
									<span class="input-group-text col-md-4"><i class="fa fa-address-card-o"></i>&nbsp;AR</span>
									<select name="ar" id="ar" required class="form-control" style="width:250px;">
										<option value = "">---Select---</option>
																																	<?php
										foreach($arObjects as $ar) 
										{																							?>
											<option value="<?php echo $ar['id'];?>"><?php echo $ar['name'];?></option>			<?php	
										}																							?>
									</select>
								</div>
							</div>
							<div class="col col-md-4 offset-1">
								<div class="input-group mb-3">
									<span class="input-group-text col-md-4"><i class="fas fa-truck-moving"></i>&nbsp;Truck No</span>
									<input type="text" name="truck" class="form-control">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col col-md-5 offset-1">
								<div class="input-group mb-3">
									<span class="input-group-text col-md-4"><i class="fa fa-suitcase"></i>&nbsp;Engineer</span>
									<select name="engineer" id="engineer"  class="form-control" style="width:250px;">
										<option value = "">---Select---</option>
																																	<?php
										foreach($engineerObjects as $eng) 
										{																							?>
											<option value="<?php echo $eng['id'];?>"><?php echo $eng['name'];?></option>			<?php	
										}																							?>
									</select>
								</div>
							</div>
							<div class="col col-md-4 offset-1">
								<div class="input-group mb-3">
									<span class="input-group-text col-md-4"><i class="fa fa-money"></i></i>&nbsp;Order No</span>
									<input type="text" name="order_no" class="form-control">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col col-md-4 offset-1">
								<div class="input-group mb-3">
									<span class="input-group-text col-md-5"><i class="fa fa-shield"></i>&nbsp;Product</span>
									<select name="product" id="product" required class="form-control">									<?php
										foreach($products as $product) 
										{																							?>
											<option value="<?php echo $product['id'];?>"><?php echo $product['name'];?></option>		<?php	
										}																							?>
									</select>
								</div>
							</div>
							<div class="col col-md-4 offset-2">
								<div class="input-group mb-3">
									<span class="input-group-text col-md-4"><i class="far fa-user"></i></i>&nbsp;Customer</span>
									<input type="text" name="customerName" class="form-control">
								</div>
							</div>
						</div>							
						<div class="row">
							<div class="col col-md-3 offset-1">
								<div class="input-group mb-3">
									<span class="input-group-text col-md-5"><i class="fab fa-buffer"></i>&nbsp;Quantity</span>
									<input type="text" name="qty" required class="form-control" pattern="[0-9]+" title="Input a valid number">
								</div>
							</div>
							<div class="col col-md-4 offset-3">
								<div class="input-group mb-3">
									<span class="input-group-text col-md-4"><i class="fas fa-mobile-alt"></i>&nbsp;Phone</span>
									<input type="text" name="customerPhone" class="form-control">
								</div>
							</div>
						</div>													
						<div class="row">
							<div class="col col-md-3 offset-1">
								<div class="input-group mb-3">
									<span class="input-group-text col-md-5"><i class="fa fa-tags"></i>&nbsp;Bill Disc</span>
									<input type="text" name="bd" id="bd" class="form-control">
								</div>
							</div>
						</div>																			
						<div class="row">
							<div class="col col-md-5 offset-1">
								<div class="input-group mb-3">
									<span class="input-group-text" style="width:24%;"><i class="far fa-comment-dots"></i>&nbsp;Remarks</span>
									<textarea name="remarks" id="remarks" class="form-control"></textarea>
								</div>
							</div>
							<div class="col col-md-5 offset-1">
								<div class="input-group mb-3">
									<span class="input-group-text" style="width:26%"><i class="fas fa-map-marker-alt"></i>&nbsp;Address</span>
									<textarea name="address1" id="address1" class="form-control"></textarea>
								</div>
							</div>
						</div>
							<div class="row">
								<div class="col col-md-3 offset-1">
									<div class="input-group">
										<span class="input-group-text col-md-5"><i class="fa fa-rupee-sign"></i>&nbsp;Final Rate</span>
										<input readonly id="final" class="form-control">
									</div>
								</div>
								<div class="col col-md-4 offset-3">
									<div class="input-group mb-3">
										<span class="input-group-text col-md-4"><i class="fa fa-address-card-o"></i>&nbsp;Shop</span>
										<input type="text" readonly name="shopName" id="shopName" class="form-control">
									</div>
								</div>
							</div>						
						<br/><br/>
						<div class="row">
							<div class="col col-md-5 offset-5">
								<div class="input-group mb-3">
									<button type="submit" class="btn" style="width:100px;font-size:18px;background-color:#54698D;color:white;"><i class="fa fa-save"></i> Save</button>				 
								</div>
							</div>							
						</div>			
						<input hidden name="sql" value="<?php echo $_GET['sql'];?>"/>
						<input hidden name="range" value="<?php echo $_GET['range'];?>"/>
				</form>
				<div class="col col-md-3 offset-1">
					<div class="input-group">
						<span class="input-group-text col-md-6">General Rate</span>
						<input readonly id="rate" class="form-control">
					</div>
				</div>				
				<div class="col col-md-3 offset-1">
					<div class="input-group">
						<span class="input-group-text col-md-6">Wagon Disc</span>
						<input readonly id="wd" class="form-control">
					</div>
				</div>								
				<div class="col col-md-3 offset-1">
					<div class="input-group">
						<span class="input-group-text col-md-6">Cash Disc</span>
						<input readonly id="cd" class="form-control">
					</div>
				</div>												
			</div>				
		</div>
		<div class="modal-footer"></div>
	  </div>
	</div>																																																<?php
}
else
	header( "Location: ../index/home.php" );	