<?php
session_start();
if(isset($_SESSION["user_name"]))
{
	require '../connect.php';
	require '../navbar.php';
	
	if(isset($_GET['fromDate']) && isset($_GET['toDate']))
	{
		$fromDate = $_GET['fromDate'];
		$toDate = $_GET['toDate'];				

		$arObjects = mysqli_query($con, "SELECT id,name FROM ar_details WHERE isActive = 1 AND type LIKE '%AR%' ORDER BY name asc") or die(mysqli_error($con)) or die(mysqli_error($con));		 						
		
		if(count($_POST) > 0)
		{
			foreach($_POST as $arId => $special_target)
			{
				if(is_numeric($arId))
				{
					$insertQuery = "INSERT INTO special_target (ar_id,fromDate,toDate,special_target) VALUES ('$arId','$fromDate','$toDate','$special_target')";
					$insert = mysqli_query($con, $insertQuery) or die(mysqli_error($con));		 											
				}

			}
			header("Location:../index.php");
		}	
	}
?>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Insert Special Target</title>
	<link href="../css/styles.css" rel="stylesheet" type="text/css">	
	<link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" rel="stylesheet" type="text/css">
	<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
	<style>
	@import url("https://fonts.googleapis.com/css?family=Open+Sans");
	.sidebar {
	  font-family: Arial;
	  font-size: 16px;
	  background: #5e42a6;	
	  position: fixed;
	  width: 18%;
	  height: 100vh;
	  background: #312450;
	  font-size: 0.65em;
	}

	.nav {
	  position: relative;
	  margin: 0 15%;
	  text-align: right;
	  top: 40%;
	  -webkit-transform: translateY(-50%);
			  transform: translateY(-50%);
	  font-weight: bold;
	}

	.nav ul {
	  list-style: none;
	}
	.nav ul li {
	  position: relative;
	  margin: 3.2em 0;
	}
	.nav ul li a {
	  line-height: 5em;
	  text-transform: uppercase;
	  text-decoration: none;
	  letter-spacing: 0.4em;
	  color: rgba(255, 255, 255, 0.35);
	  display: block;
	  -webkit-transition: all ease-out 300ms;
	  transition: all ease-out 300ms;
	}
	.nav ul li.active a {
	  color: white;
	}
	.nav ul li:not(.active)::after {
	  opacity: 0.2;
	}
	.nav ul li:not(.active):hover a {
	  color: rgba(255, 255, 255, 0.75);
	}
	.nav ul li::after {
	  content: "";
	  position: absolute;
	  width: 100%;
	  height: 0.2em;
	  background: black;
	  left: 0;
	  bottom: 0;
	  background-image: -webkit-gradient(linear, left top, right top, from(#5e42a6), to(#b74e91));
	  background-image: linear-gradient(to right, #5e42a6, #b74e91);
	}	
	</style>		
</head>
<body>
	<div id="main" class="main">
		<aside class="sidebar">
			<nav class="nav">
				<ul>
					<li><a href="../ar/list.php">List</a></li>
					<li><a href="../Target/monthlyPoints.php">Monthly Points</a></li>
					<li><a href="#">Total Points</a></li>
					<li class="active"><a href="#">Special Target</a></li>
				</ul>
			</nav>
		</aside>	
		<br/><br/>
		<div class="container">		
			<nav class="navbar navbar-light bg-light sticky-top bottom-nav" style="margin-left:12%;width:100%">
				<span class="navbar-brand" style="font-size:25px;margin-left:40%;"><i class="fa fa-chart-pie"></i><?php echo date('d-M-Y',strtotime($fromDate)).'&nbsp&nbsp&nbsp&nbspTO&nbsp&nbsp&nbsp&nbsp'.date('d-M-Y',strtotime($toDate));?></span>
			</nav>			
			<br/><br/>		
			<form method="post" action="">
				<table align="center" class="responstable" style="width:30%;">
					<tr><th style="width:25%">AR NAME</th><th style="width:25%;text-align:center;">SPECIAL TARGET</th></tr>					<?php
					foreach($arObjects as $ar) 
					{									?>				
						<tr>
							<td><label align="center"><?php echo $ar['name']; ?></td>	
							<td style="text-align:center;"><input type="text" style="text-align:center;width:70px;border:0px;background-color: transparent;" name="<?php echo $ar['id'];?>" value="0"></td>	
						</tr>																												<?php
					}																								?>
					<input type="hidden" name="fromDate" value="<?php echo $fromDate;?>">
					<input type="hidden" name="toDate" value="<?php echo $toDate;?>">
				</table>
				<br><br>
				<div align="center"><input type="submit" name="submit" value="Submit"></div>
			</form>
		</div>
		<br><br>
	</div>	
</body>
</html>
<?php
}
else
	header("../index.php");

?>