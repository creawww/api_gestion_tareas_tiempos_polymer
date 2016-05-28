<?php 
require_once ROUTEAPP."config.php";

class Conexion
{
	protected $_db;

	public function __construct(){
		try {
		    $this->_db = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=".DB_CHARSET, DB_USER, DB_PASS);
		    	return($this->_db);
			} catch (PDOException $e) {
				echo"<h1>Error ".TYPECONEXION.": No puede conectarse con la base de datos. Revisa la configuración y que BASE este creada</h1>";
				exit();
			}
	}
}
?> 