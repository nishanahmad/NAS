<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
if(isset($_SESSION["user_name"]))
{
	require '../connect.php';
	require 'getHistory.php';
	require 'sheetModal.php';
	require 'rateBreakDownModal.php';
	require '../navbar.php';
	
	$urlsql = $_GET['sql'];
	$urlrange = $_GET['range'];
	
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

	if($_POST)
	{
		$URL='list.php?sql='.$_POST['sql1'].'&range='.$_POST['range1'];
		echo "<script type='text/javascript'>document.location.href='{$URL}';</script>";
		echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $URL . '">';		
	}																																								?>

	<html>
	<head>
		<title>Edit Sale <?php echo $row['sales_id']; ?></title>
		<link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" rel="stylesheet" type="text/css">
		<link href="../css/styles.css" rel="stylesheet" type="text/css">
		<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/jquery.tablesorter.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/jquery.tablesorter.widgets.min.js"></script>
		<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet"/>
		<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>		
		<script src='edit.js' type='text/javascript'></script>
		<script>
			var shopNameList = '<?php echo $shopNameArray;?>';
			var shopName_array = JSON.parse(shopNameList);
			var shopNameArray = shopName_array;	
		</script>
	</head>
	<body>
		<nav class="navbar navbar-light bg-light sticky-top bottom-nav">
			<div style="float:left;margin-left:20px;">
				<form method="post" action="">
					<input hidden name="sql1" value="<?php echo $urlsql;?>">
					<input hidden name="range1" value="<?php echo $urlrange;?>">				
					<button type="submit" class="btn" style="background-color:#54698D;color:white;">
						<i class="fa fa-arrow-left"></i>&nbsp;&nbsp;Go Back
					</button>				
				</form>
			</div>
			<span class="navbar-brand" style="font-size:25px;margin-left:10%;"><i class="fa fa-bolt"></i> Sale</span>
				<div style="float:right;margin-right:20px;"><?php
					if(isset($sheet))
					{																																						?>
						<button type="button" class="btn" id="sheetMdlBtn" style="background-color:#F2CF5B;color:white;" data-toggle="modal" data-target="#sheetModal">
							<i class="far fa-edit"></i>&nbsp;&nbsp;Sheet
						</button>&nbsp;&nbsp;																																			<?php
					}
					else
					{																																						?>
						<button type="button" class="btn" id="sheetMdlBtn" style="background-color:#7dc37d;color:white;" data-toggle="modal" data-target="#sheetModal">
							<i class="fas fa-plus"></i>&nbsp;&nbsp;Sheet
						</button>																																			<?php
					}																																						?>
					<button type="button" class="btn" style="background-color:#2A739E;color:white;" data-toggle="modal" data-target="#historyModal">
						<i class="fa fa-history"></i>&nbsp;&nbsp;History
					</button>
				</div>
		</nav>
		<br/><br/>
		<form name="frmUser" method="post" action="update.php">
			<input hidden name="id" value="<?php echo $row['sales_id'];?>">
			<input hidden name="sql" value="<?php echo $urlsql;?>">
			<input hidden name="range" value="<?php echo $urlrange;?>">
			<div style="width:100%;">
				<div align="center" style="padding-bottom:5px;">				
					<div class="card" style="width:65%;">
						<div class="card-header" style="background-color:#f2cf5b;font-size:20px;font-weight:bold;color:white">Sale <?php echo $row['sales_id']; ?></div>
						<div class="card-body">
							<br/>
							<div class="row">
								<div class="col col-md-4 offset-1">
									<div class="input-group">
										<span class="input-group-text col-md-5"><i class="far fa-calendar-alt"></i>&nbsp;Date</span>
										<input type="text" required id="date" name="entryDate" class="form-control" autocomplete="off" value="<?php
												$originalDate1 = $row['entry_date'];
												$newDate1 = date("d-m-Y", strtotime($originalDate1));
												echo $newDate1; ?>">
									</div>
								</div>
								<div class="col col-md-4 offset-2">
									<div class="input-group mb-3">
										<span class="input-group-text col-md-4"><i class="far fa-file-alt"></i>&nbsp;Bill No</span>
										<input type="text" name="bill" class="form-control" value="<?php echo $row['bill_no']; ?>">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col col-md-5 offset-1">
									<div class="input-group">
										<span class="input-group-text col-md-4"><i class="fa fa-address-card-o"></i>&nbsp;AR</span>
										<select name="ar" id="ar" required class="form-control" style="width:250px;" onChange="arRefresh(shopNameArray);">
											<option value = "<?php echo $row['ar_id'];?>"><?php echo $arMap[$row['ar_id']];?></option><?php
											foreach($arMap as $arId => $arName)
											{																							?>
												<option value="<?php echo $arId;?>"><?php echo $arName;?></option>						<?php	
											}																							?>
										</select>
									</div>
								</div>
								<div class="col col-md-4 offset-1">
									<div class="input-group mb-3">
										<span class="input-group-text col-md-4"><i class="fas fa-truck-moving"></i>&nbsp;Truck</span>
										<input type="text" name="truck" class="form-control" value="<?php echo $row['truck_no']; ?>">
									</div>
								</div>
							</div>							
							<div class="row">
								<div class="col col-md-5 offset-1">
									<div class="input-group">
										<span class="input-group-text col-md-4"><i class="fa fa-suitcase"></i>&nbsp;Engineer</span>
										<select name="engineer" id="engineer" class="form-control" style="width:250px;">
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
								<div class="col col-md-4 offset-1">
									<div class="input-group mb-3">
										<span class="input-group-text col-md-4" style="width:120px;"><i class="fa fa-money"></i>&nbsp;Order No</span>
										<input type="text" name="order_no" class="form-control" value="<?php echo $row['order_no']; ?>">
									</div>
								</div>
							</div>														
							<div class="row">
								<div class="col col-md-4 offset-1">
									<div class="input-group">
										<span class="input-group-text col-md-5"><i class="fa fa-shield"></i>&nbsp;Product</span>
										   <div class="input-group" style="width:150px;">
												<select name="product" id="product" required class="form-control">																								<?php
													foreach($products as $product) 
													{																																							?>
														<option <?php if($row['product'] == $product['id']) echo 'selected';?> value="<?php echo $product['id'];?>"><?php echo $product['name'];?></option>		<?php	
													}																							?>
												</select>
											</div>
									</div>
								</div>
								<div class="col col-md-4 offset-2">
									<div class="input-group mb-3">
										<span class="input-group-text col-md-4" style="width:120px;"><i class="far fa-user"></i>&nbsp;Customer</span>
										<input type="text" name="customerName" class="form-control" value="<?php echo $row['customer_name']; ?>">
									</div>
								</div>
							</div>																					
							<div class="row">
								<div class="col col-md-4 offset-1">
									<div class="input-group">
										<span class="input-group-text col-md-5"><i class="fab fa-buffer"></i>&nbsp;Quantity</span>
										<input type="text" name="qty" required class="form-control" pattern="[0-9]+" value="<?php echo $row['qty'];?>" title="Input a valid number" autocomplete="off">
									</div>
								</div>
								<div class="col col-md-4 offset-2">
									<div class="input-group mb-3">
										<span class="input-group-text col-md-4"><i class="fas fa-mobile-alt"></i>&nbsp;Phone</span>
										<input type="text" name="customerPhone" class="form-control" value="<?php echo $row['customer_phone']; ?>">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col col-md-4 offset-1">
									<div class="input-group mb-3">
										<span class="input-group-text col-md-5"><i class="fa fa-tags"></i>&nbsp;Bill Disc.</span>
										<input type="text" name="bd" id="bd" class="form-control" value="<?php echo $row['discount'];?>">
									</div>
								</div>

							</div>							
							<div class="row">
								<div class="col col-md-5 offset-1">
									<div class="input-group">
										<span class="input-group-text col-md-3" style="width:125px;"><i class="far fa-comment-dots"></i>&nbsp;Remarks</span>
										<textarea name="remarks" id="remarks-edit" class="form-control"><?php echo $row['remarks']; ?></textarea>
									</div>
								</div>
								<div class="col col-md-5 offset-1">
									<div class="input-group mb-3">
										<span class="input-group-text col-md-3" style="width:100px;"><i class="fas fa-map-marker-alt"></i>&nbsp;Address</span>
										<textarea name="address1" class="form-control"><?php echo $row['address1']; ?></textarea>
									</div>
								</div>
							</div>														
							<div class="row">
								<div class="col col-md-4 offset-1">
									<div class="input-group">
										<span class="input-group-text col-md-5"><i class="fa fa-rupee-sign"></i>&nbsp;Final Rate</span>
										<input readonly id="final" class="form-control" style="cursor:pointer" data-toggle="modal" data-target="#rateBreakDownModal">
									</div>
								</div>
								<div class="col col-md-4 offset-2">
									<div class="input-group mb-3">
										<span class="input-group-text col-md-4"><i class="fa fa-address-card-o"></i>&nbsp;Shop</span>
										<input type="text" readonly name="shopName" id="shopName" class="form-control">
									</div>
								</div>
							</div>
							<br/>
							<button type="submit" class="btn" style="width:100px;font-size:18px;background-color:#f2cf5b;color:white;"><i class="fa fa-save"></i> Save</button>
						</div>
						<div class="card-footer" style="background-color:#f2cf5b;padding:1px;"></div>
					</div>
					<br/><br/>
					<a href="delete.php?<?php echo 'sales_id='.$row['sales_id'].'&sql='.$urlsql.'&range='.$urlrange;?>" style="float:right;margin-right:150px;background-color:#E6717C;color:#FFFFFF" class="btn" onclick="return confirm('Are you sure you want to permanently delete this entry ?')"><i class="far fa-trash-alt"></i>&nbsp;&nbsp;Delete</a>
				</div>
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
	</body>
	</html>																																								<?php
	mysqli_close($con);
}
else
	header("Location:../index/home.php");
