<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require '../connect.php';
if(isset($_SESSION["user_name"]))
{		
	$list = $_GET['list'];	
	$result = mysqli_query($con,"SELECT * FROM nas_sale WHERE sales_id='" . $_GET["sales_id"] . "'") or die(mysqli_error($con));	
	$row= mysqli_fetch_array($result,MYSQLI_ASSOC);
	
	$sheetQuery = mysqli_query($con,"SELECT * FROM sheets WHERE site='" . $_GET["sales_id"] . "'") or die(mysqli_error($con));	
	$sheet= mysqli_fetch_array($sheetQuery,MYSQLI_ASSOC);	

	$products = mysqli_query($con,"SELECT id,name FROM products WHERE status = 1 ORDER BY id ASC") or die(mysqli_error($con));	
	$arObjects = mysqli_query($con,"SELECT id,name,type,shop_name FROM ar_details ORDER BY name") or die(mysqli_error($con));	
	foreach($arObjects as $ar)
	{
		if($ar['type'] != 'Engineer Only')
			$arMap[$ar['id']] = $ar['name']; 
		if($ar['type'] == 'Engineer' || $ar['type'] == 'Contractor' || $ar['type'] == 'Engineer Only')
			$engMap[$ar['id']] = $ar['name'];
		
		$shopName = strip_tags($ar['shop_name']); 
		$shopNameMap[$ar['id']] = $shopName;
	}																																						?>

	<div class="modal fade" id="sheetModal">
	  <div class="modal-dialog modal-lg">
		<div class="modal-content">
		  <?php	
			if(isset($sheet))
			{?>
				<div class="modal-header" style="background-color:#F2CF5B;color:white">					
					<h4 class="modal-title"><i class="far fa-edit"></i>&nbsp;&nbsp;Edit Sheet Request</h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				</div>																														<?php
			}
			else
			{?>
				<div class="modal-header" style="background-color:#7dc37d;color:white">
					<h4 class="modal-title"><i class="fas fa-plus"></i>&nbsp;&nbsp;New Sheet Request</h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				</div>																														<?php
				
			}?>
		  <form class="well form-horizontal" id="form1" method="post" action="sheet.php" autocomplete="off">
			  <div class="modal-body">																					<?php
				  if(isset($sheet))
				  {																										?>
					<input type="text" hidden name="id" value="<?php echo $sheet['id'];?>"> 							<?php
				  }																										?>					  
				  <input type="text" hidden name="site" value="<?php echo $row['sales_id'];?>">
				  <input type="text" hidden name="sheet_bags" value="<?php echo $row['qty'];?>">
				  <input type="text" hidden name="sheet_shop" value="<?php echo $shopNameMap[$row['ar_id']];?>" >
				  <input hidden name="clicked_from" value="<?php echo $list;?>">
				  <fieldset>
					 <div class="form-group row" style="text-align:left;">
						<label class="col-md-2 control-label">Date</label>
						<div class="col-md-4 inputGroupContainer">
						   <div class="input-group-prepend">
								<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
								<input type="text" name="sheetDate" id="sheetDate" class="form-control" required="true" value="<?php if(isset($sheet)) echo date("d-m-Y", strtotime($sheet['date'])); else echo date("d-m-Y", strtotime($row['entry_date']. ' +1 day'));?>">
						   </div>
						</div>
					 </div>
					 <div class="form-group row">
						<label class="col-md-2 control-label">Cust Name</label>
						<div class="col-md-4 inputGroupContainer">
						   <div class="input-group">
								<span class="input-group-text"><i class="fas fa-user-tie"></i></span>
								<input type="text" name="sheet_customer_name" id="sheet_customer_name" class="form-control" value="<?php if(isset($sheet)) echo $sheet['customer_name']; else echo $row['customer_name']?>">
							</div>
						</div>
					 </div>
					 <div class="form-group row">
						<label class="col-md-2 control-label">Cust Phone</label>
						<div class="col-md-4 inputGroupContainer">
						   <div class="input-group-prepend">
								<span class="input-group-text"><i class="fas fa-mobile-alt"></i></span>
								<input type="text" name="sheet_customer_phone" id="sheet_customer_phone" class="form-control" value="<?php if(isset($sheet)) echo $sheet['customer_phone']; else echo $row['customer_phone']?>">
							</div>
						</div>
					 </div>
					 <div class="form-group row">
						<label class="col-md-2 control-label">Mason Name</label>
						<div class="col-md-4 inputGroupContainer">
						   <div class="input-group-prepend">
								<span class="input-group-text"><i class="far fa-user"></i></span>
								<input type="text" name="sheet_mason_name" id="sheet_mason_name" class="form-control" value="<?php if(isset($sheet)) echo $sheet['mason_name'];?>">
							</div>
						</div>
					 </div>
					 <div class="form-group row">
						<label class="col-md-2 control-label">Mason Phone</label>
						<div class="col-md-4 inputGroupContainer">
						   <div class="input-group-prepend">
								<span class="input-group-text"><i class="fas fa-mobile-alt"></i></span>
								<input type="text" name="sheet_mason_phone" id="sheet_mason_phone" class="form-control" value="<?php if(isset($sheet)) echo $sheet['mason_phone'];?>">
							</div>
						</div>
					 </div>
					 <div class="form-group row">
						<label class="col-md-2 control-label">Area & Location</label>
						<div class="col-md-7 inputGroupContainer">
						   <div class="input-group-prepend">
								<span class="input-group-text"><i class="fas fa-map-marker-alt"></i></i></span>
								<textarea name="sheet_area" id="sheet_area" class="form-control" required><?php if(isset($sheet)) echo $sheet['area']; else echo $row['address1'].', '.$row['address2']?></textarea>
							</div>
						</div>
					 </div>
					 <div class="form-group row">
						<label class="col-md-2 control-label">Remarks</label>
						<div class="col-md-7 inputGroupContainer">
						   <div class="input-group">
								<div class="input-group-prepend">
									<span class="input-group-text" id="sheet_remarks"><i class="far fa-clipboard"></i></span>
								</div>	
								<textarea name="sheet_remarks" id="sheet_remarks" class="form-control"><?php if(isset($sheet)) echo $sheet['remarks'];?></textarea>
							</div>
						</div>
					 </div>
					 <div class="form-group row">
						<label class="col-md-2 control-label">Truck</label>
						<div class="col-md-4 inputGroupContainer">
						   <div class="input-group-prepend">
								<span class="input-group-text"><i class="fas fa-truck-moving"></i></span>
								<input type="text" name="sheet_truck" id="sheet_truck" class="form-control" value="<?php if(isset($row['truck_no'])) echo $row['truck_no'];?>">
							</div>
						</div>
					 </div>
					 <div class="form-group row">
						<label class="col-md-2 control-label">Driver</label>
						<div class="col-md-4 inputGroupContainer">
						   <div class="input-group-prepend">
								<span class="input-group-text"><i class="far fa-id-card"></i></i></span>
								<input type="text" name="driver_name" id="driver_name" class="form-control">
							</div>
						</div>
					 </div>
					 <div class="form-group row">
						<label class="col-md-2 control-label">Driver Phone</label>
						<div class="col-md-4 inputGroupContainer">
						   <div class="input-group-prepend">
								<span class="input-group-text"><i class="fas fa-mobile-alt"></i></span>
								<input type="text" required name="driver_phone" id="driver_phone" class="form-control">
							</div>
						</div>
					 </div>					 
				  </fieldset>
			  </div>
			  <div class="modal-footer"><?php 
				if(isset($sheet))
				{																												?>
					<button class="btn" style="background-color:#F2CF5B;color:white;" type="submit"><i class="far fa-edit"></i>&nbsp;&nbsp;Update</button><?php
				}	
				else
				{																												?>	
					<button class="btn" style="background-color:#7dc37d;color:white;" type="submit"><i class="fas fa-plus"></i>&nbsp;&nbsp;Add Request</button>  <?php
				}																												?>
			  </div>
		  </form>
		</div>
	  </div>
	</div>
	  <?php
}
else
	header( "Location: ../index.php" );