<?php
session_start();
if(isset($_SESSION["user_name"]))
{																																										?>
<html>
	<head>
	<style>
	@import url(https://fonts.googleapis.com/css?family=Montserrat:400,700);

	html{
	  background-size: cover;
	  height:100%;
	}

	#feedback-page{
		text-align:center;
	}

	#form-main{
		width:100%;
		float:left;
		padding-top:0px;
	}

	#form-div {
		background-color:rgba(72,72,72,0.4);
		padding-left:35px;
		padding-right:35px;
		padding-top:35px;
		padding-bottom:50px;
		width: 450px;
		float: left;
		left: 50%;
		position: absolute;
	  margin-top:30px;
		margin-left: -260px;
	  -moz-border-radius: 7px;
	  -webkit-border-radius: 7px;
	}

	.feedback-input {
		color:#3c3c3c;
		font-family: Helvetica, Arial, sans-serif;
	  font-weight:500;
		font-size: 18px;
		border-radius: 0;
		line-height: 22px;
		background-color: #fbfbfb;
		padding: 13px 13px 13px 54px;
		margin-bottom: 10px;
		width:100%;
		-webkit-box-sizing: border-box;
		-moz-box-sizing: border-box;
		-ms-box-sizing: border-box;
		box-sizing: border-box;
	  border: 3px solid rgba(0,0,0,0);
	}

	.feedback-input:focus{
		background: #fff;
		box-shadow: 0;
		border: 3px solid #3498db;
		color: #3498db;
		outline: none;
	  padding: 13px 13px 13px 54px;
	}

	.focused{
		color:#30aed6;
		border:#30aed6 solid 3px;
	}

	/* Icons ---------------------------------- */
	#datepicker{
		background-image: url(../images/calender.png);
		background-size: 30px 30px;
		background-position: 11px 8px;
		background-repeat: no-repeat;
	}
	#name{
		background-image: url(../images/name.png);
		background-size: 30px 30px;
		background-position: 11px 8px;
		background-repeat: no-repeat;
	}
	#phone{
		background-image: url(../images/phone.png);
		background-size: 30px 30px;
		background-position: 11px 8px;
		background-repeat: no-repeat;
	}
	#bags{
		background-image: url(../images/sheet.png);
		background-size: 30px 30px;
		background-position: 11px 8px;
		background-repeat: no-repeat;
	}
	#driver{
		background-image: url(../images/truck.png);
		background-size: 30px 30px;
		background-position: 11px 8px;
		background-repeat: no-repeat;
	}
	#shop{
		background-image: url(../images/shop.png);
		background-size: 30px 30px;
		background-position: 11px 8px;
		background-repeat: no-repeat;
	}	
	#area{
		background-image: url(../images/area.png);
		background-size: 30px 30px;
		background-position: 11px 8px;
		background-repeat: no-repeat;
	}
	#remarks{
		background-image: url(../images/remarks.png);
		background-size: 30px 30px;
		background-position: 11px 8px;
		background-repeat: no-repeat;
	}	
	textarea {
		width: 100%;
		height: 100px;
		line-height: 100%;
		resize:vertical;
	}

	input:hover, textarea:hover,
	input:focus, textarea:focus {
		background-color:white;
	}

	#button-blue{
		font-family: 'Montserrat', Arial, Helvetica, sans-serif;
		float:left;
		width: 100%;
		border: #fbfbfb solid 4px;
		cursor:pointer;
		background-color: #3498db;
		color:white;
		font-size:24px;
		padding-top:22px;
		padding-bottom:22px;
		-webkit-transition: all 0.3s;
		-moz-transition: all 0.3s;
		transition: all 0.3s;
	  margin-top:-4px;
	  font-weight:700;
	}

	#button-blue:hover{
		background-color: rgba(0,0,0,0);
		color: #0493bd;
	}
		
	.submit:hover {
		color: #3498db;
	}
		
	.ease {
		width: 0px;
		height: 74px;
		background-color: #fbfbfb;
		-webkit-transition: .3s ease;
		-moz-transition: .3s ease;
		-o-transition: .3s ease;
		-ms-transition: .3s ease;
		transition: .3s ease;
	}

	.submit:hover .ease{
	  width:100%;
	  background-color:white;
	}

	@media only screen and (max-width: 580px) {
		#form-div{
			left: 3%;
			margin-right: 3%;
			width: 88%;
			margin-left: 0;
			padding-left: 3%;
			padding-right: 3%;
		}
	}
	</style>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="../css/navigation-dark.css">
	<link rel="stylesheet" href="../css/slicknav.min.css">
	<script src="https://kit.fontawesome.com/742221945b.js" crossorigin="anonymous"></script>
	<link rel="stylesheet" type="text/css" href="../css/jquery-ui.css">
	<script src="../js/jquery.js"></script> 
	<script type="text/javascript" language="javascript" src="../js/jquery-ui.min.js"></script>	
	<script src="../js/jquery.slicknav.min.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>			
	<script>
		$(function() {
		var pickerOpts = { dateFormat:"dd-mm-yy"}; 
					
		$( "#datepicker" ).datepicker(pickerOpts);
		});
	</script>	
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<title>Sheet Request New</title>
	</head>
	<body>
		<nav class="menu-navigation-dark">																		<?php 
			if($_SESSION['role'] != 'driver')
			{																									?>	
				<a href="../index.php"><i class="fa fa-home"></i><span>Home</span></a>
				<a href="new.php" class="selected"><i class="fa fa-plus"></i><span>New</span></a>
				<a href="plan.php"><i class="fa fa-list-alt"></i><span>Driver Assign</span></a>		<?php
			}																									?>	
			<a href="requests.php"><i class="fa fa-spinner"></i><span>Pending ...</span></a>
			<a href="deliveries.php"><i class="fa fa-truck"></i><span>Delivered</span></a>
			<a href="transfer.php"><i class="fa fa-exchange"></i><span>Transfer</span></a>					<?php
			if($_SESSION['role'] != 'driver')
			{																									?>				
				<a href="transfer_logs.php"><i class="fa fa-file-text"></i><span>Transfer Logs</span></a>
				<a href="closed.php"><i class="fa fa-check-square"></i><span>Closed</span></a><?php
			}?>																		
		</nav>		
		<br/>
		<div id="form-main">
			<div id="form-div">
			<form class="form" id="form1" method="post" action="insert.php" autocomplete="off">

			<p class="date">
			<input name="date" required type="text" class="feedback-input" placeholder="Date" id="datepicker" />
			</p>
			
			<p class="name">
			<input name="customer_name" type="text" class="validate[length[0,100]] feedback-input" placeholder="Customer Name" id="name" />
			</p>

			<p class="phone">
			<input name="customer_phone" type="text" class="feedback-input" id="phone" placeholder="Customer Phone" />
			</p>
			
			<p class="name">
			<input name="mason_name" type="text" class="validate[length[0,100]] feedback-input" placeholder="Mason Name" id="name" />
			</p>

			<p class="phone">
			<input name="mason_phone" type="text" class="feedback-input" id="phone" placeholder="Mason Phone" />
			</p>			
			
			<p class="bags">
			<input name="bags" type="text" class="validate[required] feedback-input" id="bags" placeholder="Number of Bags" />
			</p>	

			<p class="shop">
			<input name="shop" type="text" class="feedback-input" placeholder="Shop" id="shop"/>
			</p>
			
			<p class="area">
			<textarea required name="area" class="validate[required,length[6,200]] feedback-input" id="area" placeholder="Area & Location"></textarea>
			</p>
			
			<p class="remarks">
			<textarea name="remarks" class="feedback-input" id="remarks" placeholder="Remarks"></textarea>
			</p>			

			<div class="submit">
			<input type="submit" value="REQUEST" id="button-blue"/>
			<div class="ease"></div>
			</div>
			</form>
			</div>
		</div>
		<script>

			$(function(){

				var menu = $('.menu-navigation-dark');

				menu.slicknav();

				// Mark the clicked item as selected

				menu.on('click', 'a', function(){
					var a = $(this);

					a.siblings().removeClass('selected');
					a.addClass('selected');
				});
			});

		</script>				
	</body>	
</html>
																										<?php
}	
else
	header("Location:../index.php");