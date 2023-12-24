<?php
if(isset($_SESSION["user_name"]))
{
	require '../connect.php';
    
// Populate maps for SAP CODE and SHOP NAME
	$arObjects = mysqli_query($con,"SELECT id,name,sap_code,shop_name FROM ar_details ORDER BY name");
	foreach($arObjects as $arObject)
	{
		$arId = $arObject['id'];
		$clientMap[$arId] = $arObject['name'];  
		$shopName = strip_tags($arObject['shop_name']);
		$shopNameMap[$arId] = $shopName;
	}
	
	$shopNameArray = json_encode($shopNameMap);
	$shopNameArray = str_replace('\n',' ',$shopNameArray);
	$shopNameArray = str_replace('\r',' ',$shopNameArray);																																?>

	<meta name="viewport" content="width=device-width, initial-scale=1">
	<div class="modal fade" id="newModal">
	  <div class="modal-dialog modal-lg modal-fullscreen-sm-down">
		<div class="modal-content">
			<div class="modal-header" style="background-color:#54698D;color:white">
				<h4 class="modal-title">&nbsp;&nbsp;New Point %</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
			<form name="newRedemptionForm" id="newRedemptionForm" method="post" action="insert.php">
				<div class="modal-body">
					<br/>
					<p id="insertError" style="color:red;"></p>
					<div class="col col-md-4 offset-1">
						<div class="input-group mb-3">
							<span class="input-group-text" style="width:120px;">&nbsp;Year</span>
							<input type="text" required name="year" id="year" class="form-control" autocomplete="off">
						</div>
					</div>
					<div class="col col-md-4 offset-1">
						<div class="input-group mb-3">
							<span class="input-group-text" style="width:120px;">&nbsp;Month</span>
							<select required name="month" id="month" class="form-select" style="line-height:20px;">
								<option value = "">---Select---</option>
								<option value = "1">Jan</option>
								<option value = "2">Feb</option>
								<option value = "3">Mar</option>
								<option value = "4">Apr</option>
								<option value = "5">May</option>
								<option value = "6">Jun</option>
								<option value = "7">Jul</option>
								<option value = "8">Aug</option>
								<option value = "9">Sep</option>
								<option value = "10">Oct</option>
								<option value = "11">Nov</option>
								<option value = "12">Dec</option>
							</select>
						</div>
					</div>					
					<div class="col col-md-6 offset-1">
						<div class="input-group mb-3">
							<span class="input-group-text" style="width:120px;"><i class="fa fa-address-card-o"></i>&nbsp;AR</span>
							<select required name="ar" id="ar" class="form-select" style="line-height:20px;">
								<option value = "">---Select---</option>																			<?php
								foreach($clientMap as $id => $name) 
								{																													?>
									<option value="<?php echo $id;?>"><?php echo $name;?></option>										<?php	
								}																													?>
							</select>
						</div>
					</div>
					<div class="col col-md-4 offset-1">
						<div class="input-group mb-3">
							<span class="input-group-text" style="width:120px;">&nbsp;Point %</span>
							<input type="text" required name="points" id="points" class="form-control" autocomplete="off">
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
				<div class="modal-footer">
				</div>
			</form>												
		</div>
		<div class="modal-footer"></div>
	  </div>
	</div>																																																<?php
}
else
	header( "Location: ../index/home.php" );	