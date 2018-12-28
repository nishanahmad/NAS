<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
if(isset($_SESSION["user_name"]))
{
require '../connect.php';
echo "LOGGED USER : ".$_SESSION["user_name"] ;	
$engMap[null] = null;
$arObjects = mysqli_query($con,"SELECT id,name,type FROM ar_details ORDER BY name") or die(mysqli_error($con));	
foreach($arObjects as $ar)
{
	if($ar['type'] != 'Engineer Only')
		$arMap[$ar['id']] = $ar['name']; 
	if($ar['type'] == 'Engineer' || $ar['type'] == 'Contractor' || $ar['type'] == 'Engineer Only')
		$engMap[$ar['id']] = $ar['name'];
}
$result = mysqli_query($con,"SELECT * FROM nas_sale WHERE sales_id='" . $_GET["sales_id"] . "'") or die(mysqli_error($con));	
$row= mysqli_fetch_array($result,MYSQLI_ASSOC);
?>

<html>
<head>
<title>Edit Sale <?php echo $row['sales_id']; ?></title>
<link rel="stylesheet" type="text/css" href="../css/newEdit.css" />
<link rel="stylesheet" href="../css/button.css">
</head>
<body>
<form name="frmUser" method="post" action="update.php">
<input hidden name="id" value="<?php echo $row['sales_id'];?>">
<div style="width:100%;">

<div align="center" style="padding-bottom:5px;">
	<a href="../index.php" class="link"><img alt='Home' title='Home' src='../images/home.png' width='50px' height='50px'/></a>&nbsp;&nbsp;
	<a href="todayList.php?ar=all" class="link"><img alt='List' title='List' src='../images/list_icon.jpg' width='50px' height='50px'/></a>
	<a href="../modified_by.php?sales_id=<?php echo $row["sales_id"]; ?>"  class="link" >
		<img align="right" alt= 'Modified By' title='Modified By' src='../images/user.png' width='40px' height='50px'hspace='10'  /></a>
		
</div>

<br>
<div align ="center">
<table border="0" cellpadding="10" cellspacing="0" width="80%" align="center" class="tblSaveForm">
<tr class="tableheader">
<td colspan="4" style="text-align:center;"><b><font size="4">Edit Sale <?php echo $row['sales_id']; ?> </font><b></td>
</tr>

<tr>
<td><label>Date</label></td>
<td><input type="text" name="entryDate" class="txtField" 
	value="<?php 
			$originalDate1 = $row['entry_date'];
			$newDate1 = date("d-m-Y", strtotime($originalDate1));
			echo $newDate1; ?>">
</td>

<td><label>Remarks</label></td>
<td><input type="text" name="remarks" class="txtField" value="<?php echo $row['remarks']; ?>"></td>


</tr>
<tr>
<td><label>AR</label></td>
<td><select name="ar" required class="txtField">
    <option value = "<?php echo $row['ar_id'];?>"><?php echo $arMap[$row['ar_id']];?></option>
    <?php
		foreach($arMap as $arId => $arName)
		{?>
			<option value="<?php echo $arId;?>"><?php echo $arName;?></option>			
<?php	}
?>
      </select>
</td>

<td><label>Bill No </label></td>
<td><input type="text" name="bill" class="txtField" value="<?php echo $row['bill_no']; ?>"></td>
</tr>

<td><label>Truck No </label></td>
<td><input type="text" name="truck" class="txtField" value="<?php echo $row['truck_no']; ?>"></td>


<td><label>Customer Name</label></td>
<td><input type="text" name="customerName" class="txtField" value="<?php echo $row['customer_name']; ?>"></td>
</tr>

<td><label>SRP</label></td>
<td><input type="text" name="srp" class="txtField" value="<?php $srp = $row['srp'];echo $row['srp'];?>">
</td>

<td><label>Address Part 1</label></td>
<td><input type="text" name="address1" class="txtField" value="<?php echo $row['address1']; ?>"></td>
</tr>

<td><label>SRH</label></td>

<td><input type="text" name="srh" class="txtField" value="<?php echo $row['srh']; ?>"></td>

<td><label>Address Part 2</label></td>
<td><input type="text" name="address2" class="txtField" value="<?php echo $row['address2']; ?>"></td>
</tr>

<td><label>F2R</label></td>
<td><input type="text" name="f2r" class="txtField" value="<?php echo $row['f2r']; ?>"></td>


<td><label>Customer Phone</label></td>
<td><input type="text" name="customerPhone" class="txtField" value="<?php echo $row['customer_phone']; ?>"></td>
</tr>

<td><label>Return</label></td>
<td><input type="text" name="return" class="txtField" value="<?php echo $row['return_bag']; ?>"></td>

<td><label>Engineer</label></td>
<td><select name="engineer" class="txtField">
		<option value="<?php echo $row['eng_id'];?>"><?php echo $engMap[$row['eng_id']];?></option>																																<?php
		foreach($engMap as $engId => $engName)
		{	
			if($engId != $row['eng_id'])
			{																																			?>
				<option value="<?php echo $engId;?>"><?php echo $engName;?></option><?php
			}																																			?>																																						<?php		
		}																																				?>
      </select>
</td>

<tr>
<td colspan="4" align = "center"><input type="submit" name="submit" value="Submit" class="btnSubmit"></td>
</tr>

</table>
<br><br><br><br>	
<a href="delete.php?sales_id=<?php echo $row['sales_id'];?>" style="float:right;width:50px;margin-right:150px;" class="btn btn-red" onclick="return confirm('Are you sure you want to permanently delete this entry ?')">DELETE</a>						
</div>
</form>
</body>
</html>																								<?php
}
else
	header("Location:../index.php");
