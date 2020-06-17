<?php
class Sale
{ 
	private $conn;
	private $table_name = "nas_sale";

	public $sales_id;
	public $entry_date;
	public $product;
	public $qty;
	public $ar;	
	public $shop;	
	public $eng_id;
	public $return_bag;
	public $discount;
	public $remarks;
	public $customer_name;
	public $customer_phone;
	public $address1;
	public $address2;

	// constructor with $db as database connection
	public function __construct($db)
	{
		$this->conn = $db;
	}
	
	function getDailySales($today)
	{
		$query = "SELECT * FROM ".$this->table_name." WHERE entry_date = '$today'";
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
	 
		return $stmt;
	}	
}