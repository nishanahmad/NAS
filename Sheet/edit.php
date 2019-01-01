<?php 
require '../connect.php';
$id = $_GET['id'];
$sql = mysqli_query($con,"SELECT * FROM sheets WHERE id='$id'") or die(mysqli_error($con));
$request = mysqli_fetch_array($sql,MYSQLI_ASSOC);
?>
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
#name{
	background-image: url(../images/name.png);
	background-size: 30px 30px;
	background-position: 11px 8px;
	background-repeat: no-repeat;
}
#name:focus{
	background-image: url(../images/name.png);
	background-size: 30px 30px;
	background-position: 8px 5px;
  background-position: 11px 8px;
	background-repeat: no-repeat;
}
#phone{
	background-image: url(../images/phone.png);
	background-size: 30px 30px;
	background-position: 11px 8px;
	background-repeat: no-repeat;
}
#phone:focus{
	background-image: url(../images/phone.png);
	background-size: 30px 30px;
  background-position: 11px 8px;
	background-repeat: no-repeat;
}
#qty{
	background-image: url(../images/sheet.png);
	background-size: 30px 30px;
	background-position: 11px 8px;
	background-repeat: no-repeat;
}
#qty:focus{
	background-image: url(../images/sheet.png);
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
textarea {
    width: 100%;
    height: 150px;
    line-height: 150%;
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
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
</head>
	<div id="form-main">
	<div id="form-div">
	<form class="form" id="form1" method="post" action="update.php">
	<input name="id" type="hidden" value="<?php echo $request['id'];?>"/>
	<p class="name">
	<input name="name" type="text" class="validate[required,length[0,100]] feedback-input" id="name" value="<?php echo $request['masonName'];?>"/>
	</p>

	<p class="phone">
	<input name="phone" type="text" class="validate[required] feedback-input" id="phone" value="<?php echo $request['masonPhone'];?>"/>
	</p>
	
	<p class="qty">
	<input name="qty" type="text" class="validate[required] feedback-input" id="qty"  value="<?php echo $request['qty'];?>"/>
	</p>	

	<p class="area">
	<textarea name="area" class="validate[required,length[6,200]] feedback-input" id="area"><?php echo $request['area'];?></textarea>
	</p>
	

	<div class="submit">
	<input type="submit" value="UPDATE" id="button-blue"/>
	<div class="ease"></div>
	</div>
	</form>
	</div>
</html> 
