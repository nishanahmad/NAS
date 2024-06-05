<!DOCTYPE html>
<?php

	require '../connect.php';
	
	$telegram_contacts = mysqli_query($con,"SELECT * FROM telegram_contacts ORDER BY area") or die(mysqli_error($con));	

	?>
	
<html>
	<head>
		<link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" rel="stylesheet" type="text/css">
		<link href="../css/styles.css" rel="stylesheet" type="text/css">
		<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/jquery.tablesorter.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/jquery.tablesorter.widgets.min.js" ></script>
		<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
		<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>	
		<title>Telegram contacts</title>
	</head>
	<body>
		<nav class="navbar navbar-light bg-light sticky-top bottom-nav">
			<span class="navbar-brand" style="font-size:25px;margin-left:47%;"><i class="fa fa-rupee-sign"></i> Rate</span>
			<a href="#" class="btn btn-sm" role="button" style="background-color:#54698D;color:white;float:right;margin-right:40px;" data-toggle="modal" data-target="#newModal"><i class="fa fa-rupee-sign"></i> New Rate</a>			
		</nav>
		<div style="width:100%;" class="mainbody">	
			<div id="snackbar"><i class="fa fa-rupee-sign"></i>&nbsp;&nbsp;New Rate updated successfully !!!</div>		
			<div align="center">
				<br/><br/>
				<table class="ratetable table table-hover table-bordered" style="width:30%">
					<thead>
						<tr class="table-info">
							<th> Area</th>
							<th> Name</th>
							<th> Phone</th>
						</tr>
					</thead>
					<tbody>																														<?php				
						foreach($telegram_contacts as $ar)
						{																														?>
							<tr>
								<td><?php echo $ar['area'];?></td>
								<td><?php echo $ar['name'];?></td>
								<td><?php echo $ar['phone'];?></td>
							</tr>																											<?php
						}																													?>
					</tbody>																														
				</table>
			</div>
			<br/><br/><br/>
		</div>
		<script src="list.js"></script>
	</body>
</html>																																					