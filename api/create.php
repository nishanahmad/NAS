<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
include_once 'database.php';
include_once 'sheet.php';
 
$database = new Database();
$db = $database->getConnection();
 
$sheet = new Sheet($db);
 
$data = json_decode(file_get_contents("php://input"));
//echo json_encode($data);

if(!empty($data->concDate) && !empty($data->bags) && !empty($data->area) && !empty($data->requested_by) && !empty($data->driver_area))
{
    $sheet->date = date('Y-m-d',strtotime($data->concDate));
	$sheet->customer_name = $data->customer_name;
    $sheet->customer_phone = $data->customer_phone;
	$sheet->mason_name = $data->mason_name;
    $sheet->mason_phone = $data->mason_phone;	
    $sheet->bags = $data->bags;
    $sheet->area = $data->area;
    $sheet->shop1 = $data->shop1;
	$sheet->coveringBlock = $data->coveringBlock;
	$sheet->remarks = $data->remarks;
	$sheet->requested_by = $data->requested_by;
	$sheet->created_on = date('Y-m-d H:i:s');
	$sheet->driver_area = $data->driver_area;
	
 
    if($sheet->create() == "Success")
	{
        http_response_code(201);
        echo json_encode(array("message" => "Sheet request successfully created."));
    }
    else
	{
        http_response_code(503);
        echo json_encode(array("message" => $sheet->create()));
    }	
}
else
{ 
    http_response_code(400);
    echo json_encode(array("message" => "Unable to create sheet. Data is incomplete."));
}

?>