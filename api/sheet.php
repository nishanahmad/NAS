<?php
class Sheet
{ 
	private $conn;
	private $table_name = "sheets";

	public $date;
	public $customer_name;
	public $customer_phone;
	public $mason_name;
	public $mason_phone;	
	public $bags;
	public $area;
	public $shop;
	public $coveringBlock;
	public $remarks;
	public $requested_by;
	public $created_on;
	public $driver_area;

	// constructor with $db as database connection
	public function __construct($db)
	{
		$this->conn = $db;
	}
	
	
	// create product
	function create()
	{ 
		// Populate assigne driver value
		$stmt = $this->conn ->prepare("SELECT * FROM sheet_area WHERE id=?");
		$stmt->execute([$this -> driver_area]); 
		$assigned_to = $stmt->fetch()[2];

		
		// query to insert record
		
		$query = "INSERT INTO
					" . $this->table_name . "
				SET
					date=:date, customer_name=:customer_name, customer_phone=:customer_phone, mason_name=:mason_name, mason_phone=:mason_phone, bags=:bags, area=:area, shop1=:shop1, coveringBlock=:coveringBlock, remarks=:remarks, requested_by=:requested_by, status='requested', created_on=:created_on, driver_area=:driver_area, assigned_to=:assigned_to";
	 
		// prepare query
		$stmt = $this->conn->prepare($query);
	 
		// sanitize
		$this->date=htmlspecialchars(strip_tags($this->date));
		$this->customer_name=htmlspecialchars(strip_tags($this->customer_name));
		$this->customer_phone=htmlspecialchars(strip_tags($this->customer_phone));
		$this->mason_name=htmlspecialchars(strip_tags($this->mason_name));
		$this->mason_phone=htmlspecialchars(strip_tags($this->mason_phone));		
		$this->bags=htmlspecialchars(strip_tags($this->bags));
		$this->area=htmlspecialchars(strip_tags($this->area));
		$this->shop1=htmlspecialchars(strip_tags($this->shop1));
		$this->coveringBlock=htmlspecialchars(strip_tags($this->coveringBlock));
		$this->remarks=htmlspecialchars(strip_tags($this->remarks));
		$this->requested_by=htmlspecialchars(strip_tags($this->requested_by));
		$this->created_on=htmlspecialchars(strip_tags($this->created_on));
		$this->driver_area=htmlspecialchars(strip_tags($this->driver_area));
		$this->assigned_to=htmlspecialchars(strip_tags($assigned_to));
	 
		// bind values
		$stmt->bindParam(":date", $this->date);
		$stmt->bindParam(":customer_name", $this->customer_name);
		$stmt->bindParam(":customer_phone", $this->customer_phone);
		$stmt->bindParam(":mason_name", $this->mason_name);
		$stmt->bindParam(":mason_phone", $this->mason_phone);		
		$stmt->bindParam(":bags", $this->bags);
		$stmt->bindParam(":area", $this->area);
		$stmt->bindParam(":shop1", $this->shop1);
		$stmt->bindParam(":coveringBlock", $this->coveringBlock);
		$stmt->bindParam(":remarks", $this->remarks);
		$stmt->bindParam(":requested_by", $this->requested_by);
		$stmt->bindParam(":created_on", $this->created_on);
		$stmt->bindParam(":driver_area", $this->driver_area);
		$stmt->bindParam(":assigned_to", $assigned_to);
	 
		// execute query
		if($stmt->execute())
			return "Success";
	 
		else
			return $stmt->errorInfo();
	}


	function read($fe)
	{
		$query = "SELECT * FROM ".$this->table_name." WHERE status = 'requested' AND requested_by = '$fe' ORDER BY date";
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
	 
		return $stmt;
	}
}