<?php
header('Content-Type: application/json');

require '../../connect.php';

$number = $_POST['number'];
		
$insertQuery = "INSERT INTO vehicles (number)
				VALUES
				('$number')";

$insert = mysqli_query($con,$insertQuery);

if($insert)
{
	$response_array['status'] = 'success';
	$response_array['newid'] =  mysqli_insert_id($con);
	$response_array['newnumber'] = $number;	
}
else
{
    $response_array['status'] = 'error';
	$response_array['value'] = mysqli_error($con);
}	
	
echo json_encode($response_array);

exit;