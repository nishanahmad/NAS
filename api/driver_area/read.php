<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
include_once '../database.php';
include_once 'area.php';
 
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$area = new Area($db);

$stmt = $area->read();
$num = $stmt->rowCount();
 
if($num>0)
{ 
    $area_arr=array();
    $area_arr=array();
 
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
	{
        extract($row);
 
        $area_item=array(
            "id" => $id,
			"name" => $name,
        );
 
        array_push($area_arr, $area_item);
    }
 
    http_response_code(200); 
    echo json_encode($area_arr);
}
else
{
    http_response_code(404);
    echo json_encode(array("message" => "No area found."));
}