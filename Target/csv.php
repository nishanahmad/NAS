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
			$files = glob("send.csv");
			foreach($files as $file)
			{
				$csvFile = file($file);
				foreach ($csvFile as $row)
				{
					$message = "Dear AR, Ur OCT Month Trgt is ".$row[1]." Bgs. Achieve Ur Trgt Earn Full Lakshya Benefits - AR HELP";
					$phone = '91'.$row[0];
					echo $phone.'<br/>';
					echo $message.'<br/>';
					//$status = sendMessage($message,$phone);
				}
			}
		?>
	</body>
</html>