<?php
class Database
{
    // specify your own database credentials
    private $host = "139.59.30.47";
    private $db_name = "vqbrfnjbhf";
    private $username = "vqbrfnjbhf";
    private $password = "4CE4dusgYs";
    public $conn;
 
    // get the database connection
    public function getConnection()
	{
        $this->conn = null;
 
        try
		{
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        }
		catch(PDOException $exception)
		{
            echo "Connection error: " . $exception->getMessage();
        }
 
        return $this->conn;
    }
}
?>