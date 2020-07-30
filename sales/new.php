<?php
session_start();
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
	
	$engineerObjects = mysqli_query($con,"SELECT id,name,sap_code,shop_name FROM ar_details WHERE type LIKE '%Engineer%' OR type = 'Contractor' ORDER BY name ASC");	
?>

<html>
<head>
	<title>NEW SALE</title>
	<meta charset="utf-8">	
	<link rel="stylesheet" type="text/css" href="../css/jquery-ui.css">
	<link href='../select2/dist/css/select2.min.css' rel='stylesheet' type='text/css'>
	<script src="https://kit.fontawesome.com/742221945b.js" crossorigin="anonymous"></script>	
	<script type="text/javascript" language="javascript" src="../js/jquery.js"></script>
	<script type="text/javascript" language="javascript" src="../js/jquery-ui.min.js"></script>	
	<script src='../select2/dist/js/select2.min.js' type='text/javascript'></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>	
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
		$("#engineer").select2();
		$("#ar").select2();

		var pickerOpts = { dateFormat:"dd-mm-yy"}; 
		$( "#datepicker" ).datepicker(pickerOpts);


		
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
			url: "getWD.php",
			data:'product='+product+'&date='+date,
			success: function(data){
				$("#wd").val(data);
				refreshRate();
			}
		});				
		
		$("#datepicker").change(function()
		{
			date = $(this).val();
			product = $("#product").val();
			client = $("#ar").val();
			
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
</head>
<style>
label{
	text-align: left;
}
.select2-selection {
  text-align: left;
}
</style>
<body>
	<form name="frmUser" method="post" action="insert.php" onsubmit="return validateForm()">
		<div align="center" style="padding-bottom:5px;">
			<a href="../index.php" class="link"><img alt='home' title='home' src='../images/homeBrown.png' width='50px' height='50px'/> </a> &nbsp;&nbsp;&nbsp;
			<a href="todayList.php?ar=all" class="link">
			<img alt='List' title='List Sales' src='../images/list_icon.jpg' width='50px' height='50px'/></a>
		</div>
		<br>
		<div align="center" style="padding-bottom:5px;">				
			<div class="card" style="width:65%;">
				<div class="card-header" style="background-color:#7dc37d;font-size:20px;font-weight:bold;color:white"><i class="fa fa-pencil"></i> New Sale</div>
				<div class="card-body">		
					<div class="form-group row">
						<label class="col-md-2 control-label">Date</label>
						<div class="col-md-3 inputGroupContainer">
							<div class="input-group">
								<input type="text" id="datepicker" class="form-control" name="date" required value="<?php echo date('d-m-Y'); ?>" />						</div>
						</div>
						<span class="col-md-1"></span>
						<label class="col-md-2 control-label">Bill No</label>
						<div class="col-md-3 inputGroupContainer">
							<div class="input-group">
								<input type="text" name="bill" class="form-control">
							</div>
						</div>														
					</div>	
					 <div class="form-group row">
						<label class="col-md-2 control-label">AR</label>
						<div class="col-md-3 inputGroupContainer">
						   <div class="input-group">
								<select name="ar" id="ar" required class="form-control" onChange="arRefresh();">
									<option value = "">---Select---</option>
																																<?php
									foreach($arObjects as $ar) 
									{																							?>
										<option value="<?php echo $ar['id'];?>"><?php echo $ar['name'];?></option>			<?php	
									}																							?>
								</select>
							</div>
						</div>
						<span class="col-md-1"></span>
						<label class="col-md-2 control-label">Truck No</label>
						<div class="col-md-3 inputGroupContainer">
						   <div class="input-group">
								<input type="text" name="truck" class="form-control">
							</div>
						</div>														
					 </div>										
					 <div class="form-group row">
						<label class="col-md-2 control-label">Engineer</label>
						<div class="col-md-3 inputGroupContainer">
						   <div class="input-group">
								<select name="engineer" id="engineer"  class="form-control">
									<option value = "">---Select---</option>
																																<?php
									foreach($engineerObjects as $eng) 
									{																							?>
										<option value="<?php echo $eng['id'];?>"><?php echo $eng['name'];?></option>			<?php	
									}																							?>
								</select>
							</div>
						</div>
						<span class="col-md-1"></span>
						<label class="col-md-2 control-label">Order No</label>
						<div class="col-md-3 inputGroupContainer">
						   <div class="input-group">
								<input type="text" name="order_no" class="form-control">
							</div>
						</div>														
					 </div>												 
					 <div class="form-group row">
						<label class="col-md-2 control-label">Product</label>
						<div class="col-md-2 inputGroupContainer">
						   <div class="input-group">
								<select name="product" id="product" required class="form-control">									<?php
									foreach($products as $product) 
									{																							?>
										<option value="<?php echo $product['id'];?>"><?php echo $product['name'];?></option>		<?php	
									}																							?>
								</select>
							</div>
						</div>						
						<span class="col-md-2"></span>
						<label class="col-md-2 control-label">Customer Name</label>
						<div class="col-md-3 inputGroupContainer">
						   <div class="input-group">
								<input type="text" name="customerName" class="form-control">
							</div>
						</div>																				
					 </div>												 						 
					 <div class="form-group row">
						<label class="col-md-2 control-label">Qty</label>
						<div class="col-md-2 inputGroupContainer">
						   <div class="input-group">
								<input type="text" name="qty" required class="form-control" pattern="[0-9]+" title="Input a valid number">
							</div>
						</div>														
						<span class="col-md-2"></span>
						<label class="col-md-2 control-label">Address 1</label>
						<div class="col-md-3 inputGroupContainer">
						   <div class="input-group">
								<input type="text" name="address1" class="form-control">
							</div>
						</div>																				
					 </div>												 						 						 
					 <div class="form-group row">
						<label class="col-md-2 control-label">Remarks</label>
						<div class="col-md-3 inputGroupContainer">
						   <div class="input-group">
								<input type="text" name="remarks" class="form-control">
							</div>
						</div>														
						<span class="col-md-1"></span>
						<label class="col-md-2 control-label">Address 2</label>
						<div class="col-md-3 inputGroupContainer">
						   <div class="input-group">
								<input type="text" name="address2" class="form-control">
							</div>
						</div>																				
					 </div>												 						 						 						 
					 <div class="form-group row">
						<label class="col-md-2 control-label">Return</label>
						<div class="col-md-2 inputGroupContainer">
						   <div class="input-group">
								<input type="text" name="return" class="form-control" pattern="[0-9]+" title="Input a valid number">
							</div>
						</div>														
						<span class="col-md-2"></span>
						<label class="col-md-2 control-label">Customer Phone</label>
						<div class="col-md-3 inputGroupContainer">
						   <div class="input-group">
								<input type="text" name="customerPhone" class="form-control">
							</div>
						</div>																					
					 </div>												 						 						 						
					 <div class="form-group row">
						<label class="col-md-2 control-label">Bill Discount</label>
						<div class="col-md-2 inputGroupContainer">
						   <div class="input-group">
								<input type="text" name="bd" id="bd" class="form-control" pattern="[0-9]+" title="Input a valid number">
							</div>
						</div>														
						<span class="col-md-2"></span>
						<label class="col-md-2 control-label">Shop</label>
						<div class="col-md-3 inputGroupContainer">
						   <div class="input-group">
								<input type="text" readonly name="shopName" id="shopName" class="form-control">
							</div>
						</div>																											
					 </div>												 						 						 						 						 
					 <div class="form-group row">
						<label class="col-md-2 control-label">Final Rate</label>
						<div class="col-md-2 inputGroupContainer">
						   <div class="input-group">
								<input readonly id="final" class="form-control">
							</div>
						</div>														
					 </div>
					<button type="submit" class="btn" style="width:100px;font-size:18px;background-color:#7dc37d;color:white;"><i class="fa fa-save"></i> Save</button>				 
				</div>
				<div class="card-footer" style="background-color:#7dc37d;padding:1px;"></div>
			</div>	
		</div>
		<div align ="center">
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
		<br/><br/><br/><br/>		
	</form>
	<br/><br/><br/><br/>		
</body>
</html>																																						<?php
}
else
	header("Location:../index.php");
