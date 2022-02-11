<?php
require 'sendMessage.php';
ini_set('max_execution_time', '0');
ini_set('memory_limit', '-1');
?>
<html>
	<head>
	
	</head>
	<body>
		<div align="center"><input type="image" id = "Home" src="Images/home.png" onClick="window.location.href='PharmacyReorder.html'">
		<div align="center"><h2><font face="Tahoma, Trebuchet MS">Upload CSV</font></h2></div>
		<br /><br/>
		<?php
			$files = glob("send.csv");
			foreach($files as $file)
			{
				$csvFile = file($file);
				foreach ($csvFile as $str)
				{
					$row = explode(",",$str);
					$message = "DEAR AR, CONGRATS!! U HAVE CREDITED ".$row[1]." PLUS POINTS FOR JAN 2022. NOW U HAVE ".$row[2]." PLUS POINTS IN UR ACCOUNT.  AR HELP";
					$phone = '91'.$row[0];
					echo $phone.'<br/>';
					echo $message.'<br/>';
					//sleep(2);
					//$status = sendMessage($message,$phone);
				}
			}
		?>
	</body>
</html>