<?php
session_start();
if(isset($_SESSION["user_name"]))
{
	require '../connect.php';
    
	$id = $_GET['id'];
	$sql = mysqli_query($con,"SELECT * FROM ar_details WHERE id='$id'") or die(mysqli_error($con));
	$ar = mysqli_fetch_array($sql,MYSQLI_ASSOC);	
?>
<html>
	<head>
		<title><?php echo $ar['name'];?></title>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="../css/jquery-ui.css">
		<link rel="stylesheet" type="text/css" href="../css/companySale.css" />
		<script type="text/javascript" language="javascript" src="../js/jquery.js"></script>
		<script type="text/javascript" language="javascript" src="../js/jquery-ui.min.js"></script>
	</head>
	<body>
		<form name="frmUser" method="post" action="insert.php">
			<div style="width:100%;">
			<div align="center" style="padding-bottom:5px;">
				<a href="../index.php" class="link"><img alt='home' title='home' src='../images/homeBrown.png' width='50px' height='50px'/> </a> &nbsp;&nbsp;&nbsp;
			</div>
			<br>
			<table border="0" cellpadding="15" cellspacing="0" width=50%" align="center" style="float:center" class="tblSaveForm">
				<tr class="tableheader">
					<td colspan="4"><div align ="center"><b><font size="4"><?php echo $ar['name'];?></font><b></td>
				</tr>
				<tr>
					<td><label>Name</label></td>
					<td colspan="3"><input type="text" class="txtField" name="name" required value="<?php echo $ar['name']; ?>" /></td>
				</tr>
				<tr>
					<td><label>Mobile</label></td>
					<td colspan="3"><input type="text" class="txtField" name="name"  value="<?php echo $ar['mobile']; ?>" /></td>
				</tr>				
				<tr>
					<td><label>Shop Name</label></td>
					<td colspan="3"><input type="text" class="txtField" name="name" value="<?php echo $ar['shop_name']; ?>" /></td>
				</tr>				
				<tr>
					<td><label>SAP</label></td>
					<td colspan="3"><input type="text" name="sap" class="txtField" value="<?php echo $ar['sap_code']; ?>" /></td>
				</tr>
				<tr>
					<td colspan="4"><div align="center"><input type="submit" name="submit" value="Submit" class="btnSubmit"></div></td>
				</tr>
			</table>
			</div>
		</form>
	</body>
</html>
<?php
}
else
	header("Location:../index.php");
?>