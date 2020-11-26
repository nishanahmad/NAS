<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require '../connect.php';
if(isset($_SESSION["user_name"]))
{																																							?>
	<div class="modal fade" id="forwardModal">
	  <div class="modal-dialog modal-md" style="width:70%">
		<div class="modal-content">
			<div class="modal-header" style="background-color:#54698D;color:white">
				<h4 class="modal-title"><i class="fa fa-bolt"></i>&nbsp;&nbsp;Forward</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body">
				<form name="forwardForm" id="forwardForm" method="post" action="update.php">
					<div class="col col-md-10 offset-1">
						<input type="text" required name="remarks" id="remarks" class="form-control" autocomplete="off">
					</div>	
				</form>
			</div>
		</div>
		<div class="modal-footer">
		</div>
		</div>
	  </div>
	  <?php
}
else
	header( "Location: ../index.php" );