<?php
header('Content-Type: application/json');

require '../connect.php';

$date = date('Y-m-d',strtotime($_POST['date']));
$time = date('H:i',strtotime($_POST['time']));
$truck = $_POST['truck'];
$product = $_POST['product'];
$qty = $_POST['qty'];

$insertQuery = "INSERT INTO loading (date, time, truck, product, qty)
				VALUES
				('$date', '$time', $truck, $product, $qty)";

$insert = mysqli_query($con,$insertQuery);

if($insert)
	$response_array['status'] = 'success';
else
{
    $response_array['status'] = 'error';
	$response_array['value'] = mysqli_error($con);
}	
	
echo json_encode($response_array);

exit;