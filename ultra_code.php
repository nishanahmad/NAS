<?php
require 'connect.php';

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
			/*
			$arMap = array();
			$arList = mysqli_query($con, "SELECT * FROM ar_details WHERE child_code IS NOT NULL") or die(mysqli_error($con));
			foreach($arList as $ar)
				$arMap[$ar['child_code']] = $ar['id'];
			*/
			
			$tmpName = $_FILES['file']['tmp_name'];
			$csvAsArray = array_map('str_getcsv', file($tmpName));
			foreach($csvAsArray as $index => $row)
			{
				$phone = $row[0];
				$code = $row[1];
				
				$update = mysqli_query($con, "UPDATE ar_details SET ultra_code = '$code' WHERE mobile = '$phone' ") or die(mysqli_error($con));
			}
		}
	}
}	
?>
<html>
	<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" enctype="multipart/form-data">
		<br/>
		<input type="file" name="file" id="file"/><br/><br/>
		<br/>
		<div class="offset-5"><button type="submit" name="submit">Upload</button></div>
	</form>
</html>