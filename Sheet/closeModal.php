<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require '../connect.php';
if(isset($_SESSION["user_name"]))
{			
	$driversQuery = mysqli_query($con,"SELECT user_id,user_name FROM users WHERE role ='driver' ORDER BY user_name") or die(mysqli_error($con));
	foreach($driversQuery as $driver)
		$drivers[$driver['user_id']] = $driver['user_name'];?>
	
	<div class="modal fade" id="closeModal" style="margin-top:100px;">
	  <div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header" style="background-color:#7dc37d;color:white">
				<h4 class="modal-title"><i class="fas fa-check"></i>&nbsp;&nbsp;Close</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body">
				<br/>
				<form name="closeForm" id="closeForm" method="post" action="close.php">
					<p id="insertError" style="color:red;"></p>
					<input type="hidden" id="closeIdhidden" name="closeIdhidden">
					<div class="col col-md-7 offset-1">
						<div class="input-group mb-3">
							<span class="input-group-text" style="width:100px;"><i class="fab fa-buffer"></i>&nbsp;Full Qty</span>
							<input type="text" name="qty" id="qty" class="form-control" autocomplete="off">
						</div>
					</div>
					<div class="col col-md-7 offset-1">
						<div class="input-group mb-3">
							<span class="input-group-text" style="width:100px;"><i class="fab fa-buffer"></i>&nbsp;Half Qty</span>
							<input type="text" name="half_qty" id="half_qty" class="form-control" autocomplete="off">
						</div>
					</div>					<?php
					if($_SESSION['role'] != 'driver')
					{																																?>
						<div class="col col-md-7 offset-1">
							<div class="input-group mb-3">
								<span class="input-group-text" style="width:100px;"><i class="fa fa-user"></i>&nbsp;Driver</span>
								<select name="driverId" id="driverId" required class="form-control">
									<option value = "">---Select---</option>																						<?php
									foreach($drivers as $id => $name) 
									{																							?>
										<option value="<?php echo $id;?>"><?php echo $name;?></option>			<?php	
									}																							?>
								</select>							
							</div>
						</div>																														<?php									
					}																																?>

					<br/><br/>
					<div class="row">
						<div class="col col-md-5 offset-5">
							<div class="input-group mb-3">
								<button type="submit" class="btn" id="saveNew" style="width:100px;font-size:18px;background-color:#7dc37d;color:white;"><i class="fa fa-save"></i> Save</button>				 
							</div>
						</div>							
					</div>
				</form>			
			</div>
			<div class="modal-footer">
			</div>
		</div>
	  </div>
	</div>	
	</script>		
	<?php
}
else
	header( "Location: ../index.php" );	