<?php
define('APP_URL', 'http://proyecto3.local');

define('PAGE_PUBLIC_DEFAULT', APP_URL.'/inicio.php');

define('APPSTATUS', 'developer');
//define('APPSTATUS', '');

define('TITLEDEFAUL', 'Libros Solidarios | Llibres Solidaris');

//DATABASE MYSQL
if(APPSTATUS=='developer'){
	define('TYPECONEXION', 'LOCAL');
	define('DB_HOST', 'localhost');
	define('DB_NAME', 'rest_gestion');	
	define('DB_USER', 'root');
	define('DB_PASS', 'Hector');
	define('DB_CHARSET', 'utf8');
}else{
	define('TYPECONEXION', 'SERVER WEB');
	define('DB_NAME', 'c4tareas');
	define('DB_HOST', 'localhost');
	define('DB_USER', 'c4app');
	define('DB_PASS', 'scqndMPAZ@2');
	define('DB_CHARSET', 'utf8');
}


define('PASSDEFATUL', 'Libros16');

?> 
