<!DOCTYPE html>
<?php
session_start();
if(isset($_SESSION["user_name"]))
{
echo "LOGGED USER : ".$_SESSION["user_name"] ;	

require '../connect.php';

$saleQuery = mysqli_query($con,"SELECT * FROM nas_sale WHERE sales_id='" . $_GET["sales_id"] . "'") or die(mysqli_error($con));				 
$sale= mysqli_fetch_array($saleQuery,MYSQLI_ASSOC);

$result = mysqli_query($con,"SELECT * FROM sale_edits WHERE sale_id='" . $_GET["sales_id"] . "' ORDER BY edited_on DESC") or die(mysqli_error($con));				 
?>

<html>

<head>

  <meta charset="UTF-8">
	<title>Last Modified Information</title>
	<link href="../css/bootstrap.min.css" rel="stylesheet">
	<link href="../css/dashio.css" rel="stylesheet">
	<link href="../css/dashio-responsive.css" rel="stylesheet">  
	<script type="text/javascript" src="../js/bootstrap.min.js"></script>
	<div align="center" style="padding-bottom:5px;">
	<a href="../index.php" class="link"><img alt='Home' title='Home' src='../images/homeBlack.png' width='60px' height='60px'/></a>
	<a href="edit.php?sales_id=<?php echo $sale["sales_id"]; ?>"  class="link" >
		<img alt= 'Edit' title='Edit' src='../images/editblack.png' width='60px' height='60px'hspace='10'  /></a><br><br>
</head>

<body>
	<div class="col-md-10 col-md-offset-1">	
		<div class="row mt">
			<div class="content-panel">
				<h3 style="margin-left:100px;"><b>Record created by <?php echo "<font color='red'>".$sale['entered_by']."</font>"; ?> on <?php
					$currentDateTime = $sale['entered_on'];
					$newDateTime = date('M d, Y @ h:i A', strtotime($currentDateTime));
					echo $newDateTime;?>
				</h3>
				<br/>
				<section id="unseen">
					<table class="table table-bordered table-condensed col-md-offset-1" style="width:60%;">
						<tr>
							<th></th>
							<th>Modified By</th>
							<th>Old Value</th>
							<th>New Value</th>
							<th>Modified On</th>
						</tr>																																				<?php 
						while($row= mysqli_fetch_array($result,MYSQLI_ASSOC))
						{																																					?>
						<tr>
							<td><?php echo $row['field'];?></td>
							<td><?php echo $row['edited_by'];?></td>
							<td><?php echo $row['old_value'];?></td>
							<td><?php echo $row['new_value'];?></td>
							<td><?php echo date('h:i A, M d', strtotime($row['edited_on']));?></td>
						</tr>																																			<?php	
						}																																					?>		
					</table>	
				</section>
			</div>
		</div>
	</div>
</body>
</html>

<?php
}
else
	header("Location:../loginPage.php");

?>