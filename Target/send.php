<?php
require '../connect.php';
require 'sendMessage.php';
ini_set('max_execution_time', '0');
ini_set('memory_limit', '-1');

if(isset($_POST["submit"])) 
{
	if(isset($_FILES["file"])) 
	{
		if($_FILES["file"]["error"] > 0)
		{
			echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
		}
		else 
		{
			$checkMap = array();
			$tmpName = $_FILES['file']['tmp_name'];
			$csvAsArray = array_map('str_getcsv', file($tmpName));	
			foreach($csvAsArray as $index => $row)
			{
				$message = $_POST['message'];
				$phone = '91'.$row[0];	
				$message = str_replace("[C2]",$row[1],$message);
				if(isset($row[2]))
					$message = str_replace("[C3]",$row[2],$message);
				if(isset($row[3]))
					$message = str_replace("[C4]",$row[3],$message);
				
				$checkMap[$phone]['message'] = $message;
				$checkMap[$phone]['status'] = 'Not Sent';
				$status = sendMessage($message,$phone);
				foreach($status as $key => $value)
					$checkMap[$phone]['status'] = $key;
			}
			sleep(2);
			foreach($checkMap as $phone => $row)
			{
				$message = $row['message'];
				$status = $row['status'];
				if($status == 'Not Sent')
				{
					echo 'RESEND ACTIVATED IN LOOP 2<br/>';
					$status = sendMessage($message,$phone);
					foreach($status as $key => $value)
						$checkMap[$phone]['status'] = $key;
				}
			}
			sleep(2);
			foreach($checkMap as $phone => $row)
			{
				$message = $row['message'];
				$status = $row['status'];
				if($status == 'Not Sent')
				{
					echo 'RESEND ACTIVATED IN LOOP 3<br/>';
					$status = sendMessage($message,$phone);
					foreach($status as $key => $value)
						$checkMap[$phone]['status'] = $key;
				}
			}			
		}
	} 
	else 
	{
		echo "No file selected <br />";
	}
}																																											?>
<html>
	<body>
		<div align="center">
			Whatsapp number in column 1<br/>
			Add [C2] for column2, [C3] for column3 and so on ...
			<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" enctype="multipart/form-data">
				<br/><br/>
				<textarea name="message" rows="4" cols="100"></textarea><br/><br/>
				<input type="file" name="file" id="file"/><br/><br/>
				<input type="submit" name="submit" />
			</form>
		</div>
		<br/><br/>
	</body>
</html>