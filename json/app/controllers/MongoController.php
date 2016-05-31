<?php
require_once ROUTEAPP."models/MongoModel.php";
require_once ROUTEAPP."controllers/CommonFunctions.php";
class MongoController {

     public function creatoAutoIncre() {
        $ob_db1 = new MongoModel;
        $data=$ob_db1->createCollectionAutoincrement();
        echo "crear collections counters";
     }

    public function postGroup() {
        $ob_db1 = new MongoModel;
        $data = json_decode(file_get_contents('php://input'), true);
        $id= $ob_db1 ->addGroup($data);
        if($id>0){
            echo '{"id" : "'.$id.'" , "status" : true , "method" : "POST"}';
        }else{
            echo '{"Error" : "ERROR:'.$id.'" , "status" : false , "method" : "POST"}';
        }
    }
    public function deleteGroup($folder) {
        $key=isset($folder[3]) ? $folder[3] : null;
        $ob_db1 = new MongoModel;
        $ob_db1 ->deleteGroup($key);
        echo '{"id" : false , "status" : true , "method" : "DELETE"}';
    }



    public function get($folder,$query) {
        $table=isset($folder[2]) ? $folder[2] : null;
        $key=isset($folder[3]) ? $folder[3] : null;
        $ob_db1 = new MongoModel;
        if(isset($query)){
            $d1=explode("=",urldecode($query[0]));
            $d2=explode("=",urldecode($query[1]));
           if($d1[0]=="dateini" and $d2[0]=="dateend"){
              $data=$ob_db1->listItemsInterval($table,'date',$d1[1],$d2[1]);
           }
        }else{
                if($key){
                   $data=$ob_db1->oneItem($table,$key);

                 }else{
                   $data=$ob_db1->listItems($table);
                 }
        }
        if(!$key){
             $exitData="[";
            if(is_array($data)){
            foreach ($data as $doc) {
                $exitData.=json_encode($doc).",";
            }
            }

            $exitData =$exitData!="[" ? substr($exitData, 0, -1):$exitData;

            $exitData.= ']';
        }else{
            $exitData=json_encode($data);
        }


        echo $exitData;
    }

     public function post($folder) {
        $table=isset($folder[2]) ? $folder[2] : null;
        $ob_db1 = new MongoModel;
        $data = json_decode(file_get_contents('php://input'), true);
        $id=$ob_db1 ->addItem($table,$data);
        echo '{"id" : "'.$id.'" , "status" : true , "method" : "POST"}';

           // echo '{"Error" : "ERROR:'.$id.'" , "status" : false , "method" : "POST"}';
    }

    public function put($folder) {
        $table=isset($folder[2]) ? $folder[2] : null;
        $ob_db1 = new MongoModel;
        $data = json_decode(file_get_contents('php://input'), true);
        $ob_db1 ->updateItem($table,$data);
        echo '{"id" : false , "status" : true , "method" : "PUT"}';
    }

// actualizar fechas de acceso a los elementos
    private function putDate($collec,$id) {
        $ob_db1 = new MongoModel;
        if($collec=="groups"){
          $data=array('_id'=> new MongoInt32($id));
        }else{
          $data=array('_id' => $id);
        }
        $ob_db1 ->updateRowItem($collec,$data,"date_update",date("Y-m-d H:i:s"));
    }

    public function delete($folder) {
        $table=isset($folder[2]) ? $folder[2] : null;
        $key=isset($folder[3]) ? $folder[3] : null;
        $ob_db1 = new MongoModel;
        $ob_db1 ->deleteItem($table,$key);
        echo '{"id" : false , "status" : true , "method" : "DELETE"}';
    }

    public function prueba($folder) {
        $table=isset($folder[2]) ? $folder[2] : null;
        $key=isset($folder[3]) ? $folder[3] : null;
                   echo "constroler pide".$key;
        $ob_db1 = new MongoModel;
           $data=$ob_db1->oneItemKeyGroup('groups',$key,"title");
        echo $data;
    }
    public function getActions($folder) {
        $key=isset($folder[3]) ? $folder[3] : null;
        $ob_db1 = new MongoModel;

         if($key){
            $this->putDate('actions',$key);
            $data=$ob_db1->oneItem('actions',$key);
            $data1=$ob_db1->listItemsValue('diarydays','id_action',$data['_id']);
            foreach ($data1 as $doc) {
                $doc["date_created"]=isset($doc["date_created"]) ? explode(" ",$doc["date_created"])[0]: "";
                $arrayData[]=$doc;
            }
            $data['diary']=isset($arrayData)? $arrayData :[];
            $exitData=json_encode($data);
        }else{
            $data=$ob_db1->listItems('actions');
            $exitData="[";
            foreach ($data as $doc) {

                $doc["time_real"]=$ob_db1->sumItensDays($doc['_id']);
                $doc["date_end"]=isset($doc["date_end"]) ? explode(" ",$doc["date_end"])[0]: "";
                $doc["date_created"]=isset($doc["date_created"]) ? explode(" ",$doc["date_created"])[0]: "";
                $exitData.=json_encode($doc).",";
            }
            $exitData = substr($exitData, 0, -1);
            $exitData.= ']';
        }
        echo $exitData;
    }
    public function getGroups($folder) {
        $key=isset($folder[3]) ? $folder[3] : null;
        $ob_db1 = new MongoModel;
         if($key){
            $this->putDate('groups',$key);
            $data=$ob_db1->oneGroup('groups',$key);

                $data1=$ob_db1->listItemsValue('actions','id_group',$data['_id']);


                foreach ($data1 as $doc) {

                    $doc["time_real"]=$ob_db1->sumItensDays($doc['_id']);

                    $arrayData[]=$doc;
                }
                $data['actions']=isset($arrayData)? $arrayData :[]; ;


            $exitData=json_encode($data);
        }else{
            $data=$ob_db1->listItems('groups');
            $exitData="[";
            foreach ($data as $doc) {


                $exitData.=json_encode($doc).",";
            }
            $exitData = substr($exitData, 0, -1);
            $exitData.= ']';
        }
        echo $exitData;
    }
    public function getDiaryDay($folder,$query) {
        $table=isset($folder[2]) ? $folder[2] : null;
        $key=isset($folder[3]) ? $folder[3] : null;
        $ob_db1 = new MongoModel;
        if(isset($query)){
            $d1=explode("=",urldecode($query[0]));
            $d2=explode("=",urldecode($query[1]));
           if($d1[0]=="dateini" and $d2[0]=="dateend"){
              $data=$ob_db1->listItemsInterval($table,'date',$d1[1],$d2[1]);
           }
        }
        $exitData="[";
        if(is_array($data)){
            foreach ($data as $doc) {

                $doc["date_created"]=isset($doc["date_created"]) ? explode(" ",$doc["date_created"])[0]: "";


                $doc["text"]=$doc["comment"]." - ".$ob_db1->oneItemKeyValue("actions",$doc["id_action"],"title");
                $exitData.=json_encode($doc).",";
            }
        }
        $exitData =$exitData!="[" ? substr($exitData, 0, -1):$exitData;

        $exitData.= ']';


        echo $exitData;
    }
    public function getResultData($folder,$query) {
        $key=isset($folder[3]) ? $folder[3] : null;
        $ob_db1 = new MongoModel;
        if(isset($query)){
            $d1=explode("=",urldecode($query[0]));
            $d2=explode("=",urldecode($query[1]));
           if($d1[0]=="dateini" and $d2[0]=="dateend"){

              $data=$ob_db1->listItemsInterval("diarydays",'date',$d1[1],$d2[1]);

              }
        }
        $dd=[];
        $exitData="[";
        if(is_array($data)){
            foreach ($data as $doc) {
                if(isset($doc["datetime"])){
                 $dd["date"]=explode(" ",$doc["datetime"])[0];
                }
                else{
                $dd["date"]="";
                }
                $dd["time"]=$doc["time"];
                $dd["name_action"]=$ob_db1->oneItemKeyValue("actions",$doc["id_action"],"title") ? $ob_db1->oneItemKeyValue("actions",$doc["id_action"],"title") : "Sin Tarea" ;
                $id_group=$ob_db1->oneItemKeyValue("actions",$doc["id_action"],"id_group") ;
                $dd["name_group"]=$ob_db1->oneItemKeyGroup("groups",$id_group,"title") ?$ob_db1->oneItemKeyGroup("groups",$id_group,"title") : "Sin grupo";

                $exitData.=json_encode($dd).",";
            }
        }
        $exitData =$exitData!="[" ? substr($exitData, 0, -1):$exitData;

        $exitData.= ']';


        echo $exitData;
    }

/*



//preparar los datos
    private function preparapost($input,$link){
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
*/
}



?>
