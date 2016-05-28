<?php

require_once "Conexion.php";

//products -> id,name,description,name_icon,name_img,sale_price,id_category,history,lastdate,status
// categories -> id,name,description,order,n_products
class DevelopModel extends Conexion{
private $conex;

    public function __construct() {
     $this->conex = parent::__construct(); //creo una variable con la conexión
     return $this->conex; 
    }


    public function modElement($table,$campos,$values){
         $db = $this->conex;
         $dataout="";       
         $sql = "INSERT INTO $table ($campos) VALUES ($values)";
         $records = $db->prepare($sql);
         if ($records->execute()) {
               $dataout = $db->lastInsertId();//recoger id_accion
         } else {
             $dataout= APPSTATUS=="developer" ? "<p> GeneritModel->modElement. <br>".implode(',', $records->errorInfo())."</p>" : "<p>Error: al insertar libro</p>";
         }
         return $dataout;          
         $db = NULL;       
    }


    public function listAllElement($table){
// 'tabla'=>'libros','campos'=>'id,id_usuario,titulo,autor,editorial,ano_publica,notas,imagen,id_punto_actual,lugar_actual,fecha_crea,fecha_mod' 
        $db = $this->conex; 
        $sql = "SELECT * FROM ".$table;
        $records = $db->prepare($sql);
           if (!$records->execute()) {
                if(APPSTATUS=="developer")
                    echo "<p>Error GeneritModel->listAllElement. <br>".implode(',', $records->errorInfo())."</p>";
                else
                    echo "<p>Error</p>";
                exit;
            }
         return $records;
        $db = NULL;
  }
    public function listElement($table,$id){
// 'tabla'=>'libros','campos'=>'id,id_usuario,titulo,autor,editorial,ano_publica,notas,imagen,id_punto_actual,lugar_actual,fecha_crea,fecha_mod' 
        $db = $this->conex; 
        $sql = "SELECT * FROM ".$table." WHERE id=?";
        $records = $db->prepare($sql);
           if (!$records->execute(array($id))) {
                if(APPSTATUS=="developer")
                    echo "<p>Error GeneritModel->listElement. <br>".implode(',', $records->errorInfo())."</p>";
                else
                    echo "<p>Error</p>";
                exit;
            }
         return $records->fetch();
        $db = NULL;
  }
 
}

?>