<?php
session_start();
if(isset($_SESSION["user_name"]))
{
	require '../connect.php';
    
// Populate maps for SAP CODE and SHOP NAME
	$brands = mysqli_query($con,"SELECT id,name FROM brand WHERE status = 1 ORDER BY id ASC");

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
	<link rel="stylesheet" type="text/css" href="../css/newEdit.css" />
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
	
	$(function() {

		$("#engineer").select2();
		$("#ar").select2();

		var pickerOpts = { dateFormat:"dd-mm-yy"}; 
					
		$( "#datepicker" ).datepicker(pickerOpts);
	});
	
	</script>
</head>
<body>
	<form name="frmUser" method="post" action="insert.php" onsubmit="return validateForm()">
		<div align="center" style="padding-bottom:5px;">
			<a href="../index.php" class="link"><img alt='home' title='home' src='../images/homeBrown.png' width='50px' height='50px'/> </a> &nbsp;&nbsp;&nbsp;
			<a href="todayList.php?ar=all" class="link">
			<img alt='List' title='List Sales' src='../images/list_icon.jpg' width='50px' height='50px'/></a>
		</div>
		<br>
		<table border="0" cellpadding="15" cellspacing="0" width="80%" align="center" style="float:center" class="tblSaveForm">
			<tr class="tableheader">
				<td colspan="4"><div align ="center"><b><font size="4">NEW SALE</font><b></td>
			</tr>

			<tr>
				<td><label>Date</label></td>
				<td><input type="text" id="datepicker" class="txtField" name="date" required value="<?php echo date('d-m-Y'); ?>" /></td>

				<td><label>Bill No</label></td>
				<td><input type="text" name="bill" class="txtField"></td>
			</tr>

			<tr>
				<td><label>AR</label></td>
				<td><select name="ar" id="ar" required class="txtField" onChange="arRefresh();">
						<option value = "">---Select---</option>
																													<?php
						foreach($arObjects as $ar) 
						{																							?>
							<option value="<?php echo $ar['id'];?>"><?php echo $ar['name'];?></option>			<?php	
						}																							?>
					</select>
				</td>

				<td><label>Truck no</label></td>
				<td><input type="text" name="truck" class="txtField"></td>

			</tr>

			<tr>
				<td><label>Engineer</label></td>
				<td><select name="engineer" id="engineer"  class="txtField">
						<option value = "">---Select---</option>
																													<?php
						foreach($engineerObjects as $eng) 
						{																							?>
							<option value="<?php echo $eng['id'];?>"><?php echo $eng['name'];?></option>			<?php	
						}																							?>
					</select>
				</td>			

				<td><label>Customer Name</label></td>
				<td><input type="text" name="customerName" class="txtField"></td>
			</tr>

			<tr>
				<td><label>Brand</label></td>
				<td><select name="brand" id="brand" required class="txtField">									<?php
						foreach($brands as $brand) 
						{																							?>
							<option value="<?php echo $brand['id'];?>"><?php echo $brand['name'];?></option>		<?php	
						}																							?>
					</select>
				</td>

				<td><label>Address Part 1</label></td>
				<td><input type="text" name="address1" class="txtField"></td>
			</tr>

			<tr>
				<td><label>Qty</label></td>
				<td><input type="text" name="qty" required class="txtField" pattern="[0-9]+" title="Input a valid number"></td>

				<td><label>Address Part 2</label></td>
				<td><input type="text" name="address2" class="txtField"></td>
			</tr>

			<tr>
				<td><label>Remarks</label></td>
				<td><input type="text" name="remarks" class="txtField"></td>

				<td><label>Customer Phone</label></td>
				<td><input type="text" name="customerPhone" class="txtField"></td>
			</tr>
			
			<tr>
				<td><label>Return</label></td>
				<td><input type="text" name="return" class="txtField" pattern="[0-9]+" title="Input a valid number"></td>			
				
				<td><label>Shop</label></td>
				<td><input type="text" readonly name="shopName" id="shopName" class="txtField"></td>	
			</tr>
			</tr>
			<tr>
			<td colspan="4"><div align="center"><input type="submit" name="submit" value="Submit" class="btnSubmit"></div></td>
			</tr>
		</table>
		</div>
	</form>

	<br>
</body>
</html>																		<?php
}
else
	header("Location:../index.php");
