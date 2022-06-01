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
					$message = "Dear AR, The Special Target for 24th May to 31st May 2022. Suraksha target is ".$row[1]." bags and C+ target is ".$row[2]." bags. Your total target is ".$row[3]." bags. Achieve target and earn & full Lakshya benefits - AR HELP";
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