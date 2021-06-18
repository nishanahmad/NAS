<?php
class Area
{ 
	private $conn;
	private $table_name = "sheet_area";

	public $id;
	public $name;

	// constructor with $db as database connection
	public function __construct($db)
	{
		$this->conn = $db;
	}
	
	function read()
	{
		$query = "SELECT * FROM ".$this->table_name." ORDER BY name";
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
	 
		return $stmt;
	}	
}