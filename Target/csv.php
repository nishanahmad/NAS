<?php
require 'sendMessage.php';
ini_set('max_execution_time', '0');
ini_set('memory_limit', '-1');
?>
<html>
	<head>
	
	</head>
	<body>
		<div align="center"><h2><font face="Tahoma, Trebuchet MS">Upload CSV</font></h2>
		<br /><br/>
		<?php
			$files = glob("send.csv");
			foreach($files as $file)
			{
				$csvFile = file($file);
				foreach ($csvFile as $str)
				{
					$row = explode(",",$str);
					$message ="DEAR AR, YOUR FEB MONTH TRGT IS ".$row[1]." BAGS. ACHIEVE UR TARGET AND EARN FULL LAKSHYA BENEFITS - AR HELP";
/*					
$message = 
"Beach Residency, Kannur would love your feedback. Post a review to our profile.

https://g.page/r/CWic1xXVc4IFEB0/review";
// Change the instance ids also
*/
					$phone = '91'.$row[0];
					echo $phone.'<br/>';
					echo $message.'<br/>';
					//sleep(1);
					//$status = sendMessage($message,$phone);
				}
			}
		?>
		</div>
	</body>
</html>