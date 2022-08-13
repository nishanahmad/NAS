<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
include_once '../database.php';
include_once 'shop.php';
 
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$shop = new Shop($db);

$stmt = $shop->read();
$num = $stmt->rowCount();
 
if($num>0)
{ 
    $shop_arr=array();
    $shop_arr=array();
 
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
	{
        extract($row);
 
        $shop_item=array(
            "id" => $id,
			"name" => $shop_name,
        );
 
        array_push($shop_arr, $shop_item);
    }
 
    http_response_code(200); 
    echo json_encode($shop_arr);
}
else
{
    http_response_code(404);
    echo json_encode(array("message" => "No shop found."));
}