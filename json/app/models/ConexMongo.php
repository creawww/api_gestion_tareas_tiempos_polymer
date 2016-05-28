<?php 
require_once ROUTEAPP."config.php";

class ConexMongo
{
	protected $_db;

	public function __construct(){
		try {

	        // Connect to MongoDB
	        $conn = new MongoClient('localhost');
	        // connect to test database
	        $this->_db = $conn->test;

	    	return($this->_db);

		}
		catch ( MongoConnectionException $e )
		{
		        // if there was an error, we catch and display the problem here
		        echo $e->getMessage();
		        exit();
		}
		catch ( MongoException $e )
		{
		        echo $e->getMessage();
		        exit();
		}
	}

}
?> 