<?php
require '../connect.php';
require 'sendMessage.php';
?>
<html>
	<head>
	
	</head>
	<body>
		<div align="center"><input type="image" id = "Home" src="Images/home.png" onClick="window.location.href='PharmacyReorder.html'">
		<div align="center"><h2><font face="Tahoma, Trebuchet MS">Upload CSV</font></h2></div>
		<br /><br />
		<div align="center">
			<form method="post" enctype="multipart/form-data" >
				<table>
					<tr><td>File</td><td><input type="file" name="ip_file" /></td></tr>
					<tr><td colspan="2"><input type="submit" name="Submit" /></td></tr>
				</table>
			</form>
		</div>
		<?php
			if ((isset($_FILES["ip_file"])) && ($_FILES["ip_file"]["error"] <= 0))
			{
				$file_handle = fopen($_FILES["ip_file"]["tmp_name"], "r");

				while (!feof($file_handle))
				{
					$row = fgetcsv($file_handle);
					if($row)
					{
						$phone = '91'.$row[0];
						$message = "Dear AR, Ur AR Incentive credited in your A/C is Rs. ".$row[1]." upto June 2020.";
						//echo $message.'<br/>';
						$status = sendMessage($message,$phone);
					}
				}
				fclose($file_handle);
			}
		?>
	</body>
</html>