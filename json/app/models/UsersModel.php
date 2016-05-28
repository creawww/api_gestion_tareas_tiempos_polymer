<?php

require_once "Conexion.php";
 
//usuarios -> id, email, pass, nombre, permiso, telefono, notas, activo, fecha_crea 
class UsersModel extends Conexion {

private $conex;

    public function __construct() {
     $this->conex = parent::__construct(); //creo una variable con la conexión
     return $this->conex; 
    }


function listUsers(){
    $db =  $this->conex;
    $consulta = "SELECT * FROM usuarios";
    $result = $db->query($consulta);
    if (!$result) {
        print "<h1>ERROR(1): en operacion sobre la BD</h1>";
        exit;
    }   
    return  $result;
}
public function findUser($id){

        $db = $this->conex; 
        $sql = "SELECT  id, email, nombre, permiso, telefono, notas, activo, fecha_crea FROM usuarios WHERE id=?";
        $records = $db->prepare($sql);
        $records->execute(array($id));
        $result = $records->fetch();
    if (!$result) {
        print "<h1>ERROR(1): en operacion sobre la BD</h1>";
        exit;
    }         
        return $result;
        $db = NULL;

    }
function newUse($email,$nombre,$permiso,$telefono,$notas,$activo,$pass){
    $db =  $this->conex;
    $error="OK";
    $res1 = $db->query("SELECT COUNT(*) FROM usuarios WHERE email='$email'");
    if ($res1->fetchColumn()>0) { //comprobar si existe el email
       $error="Ese email ya existe. seleccione otro.";
    } else{ 
        $consulta = "INSERT INTO usuarios (email, nombre, permiso, telefono, notas, activo, pass) VALUES (?,?,?,?,?,?,?)";
        $result = $db->prepare($consulta);
        if (!$result->execute(array($email,$nombre,$permiso,$telefono,$notas,$activo,$pass))) {
            if(APPSTATUS=="developer")
                $error= "<p>ERROR: al crear usuario. <br>".implode(',', $result->errorInfo())."</p>";
            else
                $error= "<p>ERROR: al crear usuario.</p>";
        }
    }   
        
        return $error;
        $db = NULL;

}

function modUser($email,$nombre,$permiso,$telefono,$notas,$activo,$id){
    $db = $this->conex;
    $error="OK";
    echo "SELECT COUNT(*) FROM usuarios WHERE email='$email' AND id<>$id";
    $res1 = $db->query("SELECT COUNT(*) FROM usuarios WHERE email='$email' AND id<>$id");
    if ($res1->fetchColumn()>0) { //comprobar si existe el email
        $error="<p>Ese email ya existe. seleccione otro.</p>";
    } else{ 
        $sql = "UPDATE usuarios SET email=?,nombre=?,permiso=?,telefono=?,notas=?,activo=? WHERE id=?";
        $result = $db->prepare($sql);
        if (!$result->execute(array($email,$nombre,$permiso,$telefono,$notas,$activo,$id))) {
            if(APPSTATUS=="developer")
                $error= "<p>ERROR: al modificar los datos del usuario. <br>".implode(',', $result->errorInfo())."</p>";
            else
                $error= "<p>ERROR: al modificar los datos del usuario</p>";
        }
    }   
    return $error;
    $db = NULL;
}

    function authUser($email){
        $db = $this->conex;
        $sql="SELECT  id, email, pass, nombre, permiso FROM usuarios WHERE email=? and activo='1'";
        $records = $db->prepare($sql);
        $records->execute(array($email));
        $result = $records->fetchAll();
        return $result;
         $db = NULL;
    }

    function insertPass($pass,$id_user){
        $db = $this->conex;
        $sql = "UPDATE usuarios SET pass=? WHERE id=?";
        $result = $db->prepare($sql);

        if (!$result->execute(array($pass,$id_user))) {
          $error[]="<p>ERROR: al modificar los datos del usuario. <br>".implode(',', $result->errorInfo())."</p>";
        }
    }
}

?>