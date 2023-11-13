<?php
require '../connect.php';
session_start();
require '../navbar.php';
ini_set('max_execution_time', '0');
ini_set('memory_limit', '-1');
date_default_timezone_set("Asia/Kolkata");

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
			$arMap = array();
			$arList = mysqli_query($con, "SELECT * FROM ar_details WHERE child_code IS NOT NULL") or die(mysqli_error($con));
			foreach($arList as $ar)
				$arMap[$ar['child_code']] = $ar['id'];
			
			$checkMap = array();
			$tmpName = $_FILES['file']['tmp_name'];
			$csvAsArray = array_map('str_getcsv', file($tmpName));
			$firstRow = true;
			foreach($csvAsArray as $index => $row)
			{
				if(!$firstRow)
				{
					if(isset($arMap[trim($row[1])]))
					{
						$date = date('Y-m-d',strtotime(trim($row[0])));
						$arId = $arMap[trim($row[1])];
						$productId = trim($row[2]);
						if(empty(trim($row[3])))
							$qty = '0';
						else
							$qty = trim($row[3]);
						
						$entered_on = date('Y-m-d H:i:s');
						
						$sql="INSERT INTO company_sale (date, ar_id, product, qty, entered_on)
							  VALUES
							 ('$date', '$arId', '$productId', '$qty', '$entered_on')";
						
						$result = mysqli_query($con, $sql) or die(mysqli_error($con));						
					}
					else
					{
						echo 'No matching AR found with Code '.trim($row[1]).'<br/>';
					}

				}
				$firstRow = false;
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
		<title>Upload company sale</title>
	</head>
	<body>
		<nav class="navbar navbar-light bg-light sticky-top bottom-nav">
			<div class="btn-group" role="group" style="float:left;margin-left:2%;">
				<div class="btn-group" role="group">
					<button id="btnGroupDrop1" type="button" class="btn btn-outline-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
						<i class="fa fa-upload"></i> Upload Sale
					</button>
					<ul class="dropdown-menu" aria-labelledby="btnGroupDrop1" style="cursor:pointer">									
						<li id="saleButton" onclick="window.location.href = 'list.php'"><a class="dropdown-item">Sales List</a></li>
					</ul>
				</div>	
			</div>		
			<span class="navbar-brand" style="font-size:25px;margin-right:45%;"><i class="fa fa-upload"></i> Upload Sale</span>
		</nav>	
	  <br/><br/>	
	  <div class="col-5 offset-3">
		<div class="card">
		  <div class="card-header" style="background-color:#43B5B5;color:#FFFFFF;font-weight:bold">
			Guidelines for CSV file
		  </div>
		  <div class="card-body">
			  <ul>
				<li>Upload CSV with columns - Date, AR Code, Product Id, Sale Quantity</li>
				<li>Keep the first row for header. Data should start from row 2</li>
			  </ul>
			  
			  Product Id list is given below
			  <ul>
				<li>1 - Suraksha</li>
				<li>6 - CONCRT+</li>
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
				<div class="offset-5"><button type="submit" name="submit">Upload</button></div>
			</form>
		</div>
	</body>
</html>