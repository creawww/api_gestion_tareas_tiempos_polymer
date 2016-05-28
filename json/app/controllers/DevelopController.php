<?php
require_once ROUTEAPP."models/UsersModel.php";
require_once ROUTEAPP."models/DevelopModel.php";
require_once ROUTEAPP."controllers/CommonFunctions.php";
class DevelopController {


    public function __construct() {

        if (!isset($_SESSION)) {
            session_start();
        }
        


    }
//**** BOOKS *****************************	

    public function get($folder) {
  
        $table=isset($folder[3]) ? $folder[3] : null;

        $key=isset($folder[4]) ? $folder[4] : null;           
        $ob_db1 = new DevelopModel;

        if($table and $key){
            $data=$ob_db1->listElement($table,$key);
            echo json_encode($data);
        }
        if($table and !$key){
        $data=$ob_db1->listAllElement($table)->fetchAll(PDO::FETCH_ASSOC);
        $cad_json='[';
               foreach ($ob_db1->listAllElement($table)->fetchAll(PDO::FETCH_ASSOC) as $item){ 
                    $cad_json.= json_encode($item).",";
                }  
        $cad_json = substr($cad_json, 0, -1);

         $cad_json.= ']';
         echo $cad_json;
        }
    }
     public function post($folder) {



        $ob_db1 = new DevelopModel;
        $table=isset($folder[3]) ? $folder[3] : null;
        $campos="";
        $values=""; 
        $data=array();  

$data = json_decode(file_get_contents('php://input'), true);
print_r($data);

        foreach ($data as $key => $value) {
         $campos.=$key.","; 
         $values.="'".$value."',";  
        }
        $campos = substr($campos, 0, -1);
        $values = substr($values, 0, -1);
        $r=$ob_db1 ->modElement($table,$campos,$values);
        if($r>0){
        echo "{ 'id':".$r."}";            
        }else{
        echo "{ 'error':".$r."}";            
        }
   


    } 
    public function put($folder) {
  
        $table=isset($folder[3]) ? $folder[3] : null;
        
        echo "llega peticion PUT".$table;


    } 
     function preparapost($input,$link){
        $columns = preg_replace('/[^a-z0-9_]+/i','',array_keys($input));
        //  $columns = array_keys($input);
            $values = array_map(function ($value) use ($link) {
              if ($value===null) return null;
              return mysqli_real_escape_string($link,(string)$value);
            },array_values($input));

            // build the SET part of the SQL command
            $set = '';
            for ($i=0;$i<count($columns);$i++) {
              $set.=($i>0?',':'').'`'.$columns[$i].'`=';
              $set.=($values[$i]===null?'NULL':'"'.$values[$i].'"');
            }
            return $set;
}    

}



?>