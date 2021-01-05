<?php
header('Content-Type: application/json');

require '../connect.php';

$date = date('Y-m-d',strtotime($_POST['date']));
$time = date('H:i',strtotime($_POST['time']));
$truck = $_POST['truck'];
$product = $_POST['product'];
$qty = $_POST['qty'];

$searchQuery = "SELECT * FROM loading WHERE truck = $truck AND product = $product AND status = 'pending'";
$search = mysqli_query($con,$searchQuery);
if(mysqli_num_rows($search) > 0 )
{
	$loadId = mysqli_fetch_array($search, MYSQLI_ASSOC)['id'];
	$sql = "UPDATE loading SET qty = qty + $qty WHERE id = $loadId";
}
else
{
	$sql = "INSERT INTO loading (date, time, truck, product, qty) VALUES ('$date', '$time', $truck, $product, $qty)";
}
	
$upsert = mysqli_query($con,$sql);

if($upsert)
	$response_array['status'] = 'success';
else
{
	$response_array['status'] = 'error';
	$response_array['value'] = mysqli_error($con);
}		
	
echo json_encode($response_array);

exit;