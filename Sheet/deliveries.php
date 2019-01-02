<?php
	require '../connect.php';																															
	$sheets = mysqli_query($con,"SELECT * FROM sheets WHERE status ='delivered' ORDER BY date ASC" ) or die(mysqli_error($con));		 	 
	
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
			function closeRequest(id){
				var conf = confirm("Are you sure?");
				if(conf)
				{
					hrf = 'close.php?';
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
									<p><i class="fa fa-user"></i> <?php echo $sheet['name'];?>
									, <i class="fa fa-phone"></i> <a href="tel:<?php echo $sheet['phone'];?>"><?php echo $sheet['phone'];?></a></p>
									<p><i class="fa fa-copy"></i> <?php echo $sheet['qty'].' Nos';?></p>
									<p><i class="fa fa-calendar"></i> <?php echo date("d-m-Y",strtotime($sheet['date']));?></p>
									<p><i class="fa fa-truck"></i> Delivered by <?php echo $sheet['delivered_by'];?></p>
								</div>
								<br/>
							</div>
							<div align="center">
								<a href="edit.php?id=<?php echo $sheet['id'];?>" class="btn btn-primary" style="width:100px;">Edit</a>&nbsp;&nbsp;								
								<button class="btn btn-danger" style="width:100px;" onclick="closeRequest(<?php echo $sheet['id'];?>)">Close</button>				
							</div>
							<br/><br/>
						</div>
					</li>																					<?php				
				}																							?>

			</ul>
		</div>
	</body>
</html>
