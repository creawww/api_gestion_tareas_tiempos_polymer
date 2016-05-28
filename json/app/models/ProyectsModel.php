<?php

require_once "Conexion.php";

//products -> id,name,description,name_icon,name_img,sale_price,id_category,history,lastdate,status
// categories -> id,name,description,order,n_products
class ProyectsModel extends Conexion{
private $conex;

    public function __construct() {
     $this->conex = parent::__construct(); //creo una variable con la conexión
     return $this->conex; 
    }

    public function listProyects(){
// 'tabla'=>'proyects','campos'=>'id,title,description,date_crea,date_mod'
        $db = $this->conex; 
        $sql = "SELECT * FROM proyects";
        $records = $db->prepare($sql);
           if (!$records->execute()) {
                if(APPSTATUS=="developer")
                    echo "<p>Error ProyectsModel-> listProyects. <br>".implode(',', $records->errorInfo())."</p>";
                else
                    echo "<p>Error</p>";
                exit;
            }
         return $records;
        $db = NULL;
  }
    public function findProyectId($id){
        $db = $this->conex; 
        $sql = "SELECT title,description,date_crea,date_mod FROM proyects WHERE id=?";
        $records = $db->prepare($sql);
           if (!$records->execute(array($id))) {
                if(APPSTATUS=="developer")
                    echo "<p>Error BooksModel->findProyectId. <br>".implode(',', $records->errorInfo())."</p>";
                else
                    echo "<p>Error</p>";
                exit;
            }
         return $records->fetch();
        $db = NULL;
  }
 
}

?>