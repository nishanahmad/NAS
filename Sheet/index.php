<?php
session_start();
if(isset($_SESSION["user_name"]))
{
	$year = date("Y");
	$month = date("m") - 1;
	echo $year;
	echo $month;

?>

<html>
<head>
<style type="text/css">
a{
  text-decoration:none;
}
</style>
<title>SHEETS</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" type="text/css" href="../css/index.css" />
</head>
<body>


<div class="background">
</div>
<div class="container">
	<div class="row">
		<a href="../index.php"><img alt='Add' title='Add New' src='../images/homeSilver.png' width='80px' height='80px'/></a>
	</div>
	<hr />
</div>

<div class="row">
	<h1>SHEETS</h1>
	<br><br>
	
	<a href="new.php" class="btn lg ghost">NEW REQUEST</a>
	<br><br><br>
	
	<a href="requests.php" class="btn lg ghost">PENDING REQUESTS</a>
	<br><br><br>
	
	<a href="deliveries.php" class="btn lg ghost">DELIVERED SHEETS</a>
	<br><br><br>																						<?php 

	if($_SESSION['role'] != 'driver')
	{																									?>
		<a href="plan.php" class="btn lg ghost">DRIVER ASSIGN</a>										<?php
	}																									?>

</div>

</div>
</body>
</html>
<?php
}
else
	header("Location:../index.php");
?>