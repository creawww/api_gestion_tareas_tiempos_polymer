<?php
define('ROUTEPUBLIC', dirname(__FILE__)."/");
if(file_exists('..app/index.php')){
	include_once ('..app/index.php');
}else{
	if(file_exists('app/index.php')){
		include_once('app/index.php');	
	}else{
		echo "index no encontrado";
	}
}
?>