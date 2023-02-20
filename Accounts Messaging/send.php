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
				$message = str_replace("[C=c2]",$row[1],$message);
				if(isset($row[2]))
				{
					$message = str_replace("[C3]",$row[2],$message);
					$message = str_replace("[c3]",$row[2],$message);
				}
				if(isset($row[3]))
				{
					$message = str_replace("[C4]",$row[3],$message);
					$message = str_replace("[c4]",$row[3],$message);
				}
				if(isset($row[4]))
				{
					$message = str_replace("[C5]",$row[4],$message);
					$message = str_replace("[c5]",$row[4],$message);
				}
				if(isset($row[5]))
				{
					$message = str_replace("[C6]",$row[5],$message);
					$message = str_replace("[c6]",$row[5],$message);
				}
					
				sleep(1);
				$status = sendMessage($message,$phone);
			}
		}
	}
	else 
	{
		echo "No file selected <br />";
	}
}																																											?>
<html>
	<head>
		<link href="../css/styles.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
		<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>
	</head>
	<body>
	  <br/><br/>	
	  <div class="col-5 offset-3">
		<div class="card">
		  <div class="card-header" style="background-color:#43B5B5;color:#FFFFFF;font-weight:bold">
			Guidelines for CSV file
		  </div>
		  <div class="card-body">
			  <ul>
				<li>Always keep the Whatsapp number in column 1</li>
				<li>Add <b>[C2]</b> for second column, <b>[C3]</b> for third column and so on ...</li>
			  </ul>
		  </div>
		</div>	
	   </div>
		<br/>
		
		<div class="col-5 offset-3">
		<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" enctype="multipart/form-data">
			<br/>
			<input type="file" name="file" id="file"/><br/><br/>
			<br/>
			<i>Replace the message box given below with the message you want to send<i>
			<textarea name="message" rows="4" cols="84" id="message">Hello, your current balance is [C2].</textarea><br/><br/>
			
			<div class="offset-5"><button type="submit" name="submit">Send</button></div>
		</form>
			<button name="preview" id="preview">Preview</button>
		</div>
		<br/><br/>
		<hr />
		<div id="dvCSV"></div>		
	<script>
		$(function () {
			$("#preview").bind("click", function () {
				var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.csv|.txt)$/;
				if (regex.test($("#file").val().toLowerCase())) {
					if (typeof (FileReader) != "undefined") {
						var reader = new FileReader();
						reader.onload = function (e) {
							var mainString = "";
							console.log(mainString);
							var rows = e.target.result.split("\n");
							for (var i = 0; i < rows.length; i++) {
								var msg = $("#message").val();
								var cells = rows[i].split(",");
								if (cells.length > 1) {
									for (var j = 0; j < cells.length; j++) {
										if(j == 1)
										{
											msg = msg.replace("[C2]", cells[j]);
											msg = msg.replace("[c2]", cells[j]);
										}
										if(j == 2)
										{
											msg = msg.replace("[C3]", cells[j]);
											msg = msg.replace("[c3]", cells[j]);
										}
										if(j == 3)
										{
											msg = msg.replace("[C4]", cells[j]);
											msg = msg.replace("[c4]", cells[j]);
										}
										if(j == 4)
										{
											msg = msg.replace("[C5]", cells[j]);
											msg = msg.replace("[c5]", cells[j]);
										}
										if(j == 5)
										{
											msg = msg.replace("[C6]", cells[j]);
											msg = msg.replace("[c6]", cells[j]);
										}
									}
									mainString = mainString + msg + '<br/>';									
								}
							}
							$("#dvCSV").html('');
							$("#dvCSV").append(mainString);
						}
						reader.readAsText($("#file")[0].files[0]);
						return false;
					} else {
						alert("This browser does not support HTML5.");
					}
				} else {
					alert("Please upload a valid CSV file.");
				}
			});
		});
	</script>	
	</body>
</html>