<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
include_once '../database.php';
include_once 'RetailOrder.php';
 
$database = new Database();
$db = $database->getConnection();
 
$RetailOrder = new RetailOrder($db);

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->entry_date) && !empty($data->product) && !empty($data->qty) && !empty($data->ar_id) && !empty($data->entered_by))
{
    $RetailOrder->entry_date = date('Y-m-d',strtotime($data->entry_date));
	$RetailOrder->product = $data->product;
	$RetailOrder->qty = $data->qty;
	$RetailOrder->ar_id = $data->ar_id;
	$RetailOrder->customer_name = $data->customer_name;
    $RetailOrder->customer_phone = $data->customer_phone;
	$RetailOrder->customer_phone = $data->customer_phone;
	$RetailOrder->address1 = $data->address1;
	$RetailOrder->entered_by = $data->entered_by;
	$RetailOrder->entered_on = date('Y-m-d H:i:s');
 

    if($RetailOrder->create())
	{
        http_response_code(201);
        echo json_encode(array("message" => "Order request successfully sent."));
    }
    else
	{
        http_response_code(503);
        echo json_encode(array("message" => mysqli_error($database->conn->error)));
    }	
}
else
{ 
    http_response_code(400);
    echo json_encode(array("message" => "Unable to create order. Data is incomplete."));
}
?>