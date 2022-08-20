<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require '../connect.php';
if(isset($_SESSION["user_name"]))
{			
	$driversQuery = mysqli_query($con,"SELECT user_id,user_name FROM users WHERE role ='driver' ORDER BY user_name") or die(mysqli_error($con));
	foreach($driversQuery as $driver)
		$drivers[$driver['user_id']] = $driver['user_name'];?>
	
	<form action="ignore.php">
		<div class="modal fade" id="viewModal" style="margin-top:100px;">
		  <div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title"><i class="fas fa-clone"></i>&nbsp;&nbsp;Duplicate Check</h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				</div>
				<div class="modal-body">
					<br/>
					<div class="card" id="duplicateCard">
						<div class="card-header" style="background-color:#2a739e;color:#ffffff;font-family:Bookman;text-transform:uppercase;"><i class="fa fa-map-marker"></i> <input style="background-color:#2a739e;color:#ffffff" id="area"/> <font style="margin-left:10%;font-weight:bold"><input style="background-color:#2a739e;color:#ffffff" id="driver_area"/></font><input style="background-color:#2a739e;color:#ffffff" name="sheet_id" id="sheet_id" style="float:right"/></div>
						<div class="card-body">
							<p id="priority" style="color:#cc0000;font-size:18px;"><b><i class="fas fa-exclamation-triangle"></i> Priority Site</b></p>
							<p><i class="fa fa-user"></i> Cust  : <input style="width:150px;" id="customer_name"/>, <i class="fa fa-mobile"></i> <input id="customer_phone"/></p>
							<p><i class="fa fa-user"></i> Mason: <input style="width:150px;" id="mason_name"/>, <i class="fa fa-mobile"></i> <input id="mason_phone"/>
							<p><i class="fa fa-calendar"></i> <input id="sheet_date"/>, <i class="fa fa-shopping-bag"></i> <input id="bags"/></p>
							<p><i class="fas fa-store"></i><input id="shop"/></p>
							<p><i class="fas fa-desktop"></i> Req by <b><input id="requested_by"/></b> on <input id="created_on"/></p>
							<p><i class="fa fa-align-left"></i> <input id="remarks"/></p>
							<p><i class="fa fa-share"></i> Assigned to <b><input id="assigned_to"/></b></p>
							<br/>
						</div>
					</div>		
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn" style="color:#FFFFFF;background-color:#4BC076"><i class="fas fa-ban"></i>&nbsp;Ignore Duplicate</button>
				</div>
			</div>
		  </div>
		</div>	
	</form>
	</script>		
	<?php
}
else
	header( "Location: ../index.php" );	