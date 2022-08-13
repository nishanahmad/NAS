<?php
class Shop
{ 
	private $conn;
	private $table_name = "ar_details";

	public $id;
	public $name;

	// constructor with $db as database connection
	public function __construct($db)
	{
		$this->conn = $db;
	}
	
	function read()
	{
		$query = "SELECT * FROM ".$this->table_name." WHERE shop_name != '' AND shop_name IS NOT NULL ORDER BY shop_name";
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
	 
		return $stmt;
	}	
}