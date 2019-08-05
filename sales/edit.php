<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
if(isset($_SESSION["user_name"]))
{
	require '../connect.php';
	echo "LOGGED USER : ".$_SESSION["user_name"] ;	
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
	?>

	<html>
	<head>
		<title>Edit Sale <?php echo $row['sales_id']; ?></title>
		<link rel="stylesheet" type="text/css" href="../css/jquery-ui.css">
		<link rel="stylesheet" type="text/css" href="../css/newEdit.css" />
		<link rel="stylesheet" href="../css/button.css">
		<link href='../select2/dist/css/select2.min.css' rel='stylesheet' type='text/css'>
		<script type="text/javascript" language="javascript" src="../js/jquery.js"></script>
			<script type="text/javascript" language="javascript" src="../js/jquery-ui.min.js"></script>	
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
	</head>
	<body>
		<form name="frmUser" method="post" action="update.php">
			<input hidden name="id" value="<?php echo $row['sales_id'];?>">
			<div style="width:100%;">
				<div align="center" style="padding-bottom:5px;">
					<a href="../index.php" class="link"><img alt='Home' title='Home' src='../images/home.png' width='50px' height='50px'/></a>&nbsp;&nbsp;
					<a href="todayList.php?ar=all" class="link"><img alt='List' title='List' src='../images/list_icon.jpg' width='50px' height='50px'/></a>
					<a href="modified_by.php?sales_id=<?php echo $row["sales_id"]; ?>"  class="link" >
						<img align="right" alt= 'Modified By' title='Modified By' src='../images/user.png' width='40px' height='50px'hspace='10'  />
					</a>
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
							<td><input readonly id="final" class="txtField"></td>			
							
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
				<a href="delete.php?sales_id=<?php echo $row['sales_id'];?>" style="float:right;width:50px;margin-right:150px;" class="btn btn-red" onclick="return confirm('Are you sure you want to permanently delete this entry ?')">DELETE</a>						
			</div>
			<br/><br/><br/><br/>		
		</form>
		<br/><br/><br/><br/>		
	</body>
	</html>																								<?php
}
else
	header("Location:../index.php");
