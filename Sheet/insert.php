<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
date_default_timezone_set("Asia/Kolkata");		

require '../connect.php';
session_start();
if(isset($_SESSION["user_name"]))
{
	$sqlDate = date("Y-m-d",strtotime($_POST['date']));
	$name = $_POST['name'];
	$phone = $_POST['phone'];
	$area = $_POST['area'];
	$bags = (int)$_POST['bags'];
	$requested_by = $_SESSION['user_name'];

	$sql="INSERT INTO sheets (date, name, phone, bags, area, requested_by, status)
		 VALUES
		 ('$sqlDate', '$name', '$phone', $bags, '$area', '$requested_by', 'requested')";

	$result = mysqli_query($con, $sql) or die(mysqli_error($con));				 

	$tomorrow = new DateTime('tomorrow');
	$tomorrow = $tomorrow ->format('Y-m-d');

	if($sqlDate == $tomorrow && time() > strtotime("12:00 PM"))
	{
		$message = $name." : ".$phone."&#xA;".$area.", ".$bags." bags&#xA;&#xA;";
		
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://control.msg91.com/api/postsms.php",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_POSTFIELDS => "<MESSAGE> <AUTHKEY>212006AOQzrpS4jW5adee1be</AUTHKEY> <SENDER>ACCHLP</SENDER> <ROUTE>Template</ROUTE> <CAMPAIGN>SHEET</CAMPAIGN> <COUNTRY>91</COUNTRY> <SMS TEXT=\"".$message."\"> <ADDRESS TO=\"9744771145\"></ADDRESS> <ADDRESS TO=\"9567353448\"></ADDRESS> </SMS></MESSAGE>",
		  CURLOPT_SSL_VERIFYHOST => 0,
		  CURLOPT_SSL_VERIFYPEER => 0,
		  CURLOPT_HTTPHEADER => array(
			"content-type: application/xml"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) 
			echo "cURL Error #:" . $err;
		else
			echo $response;		
	}
	
	header( "Location: new.php" );

}
else
	header( "Location: ../index.php" );
?> 