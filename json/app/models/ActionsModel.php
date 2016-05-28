<?php

require_once "Conexion.php";


class ActionsModel extends Conexion {
private $conex;

    public function __construct() {
     $this->conex = parent::__construct(); //creo una variable con la conexión
     return $this->conex; 
    }
// ------------- acciones Public -----------------------------
    public function listActions($id_proyect){
//('tabla'=>'actions','campos'=>'id,id_user,id_proyect,description,date_deliver,time_proyected,date_end,date_created,date_update')
        $db = $this->conex; 
        $sql = "SELECT id,description,date_deliver,time_proyected,date_end,date_created,date_update FROM actions WHERE id_proyect=?";
        $records = $db->prepare($sql);
           if (!$records->execute(array($id_proyect))) {
                if(APPSTATUS=="developer")
                    echo "<p>Error ActionsModel->  listActions. <br>".implode(',', $records->errorInfo())."</p>";
                else
                    echo "<p>Error</p>";
                exit;
            }
         return $records;
        $db = NULL;
  }


public function newAccion($id_user,$id_proyect,$description,$date_deliver,$time_proyected,$date_update){
        $db =$this->conex;
        $error=""; 
        $sql = "INSERT INTO actions (id_user,id_proyect,description,date_deliver,time_proyected,date_update) VALUES (?,?,?,?,?,?,?)";
        $records = $db->prepare($sql);
            if ($records->execute(array($id_user,$id_proyect,$description,$date_deliver,$time_proyected,$date_update))) {
                $id_accion = $db->lastInsertId();//recoger id_accion
            }else{
                    if(APPSTATUS=="developer")
                        echo "<p>Error ActionsModel->  newAccion <br>".implode(',', $records->errorInfo())."</p>";
                    else
                        echo "<p>Error.</p>";
                    exit;
            }
        return $id_accion;
        $db = NULL;
}


}

?>
