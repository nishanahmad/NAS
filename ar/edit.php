<?php
session_start();
if(isset($_SESSION["user_name"]))
{
	require '../connect.php';
	require '../navbar.php';
    
	$id = $_GET['id'];
	$sql = mysqli_query($con,"SELECT * FROM ar_details WHERE id='$id'") or die(mysqli_error($con));
	$ar = mysqli_fetch_array($sql,MYSQLI_ASSOC);	
	$arId = $ar['id'];
	
	$arObjects = mysqli_query($con,"SELECT * FROM ar_details WHERE type != 'Engineer' ORDER BY name ASC") or die(mysqli_error($con));
?>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link href="../css/styles.css" rel="stylesheet" type="text/css">	
		<link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" rel="stylesheet" type="text/css">
		<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/jquery.tablesorter.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/jquery.tablesorter.widgets.min.js"></script>
		<title>Edit AR</title>
	</head>
	<body>
		<nav class="navbar navbar-light bg-light sticky-top bottom-nav">
			<span class="navbar-brand" style="font-size:25px;margin-left:42%"><i class="fa fa-address-card-o"></i> Update AR Details</span>
		</nav>
		<br/><br/>
		<div id="snackbar"><i class="fa fa-check"></i>&nbsp;&nbsp;Updated successfull !!!</div>
		<form name="editForm" id="editForm" method="post" action="update.php">
			<input hidden name="id" id="id" value="<?php echo $arId;?>">
			<div style="width:100%;">
				<div align="center" style="padding-bottom:5px;">
					<div class="card" style="width:40%;">
						<div class="card-body">
							<p id="insertError" style="color:red;"></p>							
							<div class="col-6">
								<div class="input-group mb-3">
									<select name="ar" id="ar" required class="form-control" style="width:74%" onchange="return rerender();">												<?php
										foreach($arObjects as $arObj) 
										{																																	?>
											<option <?php if($arObj['id'] == $arId) echo 'selected';?> value="<?php echo $arObj['id'];?>"><?php echo $arObj['name'];?></option>														<?php	
										}																																	?>
									</select>
								</div>
							</div>
							<br/>
							<div class="col-8">
								<div class="input-group mb-3">
									<span class="input-group-text col-md-4"><i class="fa fa-address-card-o"></i>&nbsp;Shop Name</span>
									<input type="text" name="shop_name" class="form-control" value="<?php if(isset($ar['shop_name'])) echo $ar['shop_name'];?>">
								</div>
							</div>
							<div class="col-8">
								<div class="input-group mb-3">
									<span class="input-group-text col-md-4"><i class="fa fa-whatsapp"></i>&nbsp;Whatsapp</span>
									<input type="text" name="whatsapp" class="form-control" value="<?php if(isset($ar['whatsapp'])) echo $ar['whatsapp'];?>">
								</div>
							</div>
							<div class="col-8">
								<div class="input-group mb-3">
									<span class="input-group-text col-md-4">Child Code</span>
									<input type="text" name="child_code" class="form-control" value="<?php if(isset($ar['child_code'])) echo $ar['child_code'];?>">
								</div>
							</div>							
							<div class="col-8">
								<div class="input-group mb-3">
									<span class="input-group-text col-md-4">Parent Code</span>
									<input type="text" name="parent_code" class="form-control" value="<?php if(isset($ar['parent_code'])) echo $ar['parent_code'];?>">
								</div>
							</div>
							<div class="col-8">
								<div class="input-group mb-3">
									<span class="input-group-text col-md-4">Ultra Code</span>
									<input type="text" name="ultra_code" class="form-control" value="<?php if(isset($ar['ultra_code'])) echo $ar['ultra_code'];?>">
								</div>
							</div>							
							<div class="col-8">
								<div class="input-group mb-3">
								<span class="input-group-text col-md-4">Type</span>
									<select name="type" id="type" class="form-control" style="width:60%">
										<option <?php if($ar['type'] == 'AR') echo 'selected';?> value = "AR">AR</option>
										<option <?php if($ar['type'] == 'SR') echo 'selected';?> value = "SR">SR</option>
									</select>																
								</div>
							</div>							
							<br/>	
							<button type="submit" class="btn" style="background-color:#4BA6AD;color:#FFFFFF"><i class="fas fa-save"></i> Update</button>
						</div>
					</div>
				</div>
			</div>	
			<br/><br/><br/><br/>		
		</form>	
		<script>
		$(function(){
			if(window.location.href.includes('success')){
				var x = document.getElementById("snackbar");
				x.className = "show";
				setTimeout(function(){ x.className = x.className.replace("show", ""); }, 2000);	
			}
		});
		function rerender()
		{
			var ar = document.getElementById("ar").value;
			var hrf = window.location.href;
			hrf = hrf.slice(0,hrf.indexOf("?"));
			window.location.href = hrf +"?id="+ ar;
		}					
		</script>		
	</body>
</html>	
<?php
}
else
	header("Location:../index.php");
?>