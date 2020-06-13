<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
if(isset($_SESSION["user_name"]))
{
	require '../connect.php';
	require 'getHistory.php';
	echo "LOGGED USER : ".$_SESSION["user_name"] ;	
	$list = $_GET['list'];
	$engMap[null] = null;
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

		$shopNameArray = json_encode($shopNameMap);
		$shopNameArray = str_replace('\n',' ',$shopNameArray);
		$shopNameArray = str_replace('\r',' ',$shopNameArray);		
	}
	$result = mysqli_query($con,"SELECT * FROM nas_sale WHERE sales_id='" . $_GET["sales_id"] . "'") or die(mysqli_error($con));	
	$row= mysqli_fetch_array($result,MYSQLI_ASSOC);
	$historyList = (getHistory($row['sales_id']));
	
	$sheetQuery = mysqli_query($con,"SELECT * FROM sheets WHERE site='" . $_GET["sales_id"] . "'") or die(mysqli_error($con));	
	$sheet= mysqli_fetch_array($sheetQuery,MYSQLI_ASSOC);
	?>

	<html>
	<head>
		<title>Edit Sale <?php echo $row['sales_id']; ?></title>
		<link rel="stylesheet" type="text/css" href="../css/jquery-ui.css">
		<link rel="stylesheet" type="text/css" href="../css/newEdit.css" />
		<link rel="stylesheet" href="../css/button.css">
		<link href='../select2/dist/css/select2.min.css' rel='stylesheet' type='text/css'>
		<script src="https://kit.fontawesome.com/742221945b.js" crossorigin="anonymous"></script>
		<script type="text/javascript" language="javascript" src="../js/jquery.js"></script>
		<script type="text/javascript" language="javascript" src="../js/jquery-ui.min.js"></script>	
		<link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
		<script type="text/javascript" src="../js/bootstrap.min.js"></script> 

		<script src='../select2/dist/js/select2.min.js' type='text/javascript'></script>
		<script>
		var shopNameList = '<?php echo $shopNameArray;?>';
		var shopName_array = JSON.parse(shopNameList);
		var shopNameArray = shopName_array;									
		function arRefresh()
		{
			var arId = $('#ar').val();
			var shopName = shopNameArray[arId];
			$('#shopName').val(shopName);
		}								
			
		$(document).ready(function()
		{
			$("#ar").select2();
			$("#engineer").select2();
			
			var pickerOpts = { dateFormat:"dd-mm-yy"}; 
			$( "#datepicker" ).datepicker(pickerOpts);	
			$( "#sheetDate" ).datepicker(pickerOpts);	
			
			var arId = $('#ar').val();
			var shopName = shopNameArray[arId];
			$('#shopName').val(shopName);	
				
		
			var date = $("#datepicker").val();
			var product = $("#product").val();
			var client = $("#ar").val();
			
			$.ajax({
				type: "POST",
				url: "getRate.php",
				data:'date='+date+'&product='+product,
				success: function(data){
					var rate = data;
					$("#rate").val(rate);
					refreshRate();
				}
			});
			$.ajax({
				type: "POST",
				url: "checkEngineer.php",
				data:'client='+client,
				success: function(data){
					if(data.includes("Engineer"))
					{
						$("#wd").val(0);
						refreshRate();
					}
					else
					{
						$.ajax({
							type: "POST",
							url: "getWD.php",
							data:'product='+product+'&date='+date,
							success: function(data){
								$("#wd").val(data);
								refreshRate();
							}
						});										
					}
				}
			});
			$.ajax({
				type: "POST",
				url: "getCD.php",
				data:'date='+date+'&product='+product+'&client='+client,
				success: function(data){
					$("#cd").val(data);
					refreshRate();
				}
			});		
			$.ajax({
				type: "POST",
				url: "getSD.php",
				data:'date='+date+'&product='+product+'&client='+client,
				success: function(data){
					$("#sd").val(data);
					refreshRate();
				}
			});	

			$("#datepicker").change(function()
			{
				var date = $(this).val();
				var product = $("#product").val();
				var client = $("#ar").val();
				
				$.ajax({
					type: "POST",
					url: "getRate.php",
					data:'date='+date+'&product='+product,
					success: function(data){
						var rate = data;
						$("#rate").val(rate);
						refreshRate();
					}
				});
				$.ajax({
					type: "POST",
					url: "checkEngineer.php",
					data:'client='+client,
					success: function(data){
						if(data.includes("Engineer"))
						{
							$("#wd").val(0);
							refreshRate();
						}
						else
						{
							$.ajax({
								type: "POST",
								url: "getWD.php",
								data:'product='+product+'&date='+date,
								success: function(data){
									$("#wd").val(data);
									refreshRate();
								}
							});										
						}
					}
				});												
				$.ajax({
					type: "POST",
					url: "getCD.php",
					data:'date='+date+'&product='+product+'&client='+client,
					success: function(data){
						$("#cd").val(data);
						refreshRate();
					}
				});		
				$.ajax({
					type: "POST",
					url: "getSD.php",
					data:'date='+date+'&product='+product+'&client='+client,
					success: function(data){
						$("#sd").val(data);
						refreshRate();
					}
				});		
			});
			
			
			
			$("#product").change(function()
			{
				date = $("#datepicker").val();
				product = $(this).val();
				client = $("#ar").val();
				
				$.ajax({
					type: "POST",
					url: "getRate.php",
					data:'product='+product+'&date='+date,
					success: function(data){
						var rate = data;
						$("#rate").val(rate);
						refreshRate();
					}
				});			
				$.ajax({
					type: "POST",
					url: "getWD.php",
					data:'product='+product+'&date='+date,
					success: function(data){
						$("#wd").val(data);
						refreshRate();
					}
				});										
				$.ajax({
					type: "POST",
					url: "getCD.php",
					data:'product='+product+'&date='+date+'&client='+client,
					success: function(data){
						$("#cd").val(data);
						refreshRate();
					}
				});				
				$.ajax({
					type: "POST",
					url: "getSD.php",
					data:'product='+product+'&date='+date+'&client='+client,
					success: function(data){
						$("#sd").val(data);
						refreshRate();
					}
				});	
				$.ajax({
					type: "POST",
					url: "checkEngineer.php",
					data:'client='+client,
					success: function(data){
						if(data.includes("Engineer"))
						{
							$("#wd").val(0);
							refreshRate();
						}
						else
						{
							$.ajax({
								type: "POST",
								url: "getWD.php",
								data:'product='+product+'&date='+date,
								success: function(data){
									$("#wd").val(data);
									refreshRate();
								}
							});										
						}
					}
				});															
			});
			
			$("#ar").change(function()
			{
				var date = $("#datepicker").val();
				var product = $("#product").val();
				var client = $(this).val();
				$.ajax({
					type: "POST",
					url: "getCD.php",
					data:'client='+client+'&date='+date+'&product='+product,
					success: function(data){
						$("#cd").val(data);
						refreshRate();
					}
				});			
				$.ajax({
					type: "POST",
					url: "getSD.php",
					data:'client='+client+'&date='+date+'&product='+product,
					success: function(data){
						$("#sd").val(data);
						refreshRate();
					}
				});					
				$.ajax({
					type: "POST",
					url: "checkEngineer.php",
					data:'client='+client,
					success: function(data){
						if(data.includes("Engineer"))
						{
							$("#wd").val(0);
							refreshRate();
						}
						else
						{
							$.ajax({
								type: "POST",
								url: "getWD.php",
								data:'product='+product+'&date='+date,
								success: function(data){
									$("#wd").val(data);
									refreshRate();
								}
							});										
						}
					}
				});			
			});	
			$("#bd").change(function(){
				refreshRate();
			});						
		});

		function refreshRate()
		{
			var rate=document.getElementById("rate").value;
			var cd=document.getElementById("cd").value;
			var sd=document.getElementById("sd").value;
			var wd=document.getElementById("wd").value;
			var bd=document.getElementById("bd").value;
			
			$('#final').val(rate-cd-sd-wd-bd);
		}
		</script>
		<style>
			.close{
			  font-size: 35px;
			  color: red;
			}		
		</style>
	</head>
	<body>
		<form name="frmUser" method="post" action="update.php">
			<input hidden name="id" value="<?php echo $row['sales_id'];?>">
			<input hidden name="clicked_from" value="<?php echo $list;?>">
			<div style="width:100%;">
				<div align="center" style="padding-bottom:5px;">
					<a href="../index.php" class="link"><img alt='Home' title='Home' src='../images/home.png' width='50px' height='50px'/></a>&nbsp;&nbsp;
					<a href="todayList.php?ar=all" class="link"><img alt='List' title='List' src='../images/list_icon.jpg' width='50px' height='50px'/></a>
					<div style="float:right;margin-right:20px;"><?php
						if(isset($sheet))
						{?>
							<button type="button" class="btn" style="background-color:#F2CF5B;color:white;" data-toggle="modal" data-target="#sheetModal">
								<i class="far fa-edit fa-lg"></i>&nbsp;&nbsp;Sheet
							</button><?php
						}
						else
						{?>
							<button type="button" class="btn" style="background-color:#7dc37d;color:white;" data-toggle="modal" data-target="#sheetModal">
								<i class="fas fa-plus fa-lg"></i>&nbsp;&nbsp;Sheet
							</button><?php
						}?>
						<button type="button" class="btn" style="background-color:#2A739E;color:white;" data-toggle="modal" data-target="#historyModal">
							<i class="fa fa-history fa-lg"></i>&nbsp;&nbsp;History
						</button>				
					</div>					
				</div>
				<br>
				<div align ="center">
					<table border="0" cellpadding="10" cellspacing="0" width="80%" align="center" class="tblSaveForm">
						<tr class="tableheader">
							<td colspan="4" style="text-align:center;"><b><font size="4">Edit Sale <?php echo $row['sales_id']; ?> </font><b></td>
						</tr>
						<tr>
							<td><label>Date</label></td>
							<td><input type="text" id="datepicker" name="entryDate" class="txtField" 
								value="<?php 
										$originalDate1 = $row['entry_date'];
										$newDate1 = date("d-m-Y", strtotime($originalDate1));
										echo $newDate1; ?>">
							</td>

							<td><label>Bill No </label></td>
							<td><input type="text" name="bill" class="txtField" value="<?php echo $row['bill_no']; ?>"></td>
						</tr>
						<tr>
							<td><label>AR</label></td>
							<td><select name="ar" id="ar" required class="txtField" onChange="arRefresh();">
								<option value = "<?php echo $row['ar_id'];?>"><?php echo $arMap[$row['ar_id']];?></option>
								<?php
									foreach($arMap as $arId => $arName)
									{?>
										<option value="<?php echo $arId;?>"><?php echo $arName;?></option>			
							<?php	}
							?>
								  </select>
							</td>

							<td><label>Truck No </label></td>
							<td><input type="text" name="truck" class="txtField" value="<?php echo $row['truck_no']; ?>"></td>
						</tr>
						<tr>
							<td><label>Engineer</label></td>
							<td><select name="engineer" id="engineer" class="txtField">
									<option value="<?php echo $row['eng_id'];?>"><?php echo $engMap[$row['eng_id']];?></option>																																<?php
									foreach($engMap as $engId => $engName)
									{	
										if($engId != $row['eng_id'])
										{																																			?>
											<option value="<?php echo $engId;?>"><?php echo $engName;?></option><?php
										}																																			?>																																						<?php		
									}																																				?>
								  </select>
							</td>
							<td><label>Customer Name</label></td>
							<td><input type="text" name="customerName" class="txtField" value="<?php echo $row['customer_name']; ?>"></td>
						</tr>
						<tr>
							<td><label>Product</label></td>
							<td><select name="product" id="product" required class="txtField">									<?php
									foreach($products as $product) 
									{																							?>
										<option <?php if($row['product'] == $product['id']) echo 'selected';?> value="<?php echo $product['id'];?>"><?php echo $product['name'];?></option>		<?php	
									}																							?>
								</select>
							</td>
							<td><label>Address Part 1</label></td>
							<td><input type="text" name="address1" class="txtField" value="<?php echo $row['address1']; ?>"></td>
						</tr>
						<tr>
							<td><label>Qty</label></td>
							<td><input type="text" name="qty" required class="txtField" pattern="[0-9]+" value="<?php echo $row['qty'];?>" title="Input a valid number"></td>

							<td><label>Address Part 2</label></td>
							<td><input type="text" name="address2" class="txtField" value="<?php echo $row['address2']; ?>"></td>
						</tr>
						<tr>
							<td><label>Remarks</label></td>
							<td><input type="text" name="remarks" class="txtField" value="<?php echo $row['remarks']; ?>"></td>


							<td><label>Customer Phone</label></td>
							<td><input type="text" name="customerPhone" class="txtField" value="<?php echo $row['customer_phone']; ?>"></td>
						</tr>
						<tr>
							<td><label>Return</label></td>
							<td><input type="text" name="return" class="txtField" value="<?php echo $row['return_bag']; ?>"></td>

							<td><label>Shop</label></td>
							<td><input type="text" readonly name="shopName" id="shopName" class="txtField"></td>	
						</tr>
						<tr>
							<td><label>Bill Discount</label></td>
							<td><input type="text" name="bd" id="bd" class="txtField" pattern="[0-9]+" title="Input a valid number" value="<?php echo $row['discount'];?>"></td>			
						</tr>						
						<tr>
							<td><label>Final Rate</label></td>
							<td>
								<input readonly id="final" class="txtField">
							</td>			
							
							<td></td>
							<td></td>	
						</tr>
						<tr>
							<td colspan="4" align = "center"><input type="submit" name="submit" value="Submit" class="btnSubmit"></td>
						</tr>
					</table>
					<br/><br/>
					<table border="0" cellpadding="5" cellspacing="0" width="30%" align="left" style="margin-left:10%">
						<tr>
							<td><label>Rate</label></td>
							<td><input readonly id="rate"/></td>
						</tr>	
						<tr>	
							<td><label>Wagon Discount</label></td>
							<td><input readonly id="wd"/></td>								
						</tr>			
						<tr>
							<td><label>Cash Discount</label></td>
							<td><input readonly id="cd"/></td>
						</tr>	
							<td><label>Special Discount</label></td>
							<td><input readonly id="sd"/></td>				
					</table>	
				</div>
				<a href="delete.php?sales_id=<?php echo $row['sales_id'];?>" style="float:right;margin-right:150px;" class="btn btn-red" onclick="return confirm('Are you sure you want to permanently delete this entry ?')">DELETE</a>						
			</div>
			<br/><br/><br/><br/>		
		</form>
		<br/><br/><br/><br/>

		<!-- The Modal for history -->
		<div class="modal fade" id="historyModal">
		  <div class="modal-dialog modal-xl" style="width:60%">
			<div class="modal-content">
			  <div class="modal-header" style="background-color:#2A739E;color:white">
				<h4 class="modal-title"><i class="fa fa-history fa-lg"></i>&nbsp;&nbsp;History</h4>
			  </div>
			  <div class="modal-body">
				<i class="fa fa-user fa-lg"></i>&nbsp;&nbsp;Created By : <?php echo $row['entered_by'];?><br/>
				<i class="fa fa-calendar fa-sm"></i>&nbsp;&nbsp;Created On : <?php echo date('d-m-Y, h:i A', strtotime($row['entered_on']));?>
				<br/><br/>
				<section id="unseen">
					<table class="table table-bordered table-condensed" style="width:90%;">
						<tr>
							<th></th>
							<th><i class="fa fa-user"></i>&nbsp;Modified By</th>
							<th><i class="fa fa-history"></i>&nbsp;Old Value</th>
							<th><i class="fa fa-check"></i>&nbsp;New Value</th>
							<th><i class="fa fa-calendar"></i>&nbsp;Modified On</th>
						</tr>																																				<?php 
						foreach($historyList as $history)
						{																																					?>
							<tr>
								<td><?php echo $history['field'];?></td>
								<td><?php echo $history['edited_by'];?></td>
								<td><?php echo $history['old_value'];?></td>
								<td><?php echo $history['new_value'];?></td>
								<td><?php echo date('d-m-Y, h:i A', strtotime($history['edited_on']));?></td>
							</tr>																																			<?php	
						}																																					?>		
					</table>	
				</section>				
			  </div>
			  <div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
			  </div>
			</div>
		  </div>
		</div>

		<!-- The Modal for new Sheet -->
		<div class="modal fade" id="sheetModal">
		  <div class="modal-dialog modal-xl" style="width:40%">
			<div class="modal-content">
			  <?php	
				if(isset($sheet))
				{?>
					<div class="modal-header" style="background-color:#F2CF5B;color:white">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title"><i class="far fa-edit fa-lg"></i>&nbsp;&nbsp;Edit Sheet Request</h4><?php
				}
				else
				{?>
					<div class="modal-header" style="background-color:#7dc37d;color:white">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title"><i class="fas fa-plus fa-lg"></i>&nbsp;&nbsp;New Sheet Request</h4><?php
				}?>
			  </div>
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
						 <div class="form-group" style="text-align:left;">
							<label class="col-md-4 control-label">Date</label>
							<div class="col-md-6 inputGroupContainer">
							   <div class="input-group">
									<span class="input-group-addon"><i class="far fa-calendar-alt fa-lg"></i></span>
									<input type="text" name="sheetDate" id="sheetDate" placeholder="Date" class="form-control" required="true" value="<?php if(isset($sheet)) echo date("d-m-Y", strtotime($sheet['date']));?>">
							   </div>
							</div>
						 </div>
						 <div class="form-group">
							<label class="col-md-4 control-label">Customer Name</label>
							<div class="col-md-6 inputGroupContainer">
							   <div class="input-group">
									<span class="input-group-addon"><i class="fas fa-user-tie fa-lg"></i></span>
									<input type="text" name="sheet_customer_name" id="sheet_customer_name" placeholder="Customer Name" class="form-control" value="<?php if(isset($sheet)) echo $sheet['customer_name'];?>">
								</div>
							</div>
						 </div>
						 <div class="form-group">
							<label class="col-md-4 control-label">Customer Phone</label>
							<div class="col-md-6 inputGroupContainer">
							   <div class="input-group">
									<span class="input-group-addon"><i class="fas fa-mobile-alt fa-lg"></i></span>
									<input type="text" name="sheet_customer_phone" id="sheet_customer_phone" placeholder="Customer Phone" class="form-control" value="<?php if(isset($sheet)) echo $sheet['customer_phone'];?>">
								</div>
							</div>
						 </div>
						 <div class="form-group">
							<label class="col-md-4 control-label">Mason Name</label>
							<div class="col-md-6 inputGroupContainer">
							   <div class="input-group">
									<span class="input-group-addon"><i class="far fa-user fa-lg"></i></span>
									<input type="text" name="sheet_mason_name" id="sheet_mason_name" placeholder="Mason Name" class="form-control" value="<?php if(isset($sheet)) echo $sheet['mason_name'];?>">
								</div>
							</div>
						 </div>
						 <div class="form-group">
							<label class="col-md-4 control-label">Mason Phone</label>
							<div class="col-md-6 inputGroupContainer">
							   <div class="input-group">
									<span class="input-group-addon"><i class="fas fa-mobile-alt fa-lg"></i></span>
									<input type="text" name="sheet_mason_phone" id="sheet_mason_phone" placeholder="Mason Phone" class="form-control" value="<?php if(isset($sheet)) echo $sheet['mason_phone'];?>">
								</div>
							</div>
						 </div>
						 <div class="form-group">
							<label class="col-md-4 control-label">Area & Location</label>
							<div class="col-md-8 inputGroupContainer">
							   <div class="input-group">
									<span class="input-group-addon"><i class="fas fa-map-marker-alt fa-lg"></i></i></span>
									<textarea name="sheet_area" id="sheet_area" placeholder="Area & Location" class="form-control" required><?php if(isset($sheet)) echo $sheet['area'];?></textarea>
								</div>
							</div>
						 </div>
						 <div class="form-group">
							<label class="col-md-4 control-label">Remarks</label>
							<div class="col-md-8 inputGroupContainer">
							   <div class="input-group">
									<span class="input-group-addon"><i class="far fa-clipboard fa-lg"></i></span>
									<textarea name="sheet_remarks" id="sheet_remarks" placeholder="Remarks" class="form-control"><?php if(isset($sheet)) echo $sheet['remarks'];?></textarea>
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
	</body>
	</html>																								<?php
	mysqli_close($con);
}
else
	header("Location:../index.php");
