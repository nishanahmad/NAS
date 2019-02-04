<!DOCTYPE html>
<?php
session_start();
if(isset($_SESSION["user_name"]))
{
echo "LOGGED USER : ".$_SESSION["user_name"] ;	

require '../connect.php';

$result = mysqli_query($con,"SELECT * FROM nas_sale WHERE sales_id='" . $_GET["sales_id"] . "'") or die(mysqli_error($con));				 
		 
$row= mysqli_fetch_array($result,MYSQLI_ASSOC);
?>

<html>

<head>

  <meta charset="UTF-8">
	<title>Last Modified Information</title>
  <link rel="stylesheet" type="text/css" href="../css/responstable.css">
<div align="center" style="padding-bottom:5px;">
	<a href="../index.php" class="link"><img alt='Home' title='Home' src='../images/homeBlack.png' width='60px' height='60px'/></a>
	<a href="edit.php?sales_id=<?php echo $row["sales_id"]; ?>"  class="link" >
		<img alt= 'Edit' title='Edit' src='../images/editblack.png' width='60px' height='60px'hspace='10'  /></a><br><br>
	<b>Record created by <?php echo "<font color='red'>".$row['entered_by']."</font>"; ?> on <?php
			$currentDateTime = $row['entered_on'];
			$newDateTime = date('M d, Y @ h:i A', strtotime($currentDateTime));
			echo $newDateTime;?>

</head>

<body>
<br/><br/><br/><br/>
<table class="responstable">
	<tr>
		<th></th>
		<th>Modified On</th>
		<th>Modified By</th>
	</tr>																																				<?php 
	if($row['entry_date_mod'] != null)
	{																																					?>
		<tr>
			<td>Date</td>
			<td><?php echo date('M d, Y @ h:i A', strtotime($row['entry_date_dt']));?></td>
			<td><?php echo $row['entry_date_mod'];?></td>
		</tr>																																			<?php	
	}
	if($row['ar_mod'] != null)
	{																																					?>
		<tr>
			<td>Ar</td>
			<td><?php echo date('M d, Y @ h:i A', strtotime($row['ar_dt']));?></td>
			<td><?php echo $row['ar_mod'];?></td>
		</tr>																																			<?php	
	}
	if($row['eng_mod'] != null)
	{																																					?>
		<tr>
			<td>Engineer</td>
			<td><?php echo date('M d, Y @ h:i A', strtotime($row['eng_dt']));?></td>
			<td><?php echo $row['eng_mod'];?></td>
		</tr>																																			<?php	
	}
	if($row['truck_no_mod'] != null)
	{																																					?>
		<tr>
			<td>Truck</td>
			<td><?php echo date('M d, Y @ h:i A', strtotime($row['truck_no_dt']));?></td>
			<td><?php echo $row['truck_no_mod'];?></td>
		</tr>																																			<?php	
	}
	if($row['srp_mod'] != null)
	{																																					?>
		<tr>
			<td>SRP</td>
			<td><?php echo date('M d, Y @ h:i A', strtotime($row['srp_dt']));?></td>
			<td><?php echo $row['srp_mod'];?></td>
		</tr>																																			<?php	
	}
	if($row['srh_mod'] != null)
	{																																					?>
		<tr>
			<td>SRH</td>
			<td><?php echo date('M d, Y @ h:i A', strtotime($row['srh_dt']));?></td>
			<td><?php echo $row['srh_mod'];?></td>
		</tr>																																			<?php	
	}
	if($row['f2r_mod'] != null)
	{																																					?>
		<tr>
			<td>F2R</td>
			<td><?php echo date('M d, Y @ h:i A', strtotime($row['f2r_dt']));?></td>
			<td><?php echo $row['f2r_mod'];?></td>
		</tr>																																			<?php	
	}
	if($row['return_mod'] != null)
	{																																					?>
		<tr>
			<td>Return Bags</td>
			<td><?php echo date('M d, Y @ h:i A', strtotime($row['return_dt']));?></td>
			<td><?php echo $row['return_mod'];?></td>
		</tr>																																			<?php	
	}
	if($row['remarks_mod'] != null)
	{																																					?>
		<tr>
			<td>Remarks</td>
			<td><?php echo date('M d, Y @ h:i A', strtotime($row['remarks_dt']));?></td>
			<td><?php echo $row['remarks_mod'];?></td>
		</tr>																																			<?php	
	}
	if($row['bill_no_mod'] != null)
	{																																					?>
		<tr>
			<td>Bill</td>
			<td><?php echo date('M d, Y @ h:i A', strtotime($row['bill_no_dt']));?></td>
			<td><?php echo $row['bill_no_mod'];?></td>
		</tr>																																			<?php	
	}	
	if($row['customer_name_mod'] != null)
	{																																					?>
		<tr>
			<td>Customer Name</td>
			<td><?php echo date('M d, Y @ h:i A', strtotime($row['customer_name_dt']));?></td>
			<td><?php echo $row['customer_name_mod'];?></td>
		</tr>																																			<?php	
	}	
	if($row['customer_phone_mod'] != null)
	{																																					?>
		<tr>
			<td>Customer Phone</td>
			<td><?php echo date('M d, Y @ h:i A', strtotime($row['customer_phone_dt']));?></td>
			<td><?php echo $row['customer_phone_mod'];?></td>
		</tr>																																			<?php	
	}	
	if($row['address1_mod'] != null)
	{																																					?>
		<tr>
			<td>Address 1</td>
			<td><?php echo date('M d, Y @ h:i A', strtotime($row['address1_dt']));?></td>
			<td><?php echo $row['address1_mod'];?></td>
		</tr>																																			<?php	
	}	
	if($row['address2_mod'] != null)
	{																																					?>
		<tr>
			<td>Address 2</td>
			<td><?php echo date('M d, Y @ h:i A', strtotime($row['address2_dt']));?></td>
			<td><?php echo $row['address2_mod'];?></td>
		</tr>																																			<?php	
	}																																					?>		
</table>	
</body>
</html>

<?php
}
else
	header("Location:../loginPage.php");

?>