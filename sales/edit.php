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
		<link href='../select2/dist/css/select2.min.css' rel='stylesheet' type='text/css'>
		<script src="https://kit.fontawesome.com/742221945b.js" crossorigin="anonymous"></script>
		<script type="text/javascript" language="javascript" src="../js/jquery.js"></script>
		<script type="text/javascript" language="javascript" src="../js/jquery-ui.min.js"></script>	
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
		<script src='../select2/dist/js/select2.min.js' type='text/javascript'></script>
		<script src='editScripts.js' type='text/javascript'></script>
		<script>
			var shopNameList = '<?php echo $shopNameArray;?>';
			var shopName_array = JSON.parse(shopNameList);
			var shopNameArray = shopName_array;	
		</script>
		<style>
			.close{
			  font-size: 40px;
			  color: red;
			}		
			label{
				text-align: left;
			}
			.select2-selection {
			  text-align: left;
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
								<i class="far fa-edit"></i>&nbsp;&nbsp;Sheet
							</button><?php
						}
						else
						{?>
							<button type="button" class="btn" style="background-color:#7dc37d;color:white;" data-toggle="modal" data-target="#sheetModal">
								<i class="fas fa-plus"></i>&nbsp;&nbsp;Sheet
							</button><?php
						}?>
						<button type="button" class="btn" style="background-color:#2A739E;color:white;" data-toggle="modal" data-target="#historyModal">
							<i class="fa fa-history"></i>&nbsp;&nbsp;History
						</button>				
					</div>					
				</div>
				<br>
				<div align="center" style="padding-bottom:5px;">				
					<div class="card" style="width:65%;">
						<div class="card-header" style="background-color:#f2cf5b;font-size:20px;font-weight:bold;color:white"><i class="fa fa-pencil"></i> Edit Sale <?php echo $row['sales_id']; ?></div>
						<div class="card-body">
						 <div class="form-group row">
							<label class="col-md-2 control-label">Date</label>
							<div class="col-md-3 inputGroupContainer">
							   <div class="input-group">
									<input type="text" id="entryDate" name="entryDate" class="form-control date" 
										value="<?php 
												$originalDate1 = $row['entry_date'];
												$newDate1 = date("d-m-Y", strtotime($originalDate1));
												echo $newDate1; ?>">
								</div>
							</div>
							<span class="col-md-1"></span>
							<label class="col-md-2 control-label">Bill No</label>
							<div class="col-md-3 inputGroupContainer">
							   <div class="input-group">
									<input type="text" name="bill" class="form-control" value="<?php echo $row['bill_no']; ?>">
								</div>
							</div>														
						 </div>
						 <div class="form-group row">
							<label class="col-md-2 control-label">AR</label>
							<div class="col-md-3 inputGroupContainer">
							   <div class="input-group">
									<select name="ar" id="ar" required class="form-control" onChange="arRefresh(shopNameArray);">
										<option value = "<?php echo $row['ar_id'];?>"><?php echo $arMap[$row['ar_id']];?></option><?php
										foreach($arMap as $arId => $arName)
										{																							?>
											<option value="<?php echo $arId;?>"><?php echo $arName;?></option>						<?php	
										}																							?>
									</select>
								</div>
							</div>
							<span class="col-md-1"></span>
							<label class="col-md-2 control-label">Truck No</label>
							<div class="col-md-3 inputGroupContainer">
							   <div class="input-group">
									<input type="text" name="truck" class="form-control" value="<?php echo $row['truck_no']; ?>">
								</div>
							</div>														
						 </div>						
						 <div class="form-group row">
							<label class="col-md-2 control-label">Engineer</label>
							<div class="col-md-3 inputGroupContainer">
							   <div class="input-group">
									<select name="engineer" id="engineer" class="form-control">
										<option value="<?php echo $row['eng_id'];?>"><?php echo $engMap[$row['eng_id']];?></option>																																<?php
										foreach($engMap as $engId => $engName)
										{	
											if($engId != $row['eng_id'])
											{																																			?>
												<option value="<?php echo $engId;?>"><?php echo $engName;?></option><?php
											}																																			?>																																						<?php		
										}																																				?>
									</select>
								</div>
							</div>
							<span class="col-md-1"></span>
							<label class="col-md-2 control-label">Customer Name</label>
							<div class="col-md-3 inputGroupContainer">
							   <div class="input-group">
									<input type="text" name="customerName" class="form-control" value="<?php echo $row['customer_name']; ?>">
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
											<option <?php if($row['product'] == $product['id']) echo 'selected';?> value="<?php echo $product['id'];?>"><?php echo $product['name'];?></option>		<?php	
										}																							?>
									</select>
								</div>
							</div>
							<span class="col-md-2"></span>
							<label class="col-md-2 control-label">Address 1</label>
							<div class="col-md-3 inputGroupContainer">
							   <div class="input-group">
									<input type="text" name="address1" class="form-control" value="<?php echo $row['address1']; ?>">
								</div>
							</div>														
						 </div>												 						 
						 <div class="form-group row">
							<label class="col-md-2 control-label">Qty</label>
							<div class="col-md-2 inputGroupContainer">
							   <div class="input-group">
									<input type="text" name="qty" required class="form-control" pattern="[0-9]+" value="<?php echo $row['qty'];?>" title="Input a valid number">
								</div>
							</div>														
							<span class="col-md-2"></span>
							<label class="col-md-2 control-label">Address 2</label>
							<div class="col-md-3 inputGroupContainer">
							   <div class="input-group">
									<input type="text" name="address2" class="form-control" value="<?php echo $row['address2']; ?>">
								</div>
							</div>														
						 </div>												 						 						 
						 <div class="form-group row">
							<label class="col-md-2 control-label">Remarks</label>
							<div class="col-md-3 inputGroupContainer">
							   <div class="input-group">
									<input type="text" name="remarks" class="form-control" value="<?php echo $row['remarks']; ?>">
								</div>
							</div>														
							<span class="col-md-1"></span>
							<label class="col-md-2 control-label">Customer Phone</label>
							<div class="col-md-3 inputGroupContainer">
							   <div class="input-group">
									<input type="text" name="customerPhone" class="form-control" value="<?php echo $row['customer_phone']; ?>">
								</div>
							</div>														
						 </div>												 						 						 						 
						 <div class="form-group row">
							<label class="col-md-2 control-label">Return</label>
							<div class="col-md-2 inputGroupContainer">
							   <div class="input-group">
									<input type="text" name="return" class="form-control" value="<?php echo $row['return_bag']; ?>">
								</div>
							</div>														
							<span class="col-md-2"></span>
							<label class="col-md-2 control-label">Shop</label>
							<div class="col-md-4 inputGroupContainer">
							   <div class="input-group">
									<input type="text" readonly name="shopName" id="shopName" class="form-control">
								</div>
							</div>														
						 </div>												 						 						 						
						 <div class="form-group row">
							<label class="col-md-2 control-label">Bill Discount</label>
							<div class="col-md-2 inputGroupContainer">
							   <div class="input-group">
									<input type="text" name="bd" id="bd" class="form-control" pattern="[0-9]+" title="Input a valid number" value="<?php echo $row['discount'];?>">
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
						 <button type="submit" class="btn" style="width:100px;font-size:18px;background-color:#f2cf5b;color:white;"><i class="fa fa-save"></i> Save</button>
						</div>
						<div class="card-footer" style="background-color:#f2cf5b;padding:1px;"></div>
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
				<a href="delete.php?sales_id=<?php echo $row['sales_id'];?>" style="float:right;margin-right:150px;background-color:#E6717C;color:#FFFFFF" class="btn" onclick="return confirm('Are you sure you want to permanently delete this entry ?')"><i class="far fa-trash-alt"></i>&nbsp;&nbsp;Delete</a>						
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
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			  </div>
			  <div class="modal-body">
				<i class="fa fa-user"></i>&nbsp;&nbsp;Created By : <?php echo $row['entered_by'];?><br/>
				<i class="fa fa-calendar"></i>&nbsp;&nbsp;Created On : <?php echo date('d-m-Y, h:i A', strtotime($row['entered_on']));?>
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
						if(isset($historyList))
						{
							foreach($historyList as $history)
							{																																					?>
								<tr>
									<td><?php echo $history['field'];?></td>
									<td><?php echo $history['edited_by'];?></td>
									<td><?php echo $history['old_value'];?></td>
									<td><?php echo $history['new_value'];?></td>
									<td><?php echo date('d-m-Y, h:i A', strtotime($history['edited_on']));?></td>
								</tr>																																			<?php	
							}																																													
						}																																					?>
	
					</table>	
				</section>				
			  </div>
			  <div class="modal-footer">
			  </div>
			</div>
		  </div>
		</div>

		<!-- The Modal for new Sheet -->
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
