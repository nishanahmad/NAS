<?php
header('Content-Type: application/json');

require '../../connect.php';

$id = $_POST['id'];

$update = "UPDATE loading SET qty = 0 , status = 'cleared' WHERE id = $id";

$result = mysqli_query($con,$update);

if($result)
	$response_array['status'] = 'success';
else
{
    $response_array['status'] = 'error';
	$response_array['value'] = mysqli_error($con);
}	
	
echo json_encode($response_array);

exit;