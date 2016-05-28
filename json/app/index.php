<?php
$url=$_SERVER['REQUEST_URI'];
$url_par=parse_url($url);


$folder=isset($url_par['path']) ? explode("/",$url_par['path']): null;

if(isset($folder)){
    $ruta="/".$folder[1]."/".$folder[2];
}
$paramet=isset($url_par['query']) ? explode("&",$url_par['query']): null;

define('ROUTEAPP', dirname(__FILE__)."/");

//require_once __DIR__ ."/core/functionUtil.php";
require_once __DIR__ ."/config.php";
if(APPSTATUS=='developer'){
error_reporting(E_ALL);
ini_set('display_errors', '1');
}
$ruta = isset($ruta)? $ruta : PAGE_PUBLIC_DEFAULT;

include_once  __DIR__ .'/routes.php';

$method = $_SERVER['REQUEST_METHOD'];
//echo "method".$method;
// Parseo de la ruta
if (isset($routes[$ruta][$method])) {
        $controlador =  $routes[$ruta][$method];

        include_once  __DIR__ .'/controllers/'.$controlador['controller'].'.php';  
 
        //echo "method:".$method."-->".$controlador['controller'].",".$controlador['action'];
    // Ejecución del controlador asociado a la ruta
        if (method_exists($controlador['controller'],$controlador['action'])) {
            call_user_func(array(new $controlador['controller'], $controlador['action']),$folder,$paramet);
        
        } else {
            header('Status: 404 Not Found');
            echo '<html><body><h1>Error 404: El controlador <i>' .
            $controlador['controller'].'->'.$controlador['action'] .
            '</i> no existe</h1></body></html>';
        }
} else {
    header('Status: 404 Not Found');
    echo '<html><body><h1>Error 404: No existe la ruta <i>'.$ruta.'</p></body></html>';
    exit;
}

?>