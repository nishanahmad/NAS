<?php
class Sale
{ 
	private $conn;
	private $table_name = "nas_sale";

	// constructor with $db as database connection
	public function __construct($db)
	{
		$this->conn = $db;
	}
	
	function getDailySales($day)
	{
		$query = "SELECT * FROM ".$this->table_name." WHERE entry_date = '$day'";
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
	 
		return $stmt;
	}	
	
	function getDailySaleSum($day)
	{
		$query = "SELECT SUM(qty),product FROM ".$this->table_name." WHERE entry_date = '$day' GROUP BY product";
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
	 
		return $stmt;
	}

	function set_values($sale) 
	{
		$this->entry_date = $sale['entry_date'];
		$this->qty = $sale['qty'] - $sale['return_bag'];
		$this->client = $sale['ar_id'];
		$this->bd = $sale['discount'];
	}

	function entry_date() 
	{
		return $this->entry_date;
	}

	function qty() 
	{
		return $this->qty;
	}

	function client() 
	{
		return $this->client;
	}    

	function bd() 
	{
		return $this->bd;
	}      	
}