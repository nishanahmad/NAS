<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
include_once '../database.php';
include_once '../../connect.php';
include_once 'sale.php';
include_once 'functions/nameMaps.php';
 
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$sale = new Sale($db);

$startDate = date("Y-m-d",strtotime($_GET['startDate']));
$endDate = date("Y-m-d",strtotime($_GET['endDate']));
$stmt = $sale->getEngineerSales($startDate,$endDate);
$num = $stmt->rowCount();
 
if($num>0)
{ 
	$productNameMap = getProductNamesMap($con);
    $sales_arr=array();
    $sales_arr["records"]=array();
 
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
	{
        $sale_item=array(
			"date" => $row['entry_date'],		
			"shop" => $row['ar_id'],		
			"product" => $row['product'],
			"qty" => $row['qty'],
			"customer_name" => $row['customer_name'],
			"customer_phone" => $row['customer_phone'],
			"remarks" => $row['remarks'],
        );
 
        array_push($sales_arr["records"], $sale_item);
    }
 
    http_response_code(200); 
    echo json_encode($sales_arr);
}
else
{
    http_response_code(404);
    echo json_encode(array("message" => "No Sales found."));
}