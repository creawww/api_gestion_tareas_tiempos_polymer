<?php

require_once "Conexion.php";
// 'tabla'=>'comentarios','campos'=>'id,id_accion, id_libro, id_lector, puntuacion,lugares,comentarios,revisado' 

class BooksModel extends Conexion{
private $conex;

    public function __construct() {
     $this->conex = parent::__construct(); //creo una variable con la conexión
     return $this->conex; 
    }

    public function listComments(){
        $db = $this->conex;
    //$consulta = "SELECT M.id as idm,nombre,apellido,foto,remitente,destinatario,fecha,asunto,texto,respuestade FROM mensajes M JOIN usuarios U ON U.id = M.destinatario WHERE remitente='$id' ORDER BY M.id DESC";

        $consulta = "SELECT L.id,id,id_accion, id_libro, id_lector, puntuacion,lugares,comentarios,revisado,P.nombre as punto_actual,fecha_mod FROM libros L JOIN puntos P ON P.id=L.id_punto_actual ORDER BY L.id DESC";
        $result = $db->query($consulta);
        $db = NULL;
        return  $result;
    }

}

?>