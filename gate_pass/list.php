<!DOCTYPE html>
<?php
ini_set('max_execution_time', '0'); // for infinite time of execution 
ini_set('memory_limit', '-1');
session_start();
if(isset($_SESSION["user_name"]))
{
	require '../connect.php';
	require '../navbar.php';
	require 'newModal.php';	

	$passes = mysqli_query($con,"SELECT * FROM gate_pass") or die(mysqli_error($con));																							?>	
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link href="../css/styles.css" rel="stylesheet" type="text/css">
		<link href="../css/navbarMobile.css" media="screen and (max-device-width: 768px)" rel="stylesheet" type="text/css">
		<link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" rel="stylesheet" type="text/css">
		<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.9.1/jquery.tablesorter.min.js" integrity="sha512-mWSVYmb/NacNAK7kGkdlVNE4OZbJsSUw8LiJSgGOxkb4chglRnVfqrukfVd9Q2EOWxFp4NfbqE3nDQMxszCCvw==" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.9.1/jquery.tablesorter.widgets.min.js" integrity="sha512-6I1SQyeeo+eLGJ9aSsU43lGT+w5HYY375ev/uIghqqVgmSPSDzl9cqiQC4HD6g8Ltqz/ms1kcf0takjBfOlnig==" crossorigin="anonymous"></script>
		<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
		<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
		<title>passs</title>
		<style>
			.select2-selection__rendered {
				line-height: 33px !important;
			}
			.select2-container .select2-selection--single {
				height: 38px !important;
			}
			.select2-selection__arrow {
				height: 37px !important;
			}
			#line{
			   display:block;
			   width:220px;
			   border-top: 1px solid #D3D3D3;
			   margin-top:5px;
			   margin-bottom:5px;
			}	
		</style>			
	</head>
	<body>
		<nav class="navbar navbar-light bg-light sticky-top bottom-nav">
			<!--div class="btn-group" role="group" style="float:left;margin-left:2%;">
				<div class="btn-group" role="group">
					<button id="btnGroupDrop1" type="button" class="btn btn-outline-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
						<i class="far fa-calendar-alt"></i> <?php echo $range;?>
					</button>
					<ul class="dropdown-menu" aria-labelledby="btnGroupDrop1" style="cursor:pointer">									
						<li id="todayFilter"><a class="dropdown-item">Today</a></li>							
						<li id="10DaysFilter"><a class="dropdown-item">10 Days</a></li>
						<li id="customFilter" class="dropdown-item">Custom Filter</a></li>				
					</ul>
				</div>
			</div-->
			<span class="navbar-brand" style="font-size:25px;"><i class="fa fa-bolt"></i> passs</span><?php
			if($_SESSION['role'] != 'marketing')
			{																							?>				
				<a href="#" class="btn btn-sm" role="button" style="background-color:#54698D;color:white;float:right;margin-right:3%;" data-toggle="modal" data-target="#passModal"><i class="fa fa-bolt"></i> New pass</a><?php
			}	
			else
			{											 ?>
				<p style="float:right;margin-right:3%;"/><?php
			}											 ?>
		</nav>
		<div style="width:100%;" class="mainbody">	
			<div id="snackbar"><i class="fa fa-bolt"></i>&nbsp;&nbsp;Gate 5minute saved successfully !!!</div>
			<br/><br/>
			<div align="center">
			</div>
			<div id="content-desktop">
				<br/><br/>
				<input type="hidden" id="userRole" value="<?php echo $_SESSION['role'];?>">
				<table class="maintable table table-hover table-bordered" style="width:95%;margin-left:2%;">
					<thead>
						<tr style="background-color:#5ca1bf">
							<th>LR No</th>
							<th>Date</th>
							<th>Time</th>
							<th>Vehicle</th>
							<th>Consignor</th>
							<th>Driver</th>
						</tr>	
					</thead>
					<tbody>	<?php
						foreach($passes as $pass) 
						{																																										?>	
							<tr>
								<td><a href="edit.php?id=<?php echo $pass['id'];?>"><?php echo $pass['id']; ?></td>
								<td><?php echo $pass['date']; ?></td>
								<td><?php echo $pass['time']; ?></td>
								<td><?php echo $pass['vehicle']; ?></td>
								<td><?php echo $pass['consignor_id']; ?></td>
								<td><?php echo $pass['driver']; ?></td>
							</tr>																																		<?php				
						}																																				?>
					</tbody>	
				</table>
			</div>
			<br/><br/><br/>
		</div>
		<script src="list.js"></script>
		<script src="newModal.js"></script>
	</body>
</html>																																					<?php
}
else
	header("Location:../index/home.php");																													?>