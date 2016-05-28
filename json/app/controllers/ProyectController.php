<?php
require_once ROUTEAPP."models/ProyectsModel.php";
require_once ROUTEAPP."models/ActionsModel.php";
require_once ROUTEAPP."controllers/CommonFunctions.php";
class ProyectController {

    public function __construct() {

        if (!isset($_SESSION)) {
            session_start();
        }


    }


    public function listProyects() {

        $ob_db1 = new ProyectsModel;
        $cad_json='[';
               foreach ($ob_db1->listProyects()->fetchAll(PDO::FETCH_ASSOC) as $item){ 
                    $cad_json.= json_encode($item).",";
                }  
        $cad_json = substr($cad_json, 0, -1);
        $cad_json.= ']';
        echo $cad_json;
    }

    public function proyect($folder) {
        $id_proyect=isset($folder[3]) ? $folder[3] : null;

        $ob_db1 = new ProyectsModel;

        $data=$ob_db1->findProyectId($id_proyect);
        var_dump($data);
        //$cad_json=json_encode($data);



        $ob_db2 = new ActionsModel;
        $data1='[';
               foreach ($ob_db2->listActions($id_proyect)->fetchAll(PDO::FETCH_ASSOC) as $item){ 
                    $data1.= json_encode($item).",";
                }  
        $data1= substr($data1, 0, -1);
        $data1.= ']';
        var_dump($data1);

        //echo $cad_json;
    }



    public function showBook($param,$errors=null) {
        parse_str($param[0]); //convierte string en variable
        $ob_db2 = new ActionsModel;       
        if(isset($id)){
            //editar LIbro
            $ob_db1 = new BooksModel;
            $params = array(
                'title'     => 'Editar Libro',
                'menu'      => $this->menuOption(),
                'url_form_exit' => '/cpanel/libro.php?id='.$id,
                'sites'     => $ob_db2->listSites(),           
                'books'     => $ob_db1->findBookId($id),
                ); 

        }else{
            //Nuevo libro
            $params = array(
                'title'         => 'Nuevo Libro',
                'menu'      => $this->menuOption(),
                'url_form_exit' => '/cpanel/libro.php',
                'sites'     => $ob_db2->listSites(),           
                'books'     => ['id'                =>''
                                ,'titulo'           =>''
                                ,'autor'            =>''
                                ,'editorial'        =>''
                                ,'ano_publica'      =>''
                                ,'notas'            =>''
                                ,'imagen'           =>'0.png'
                                ,'id_punto_actual'  =>''
                                ]
                );            
        }
                     
            require_once ROUTEAPP."views/admin/book_form.php";
    }


    public function newBook($param) {
           $dat_Post=[
             'id'               =>''
            ,'titulo'           =>'Required|alfa'
            ,'autor'            =>''
            ,'editorial'        =>''
            ,'ano_publica'      =>''
            ,'notas'            =>''
            ,'imagen'           =>''
            ,'id_punto_actual'  =>''
            ];

       $dat=validarDataForm($dat_Post);    

       $ob_db2 = new ActionsModel;             
               //si no se ha llenado la variable $error procede al envio de los datos a la BD
            if(!isset($dat['error'])){
                $ob_db1 = new BooksModel;
              echo ("id:".$dat['id_punto_actual']);
                $lugar_actual=$dat['id_punto_actual']>0 ? lugarActual("dejar",$dat['id_punto_actual'],$ob_db2) : "";

                $imagen= $dat['imagen']!="" ?  $dat['imagen'] : "0.png";  
                //comprobar si existe imagen el POST
                if(($_FILES['foto']['tmp_name'])!=""){
                    $nfoto=time();
                    $imagen=subirImagen($nfoto,$imagen);
                }
                echo "imagen subida:".$imagen;
                if($dat['id']>0){
                    $act_db=$ob_db1->modBook($dat['titulo'],$dat['autor'],$dat['editorial'],$dat['ano_publica'],$dat['notas'],$imagen,$dat['id_punto_actual'],$lugar_actual,$dat['id']);
                    $messageOk='EL LIBRO '.$dat['id'].' ACTUALIZADO CORRECTAMENTE.';
                        echo "punto actual en BD".$ob_db1->findBookId($dat['id'])['id_punto_actual'];
                        if($dat['id_punto_actual']>0 and $ob_db1->findBookId($dat['id'])['id_punto_actual']<1){
                            $act_db1=$ob_db1->firstAcction($dat['id'],$dat['id_punto_actual']); 
                            $act_db=$act_db1!="OK" ? $act_db1 : "";                            
                        }

                }else{
                    $act_db=$ob_db1->newBook($_SESSION['sid_user'],$dat['titulo'],$dat['autor'],$dat['editorial'],$dat['ano_publica'],$dat['notas'],$imagen,$dat['id_punto_actual'],$lugar_actual,date("Y-m-d H:i:s"));

                    if($act_db>0){                   
                        $messageOk='<h2>( Cod. '. $act_db.' )</h2> NUEVO ELEMENTO REGISTRADO CORRECTAMENTE.'; 
                        if($dat['id_punto_actual']>0){
                            $act_db1=$ob_db1->firstAcction($act_db,$dat['id_punto_actual']); 
                            $act_db=$act_db1!="OK" ? $act_db1 : "";                            
                        }

                     
                    }else{
                         $messageKO='ERROR:'.$act_db;
                    }

              
                }

                if($act_db==""){
                    header('Location: /cpanel/libros.php?m='.$messageOk.'&t=success'); 
                    exit();              
                }else{
                    header('Location: /cpanel/libros.php?m='.$messageKO.'&t=danger'); 
                    exit();  
                }
                                  
            }else{
                $params = array(
                'title'     => 'Editar Libro',
                'menu'      => $this->menuOption(),
                'url_form_exit' => '/cpanel/libro.php',
                'sites'     => $ob_db2->listSites(),           
                'books'     => $dat,
                ); 

            require_once ROUTEAPP."views/admin/book_form.php"; 
 
           }
    
    }

//******************************************************************* END BOOKS

//**** COMMENTS *****************************

   public function listComments() {
        $ob_db1 = new ActionsModel;




        $params = array(
            'title' => 'Panel Comentarios',
            'menu'      => $this->menuOption(),
            'comments' =>  $ob_db1->commentsList('All')
            ); 



        require_once ROUTEAPP."views/admin/comments_list.php";
    }

   public function modComments() {
      echo "llega POST tipo: ".$_POST['type']."-- id:".$_POST['id'];
           $dat_Post=[
             'id'     =>'Required|Num'
            ,'type'   =>'Required'
            ];

       $dat=validarDataForm($dat_Post); 
            echo "llega petion tipo: ".$dat['type']."-- id:".$dat['id'];
       if($dat){
          $ob_db1 = new ActionsModel;
         if ($dat['type']=="ok"){
               $ob_db1->checkComments($dat['id']);
            }

         if ($dat['type']=="ko"){
           $ob_db1->delComments($dat['id']);
         }
        }
    }

//******************************************************************* END COMMENTS

//**** PUNTOS *****************************

   public function listPuntos() {
        $ob_db1 = new ActionsModel;
        $params = array(
            'title' => 'Puntos Recogida',
            'menu'      => $this->menuOption(),
            'points' =>  $ob_db1->pointsList()
            );       
        require_once ROUTEAPP."views/admin/points_list.php";
    }

   public function modPunto() {
        $ob_db1 = new ActionsModel;
        $params = array(
            'title' => 'Panel Comentarios',
            'menu'      => $this->menuOption(),
            'comments' =>  $ob_db1->commentsList('All')
            );       
        require_once ROUTEAPP."views/admin/comments_list.php";
    }

//******************************************************************* END PUNTOS


//**** USER *****************************  


    public function listUsers() {
        $ob_db1 = new UsersModel;

        $params = array(
            'title' => 'Lista Usuarios',
            'menu'      => $this->menuOption(),
            'users' =>  $ob_db1->listUsers()
            );       
        require_once ROUTEAPP."views/admin/users_list.php";
    }

    public function showUser($param) {
        if(isset($param[0])){
             $dato=explode("=",strip_tags(trim($param[0])));
             if($dato[0]=="edita"){
                $id= $_SESSION['sid_user']; 
             }else{
                $id=$dato[0]=="id" ? filter_var($dato[1], FILTER_VALIDATE_INT) : null;              
             }
        }else{
            $id=null;
        }
             
        if(isset($id)){
            $ob_db1 = new UsersModel;


            $params = array(
                'title' => 'Usuario',
                'menu'      => $this->menuOption(),
                'url_form_exit' =>'/cpanel/usuario.php?id='.$id,
                'error'     => isset($errors) ?  $errors : null,                
                'user'     => $ob_db1->findUser($id),
                );  
        }else{
            $params = array(
                'title' => 'Usuario',                        
                'menu'      => $this->menuOption(),
                'url_form_exit' =>'/cpanel/usuario.php',
                'user'     => ['id'            =>''
                                ,'email'        =>''
                                ,'nombre'       =>''
                                ,'permiso'      =>'user'
                                ,'telefono'     =>''
                                ,'notas'        =>''
                                ,'activo'       =>'1'
                                ]
                );            
        }
             
    require_once ROUTEAPP."views/admin/user_form.php";
    }

    private function userDatNew(){

    }

    private function datUser(){
     if (isset($_POST['enviar'])) {
            $dat_Post=[
             'id'           =>''
            ,'email'        =>'Required|Email'
            ,'nombre'       =>'Required|Alfa|Min:3'
            ,'permiso'      =>'Required'
            ,'telefono'     =>'Required|Num|Max:12|Min:5'
            ,'notas'        =>''
            ,'activo'       =>''
            ];
        }
        $dat_Val=validarDataForm($dat_Post);  
       return  $dat_Val;
    }

    public function addUser() {
        $dat=$this->datUser();
            //si no se ha llenado la variable $error procede al envio de los datos a la BD
       if(!isset($dat['error'])){
            $ob_db1 = new UsersModel;
            if($dat['id']>0){
                $valor=$ob_db1->modUser($dat['email'],$dat['nombre'],$dat['permiso'],$dat['telefono'],$dat['notas'],$dat['activo'],$dat['id']);
                if($valor=="OK"){
                    $messageOk='REGISTRO ACTUALIZADO CORRECTAMENTE.';
                }

            }else{
                $valor=$ob_db1->newUse($dat['email'],$dat['nombre'],$dat['permiso'],$dat['telefono'],$dat['notas'],$dat['activo'],$this->encripPass(PASSDEFATUL));
                if($valor=="OK"){
                    $messageOk='<p>NUEVO USUARIO CREADO REGISTRADO CORRECTAMENTE.<p><p>Su contraseña es : <b>'.PASSDEFATUL.'</b> ';
                }
            
            }

           if($valor=="OK"){
                header('Location: /cpanel/usuarios.php?m='.$messageOk."&t=success");
                exit;               
            }else{
               $dat['error'][]=$valor;
            }
           
       }
        $params = array(
            'title' => 'Usuario',
            'menu'      => $this->menuOption(),
            'url_form_exit' =>'/cpanel/usuario.php',
            'user'     => $dat,
            );   
        require_once ROUTEAPP."views/admin/user_form.php";
     
    }

    public function changePass(){
        if (isset($_POST['enviar'])) {
            $dat_Post=[
             'pass'         =>'Required'
            ,'pass1'        =>'Required'
            ];
        }
        $dat_Val=validarDataForm($dat_Post);
        if(empty ($dat_Val)){ 
            if ($dat_val['pass']!=$dat_val['pass1']) {
            
            }
        }
    }
    public function encripPass($pass){

        //$pass_cript=crypt($pass, '$2a$07$' . $caracteres_aleatorios . '$');
        if (CRYPT_BLOWFISH != 1) 
            die("error al encriptar");
        return crypt($pass, '$2a$07$'.bin2hex(openssl_random_pseudo_bytes(22)).'$');

    }

}

?>