<?php
session_start();
if(isset($_SESSION["user_name"]) && $_SESSION["sheet_only"] != '1')
{																						?>
<html>
<style type="text/css">
a{
  text-decoration:none;
}
</style>
<head>
<title>HOME</title>
<link rel="stylesheet" type="text/css" href="css/index.css" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

<div class="background">
</div>
<div class="container">
  <div class="row">
    <h1><img alt='Add' title='Add New' src='images/logo.png' width='300px' height='50px'/></h1>
    <h4></h4>
  </div>
  <hr/>
</div>
   
<br><br> 

	<div class="row">
	
	<a href="sales/todayList.php?ar=all" class="btn lg ghost">TODAY SALES</a>
    <br><br><br>

	<a href="sales/list.php" class="btn lg ghost">ALL SALES</a>
    <br><br><br>
		
	<a href="reports/totalSalesAR.php" class="btn lg ghost">SALES SUMMARY</a>
    <br><br><br>
	
   	<a href="Target/" class="btn lg ghost">TARGET</a>
    <br><br><br>	
	
   	<a href="SpecialTarget/" class="btn lg ghost">SPECIAL TARGET</a>
    <br><br><br>		
	
   	<a href="indexParty.php" class="btn lg ghost">PARTY</a>
    <br><br><br>			
	
	<a href="discounts/" class="btn lg ghost">RATE & DISCOUNTS</a>
	<br><br><br>																																		
	
	<a href="Sheet/" class="btn lg ghost">SHEET DELIVERY</a>
    <br><br><br>		

	</div>
</body>
</html>
<?php
}
else if(isset($_SESSION["user_name"]) && $_SESSION["sheet_only"] == '1')
	header("Location:Sheet/index.php");
else
	header("Location:loginPage.php");
?>