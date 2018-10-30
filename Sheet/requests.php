<?php
	require '../connect.php';																															
	$sheets = mysqli_query($con,"SELECT * FROM sheet_requests WHERE status IS NULL ORDER BY date ASC" ) or die(mysqli_error($con));		 	 
	
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
	</style>
	<head>
		<title>Sheets</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="../css/font-awesome.min.css">
		<script type="text/javascript" src="../js/jquery.js"></script> 
		<script type="text/javascript" src="../js/bootstrap.min.js"></script> 
		<script>
		function deliver(id){
			var qty = window.prompt("Enter number of sheets delivered to this site");		
			if(isNaN(qty))
			{
				alert('Please enter a valid number');
				return false;
			}
			else
			{
				hrf = 'deliver.php?';
				window.location.href = hrf +"id="+ id + "&qty=" + qty;
			}
		}
		function cancel(id){
			var conf = confirm("This will cancel this request. Are you sure?");
			if(conf)
			{
				hrf = 'cancel.php?';
				window.location.href = hrf +"id="+ id;		
			}
		}
		</script>
	</head>
	<body>
		<div class="container" >
			<ul class="list-group">																			<?php 
				foreach($sheets as $sheet)
				{																							?>
					<li>
						<div class="panel panel-default">
							<div class="panel-body">
								<div class="panel-info">
									<p><i class="fa fa-map-marker"></i><strong> <?php echo $sheet['area'];?></strong></p>
									<p>Mason : <i class="fa fa-user"></i> <?php echo $sheet['masonName'];?>
									, <i class="fa fa-phone"></i> <a href="tel:<?php echo $sheet['masonPhone'];?>"><?php echo $sheet['masonPhone'];?></a></p>												<?php
									if(!empty($sheet['customerName']) || !empty($sheet['customerPhone']))
									{																																							?>
										<p>Customer : <i class="fa fa-user"></i> <?php echo $sheet['customerName'];?>
										, <i class="fa fa-phone"></i> <a href="tel:<?php echo $sheet['customerPhone'];?>"><?php echo $sheet['customerPhone'];?></a></p><?php										
									}																																												?>
									<p><i class="fa fa-copy"></i> <?php echo $sheet['qty'].' Nos';?></p>
									<p><i class="fa fa-calendar"></i> <?php echo date("d-m-Y",strtotime($sheet['date']));?></p>
									<p><i class="fa fa-truck"></i> Requested by <?php echo $sheet['fe'];?></p>
								</div>
								<br/>
							</div>
							<div align="center">
								<button class="btn btn-primary" onclick="deliver(<?php echo $sheet['id'];?>)">Deliver</button>&nbsp;&nbsp;&nbsp;&nbsp;				
								<button class="btn btn-danger" onclick="cancel(<?php echo $sheet['id'];?>)">Cancel</button>				
							</div>
							<br/><br/>
						</div>
					</li>																					<?php				
				}																							?>

			</ul>
		</div>
	</body>
</html>