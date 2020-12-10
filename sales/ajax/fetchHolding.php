<?php
header('Content-Type: application/json');

require '../../connect.php';

session_start();

$ar = $_POST['ar'];

$search = mysqli_query($con,"SELECT * FROM holdings WHERE returned_sale = $returnId");

if(mysqli_num_rows($search) > 0 )
	$upsert = "UPDATE holdings SET qty = $qty, returned_by = $userId WHERE returned_sale = $returnId";
else
	$upsert = "INSERT INTO holdings (returned_sale, qty, returned_by) VALUES ($returnId,$qty,$userId)";


$result = mysqli_query($con,$upsert);

if($result)
	$response_array['status'] = 'success';
else
{
    $response_array['status'] = 'error';
	$response_array['value'] = mysqli_error($con);
}	
	
echo json_encode($response_array);

exit;