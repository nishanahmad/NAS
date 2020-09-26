<?php
session_start();
if(isset($_SESSION["user_name"]))
{
	require '../connect.php';
	require '../navbar.php';
	
	if($_POST)
	{
		$fromDate = date("Y-m-d", strtotime($_POST["from"]));
		$toDate = date("Y-m-d", strtotime($_POST["to"]));
		
		$query = mysqli_query($con,"INSERT INTO special_target_date (from_date,to_date) VALUES ('$fromDate','$toDate') ") or die(mysqli_error($con));		 						
		
		header("Location:insertNewList.php?fromDate=$fromDate&toDate=$toDate");
	}
?>
<head>
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
		<div class="container">		
			<nav class="navbar navbar-light bg-light sticky-top bottom-nav" style="margin-left:12%;width:100%">
				<span class="navbar-brand" style="font-size:25px;margin-left:40%;"><i class="fa fa-chart-pie"></i> Insert Date Range</span>
			</nav>			
			<br/><br/>
			<div align="center">
			<form name="frm" method="post" action="" style="margin-left:20%">
				<input type="text" id="datepicker" name="from" required  class="form-control" style="width:150px;" placeholder="From Date"/>
				<br/>
				<input type="text" id="datepicker2" name="to" required  class="form-control" style="width:150px;" placeholder="To date"/>
				<br/>
				<input type="submit" name="submit" class="btn btn-success" value="Insert" onclick="return confirm('Do you want to insert new special target range?')">
			</form>
			</div>
		</div>
	</div>
</body>
<script>
$(function() {

var pickerOpts = { dateFormat:"d-mm-yy"}; 
	    	
$( "#datepicker" ).datepicker(pickerOpts);
$( "#datepicker2" ).datepicker(pickerOpts);


});
</script>																							<?php
}
else
	header("Location:../index.php");

