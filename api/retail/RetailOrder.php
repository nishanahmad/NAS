<?php
class RetailOrder
{ 
	private $conn;
	private $table_name = "nas_sale";

	public $entry_date;
	public $product;
	public $qty;
	public $ar_id;
	public $customer_name;
	public $customer_phone;
	public $ar_direct;
	public $address1;
	public $entered_by;

	// constructor with $db as database connection
	public function __construct($db)
	{
		$this->conn = $db;
	}
	
	// create product
	function create()
	{ 
		// query to insert record
		$query = "INSERT INTO
					" . $this->table_name . "
				SET
					entry_date=:entry_date, product=:product, qty=:qty, ar_id=:ar_id, customer_name=:customer_name, customer_phone=:customer_phone, ar_direct=1, address1=:address1, entered_by=:entered_by, entered_on=:entered_on";
	 
		// prepare query
		$stmt = $this->conn->prepare($query);
	 
		// sanitize
		$this->entry_date=htmlspecialchars(strip_tags($this->entry_date));
		$this->product=htmlspecialchars(strip_tags($this->product));
		$this->qty=htmlspecialchars(strip_tags($this->qty));
		$this->ar_id=htmlspecialchars(strip_tags($this->ar_id));
		$this->customer_name=htmlspecialchars(strip_tags($this->customer_name));		
		$this->customer_phone=htmlspecialchars(strip_tags($this->customer_phone));
		$this->address1=htmlspecialchars(strip_tags($this->address1));
		$this->entered_by=htmlspecialchars(strip_tags($this->entered_by));
		$this->entered_on=htmlspecialchars(strip_tags($this->entered_on));
	 
		// bind values
		$stmt->bindParam(":entry_date", $this->entry_date);
		$stmt->bindParam(":product", $this->product);
		$stmt->bindParam(":qty", $this->qty);
		$stmt->bindParam(":ar_id", $this->ar_id);
		$stmt->bindParam(":customer_name", $this->customer_name);		
		$stmt->bindParam(":customer_phone", $this->customer_phone);
		$stmt->bindParam(":address1", $this->address1);
		$stmt->bindParam(":entered_by", $this->entered_by);
		$stmt->bindParam(":entered_on", $this->entered_on);
	 
		// execute query
		if($stmt->execute())
			return true;
	 
		else
			return false;
	}


	function read($fe)
	{
		$query = "SELECT * FROM ".$this->table_name." WHERE status = 'requested' AND requested_by = '$fe' ORDER BY date";
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
	 
		return $stmt;
	}	
}