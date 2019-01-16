<?php

require '../connect.php';

mysqli_query($con,"INSERT INTO sms_report (sent_to, message, status) VALUES ('NA', 'NA', 'TEST CRON')") or die(mysqli_error($con));		 		

$date = new DateTime('tomorrow');
$date = $date ->format('Y-m-d');

$sheets = mysqli_query($con,"SELECT * FROM sheets WHERE date='$date' AND status = 'requested' ") or die(mysqli_error($con));
$message = null;
foreach($sheets as $sheet)
{
	$message = $message.$sheet['name']." : ".$sheet['phone']."&#xA;".$sheet['area'].", ".$sheet['bags']." bags&#xA;&#xA;";
}	

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
{
	$status = "cURL Error #:" . $err;	 
	mysqli_query($con,"INSERT INTO sms_report (sent_to, message, status) VALUES ('NA', 'NA', '$status')") or die(mysqli_error($con));		 		
}
else
	echo $response;