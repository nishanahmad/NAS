<?php
session_start();	

if(isset($_SESSION["user_name"]))
{
	require '../connect.php';	
	require 'navbar.php';		

	if(isset($_GET['error']))
		$error = $_GET['error'];
	else
		$error = 'false';	
	
	$users = mysqli_query($con,"SELECT * FROM users WHERE role ='driver' ORDER BY user_name ASC" ) or die(mysqli_error($con));
	foreach($users as $user)
	{
		if($user['user_name'] == 'Damage')
		{
			if($_SESSION["user_name"] == 'Aparna' || $_SESSION["role"]== 'admin')
				$userMap[$user['user_id']] = $user['user_name'];
		}
		else
			$userMap[$user['user_id']] = $user['user_name'];
	}
	
	if($_POST)
	{
		$from = (int)$_POST['from'];
		$to = (int)$_POST['to'];
		$qty = (int)$_POST['qty'];
		$half_qty = (int)$_POST['half_qty'];
		$remarks = $_POST['remarks'];
		$transferred_on = date('Y-m-d H:i:s');
		$transferred_by = $_SESSION['user_id'];
		
		$commitFlag = true;
		mysqli_autocommit($con, FALSE);		

		$QuerySheetInHand = mysqli_query($con,"SELECT qty FROM sheets_in_hand WHERE user=$from ");
		$newQty = mysqli_fetch_array($QuerySheetInHand,MYSQLI_ASSOC)['qty'] - $qty;
		if($newQty < 0)
			$commitFlag = false;	
	
		$updateFrom = mysqli_query($con,"UPDATE sheets_in_hand SET qty = qty - '$qty' WHERE user=$from ");
		if(!$updateFrom)
			$commitFlag = false;		

		$queryFrom = mysqli_query($con,"SELECT qty FROM sheets_in_hand WHERE user=$from ");
		if(!$queryFrom)
			$commitFlag = false;				
		
		$fromStock = mysqli_fetch_array($queryFrom,MYSQLI_ASSOC)['qty'];
		
		$updateTo = mysqli_query($con,"UPDATE sheets_in_hand SET qty = qty + '$qty' WHERE user=$to ");
		if(!$updateTo)
			$commitFlag = false;				
		
		$queryTo = mysqli_query($con,"SELECT qty FROM sheets_in_hand WHERE user=$to ");
		if(!$queryTo)
			$commitFlag = false;		
		
		$toStock = mysqli_fetch_array($queryTo,MYSQLI_ASSOC)['qty'];

		$insertLog = mysqli_query($con, "INSERT INTO transfer_logs (user_from, user_to, qty, transferred_on, transferred_by, fromStock, toStock, remarks) 
															VALUES ('$from', '$to', '$qty', '$transferred_on', '$transferred_by', '$fromStock', '$toStock', '$remarks')");
															
															
		// Repeat same for half sheets
		$QuerySheetInHand = mysqli_query($con,"SELECT half_qty FROM sheets_in_hand WHERE user=$from ");
		$newQty = mysqli_fetch_array($QuerySheetInHand,MYSQLI_ASSOC)['half_qty'] - $half_qty;
		if($newQty < 0)
			$commitFlag = false;	
	
		$updateFrom = mysqli_query($con,"UPDATE sheets_in_hand SET half_qty = half_qty - '$half_qty' WHERE user=$from ");
		if(!$updateFrom)
			$commitFlag = false;		

		$queryFrom = mysqli_query($con,"SELECT half_qty FROM sheets_in_hand WHERE user=$from ");
		if(!$queryFrom)
			$commitFlag = false;				

		$fromStock = mysqli_fetch_array($queryFrom,MYSQLI_ASSOC)['half_qty'];
		
		$updateTo = mysqli_query($con,"UPDATE sheets_in_hand SET half_qty = half_qty + '$half_qty' WHERE user=$to ");
		if(!$updateTo)
			$commitFlag = false;				
		
		$queryTo = mysqli_query($con,"SELECT half_qty FROM sheets_in_hand WHERE user=$to ");
		if(!$queryTo)
			$commitFlag = false;		
		
		$toStock = mysqli_fetch_array($queryTo,MYSQLI_ASSOC)['half_qty'];

		$insertLog = mysqli_query($con, "INSERT INTO half_transfer_logs (user_from, user_to, qty, transferred_on, transferred_by, fromStock, toStock, remarks) 
															VALUES ('$from', '$to', '$half_qty', '$transferred_on', '$transferred_by', '$fromStock', '$toStock', '$remarks')");
		if(!$insertLog)
			$commitFlag = false;

		if($commitFlag)
		{
			mysqli_commit($con);	
			echo '<script type="text/javascript"> 
			window.location.href="transfer.php"; 
			</script>';
		}
		else
		{
			mysqli_rollback($con);
			echo '<script type="text/javascript"> 
			window.location.href="transfer.php?error=true"; 
			</script>';
		}
	}
?>	
<html>
	<style>
	.list-group li {
		list-style: none;
	}
	.panel-info, .panel-rating, .panel-more1 {
		float: left;
		margin: 0 10px;
	}
	
	.modal-footer button {
	  float:right;
	  margin-left: 10px;
	}
	
	</style>
	<head>
		<title>Transfer Stock</title>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
	</head>
	<body>
		<br/><br/>
		<div align="center">
			<h2><i class="fa fa-exchange"></i>&nbsp;&nbsp;Tranfer Sheets</h2><br/>
			<form action="" method="post" style="width:400px;border:1px solid black">
				<br/><br/>
				<div class="form-group">
					<div class="col-md-4 col-md-offset-3">
						<input type="text" name="qty" class="form-control" placeholder="Qty" pattern="[0-9]+" title="Input a valid number"><br/><br/>
					</div>	
					<div class="col-md-4 col-md-offset-3">
						<input type="text" name="half_qty" class="form-control" placeholder="Half Qty" pattern="[0-9]+" title="Input a valid number"><br/><br/>
					</div>						
				</div>	

				<div class="form-group row">
					<div style="margin-left:30px;">
						<select required name="from" id="from" class="form-control">
							<option value="">--- FROM ---</option><?php
							foreach($userMap as $userId => $userName)
							{																																			?>
								<option value="<?php echo $userId;?>"><?php echo $userName;?></option> 						<?php
							}																																			?>
						</select>
					</div>
					<div>
						<span class="input-group-text"><i class="fa fa-angle-double-right"></i><i class="fa fa-angle-double-right"></i></span>
					</div>						
					<div>
						<select required name="to" id="to" class="form-control">
							<option value="">---- TO ----</option><?php
							foreach($userMap as $userId => $userName)
							{																																			?>
								<option value="<?php echo $userId;?>"><?php echo $userName;?></option> 						<?php
							}																																			?>
						</select>
					</div>						
				</div>		
				<div class="form-group">
					<div style="width:60%">
						<input type="text" name="remarks" id="remarks" class="form-control" placeholder="Remarks"/>
					</div>
				</div>
				<br/><br/><br/><br/><br/><br/>
				<div class="form-group">
					<button type="submit" class="btn btn-info">Submit</button>
				</div>
			</form>
		</div>	 			
		<script>

			$(function(){

				var menu = $('.menu-navigation-dark');

				menu.slicknav();

				// Mark the clicked item as selected

				menu.on('click', 'a', function(){
					var a = $(this);

					a.siblings().removeClass('selected');
					a.addClass('selected');
				});
				
				var error = "<?php echo $error;?>";
				if(error == 'true')
				{
					bootbox.alert("Not enough sheets in hand to deliver!!!");					
				}				
			});

		</script>				
	</body>
</html>																								<?php
}
else
	header("Location:../index.php");
