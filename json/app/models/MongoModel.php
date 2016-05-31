<?php

require_once "ConexMongo.php";

class MongoModel extends ConexMongo{
private $conex;

    public function __construct() {
     $this->conex = parent::__construct(); //creo una variable con la conexión
     return $this->conex; 
    }

 
 
/******************Function to auto increment seq******************/
public function getNextSequence($data){
 $db = $this->conex;
 $collection = $db->counters;
$retval = $collection->findAndModify(
     array('_id' => $data),
     array('$inc' => array("seq" => 1)),
     null,
     array(
        "new" => true,
    )
);
return $retval['seq'];
}

//Crear una collecion para contadores
    public function createCollectionAutoincrement(){
        $db = $this->conex;   
        // a new products collection object
        $collection = $db->counters;

        // Create an array of values to insert 
        $data = array('_id' => 'id_groups',
                      'seg' => 0
                      );
        $collection->insert( $data );
        }
       public function addGroup($data){
        $db = $this->conex;
        $collection = $db->groups;
        // Create an array of values to insert 
        $id=$this->getNextSequence("id_groups");

        $data= array('_id' => $id)+$data;
        // se hace una simplicacions y se almacena las fecha como arrays
        $data['date_created']=date("Y-m-d H:i:s");
        $data['date_update']=date("Y-m-d H:i:s");

        $collection->insert($data);
        
        return $id;
        
        $db=NULL;       
    }

    public function oneGroup($collect,$id){
        $db = $this->conex;
        $collection = $db->$collect; 
        $array = $collection->findOne(array('_id'=> new MongoInt32($id)));
        return $array;
        $db=null;
    }
    public function deleteGroup($id){
        $db = $this->conex;
        $collection = $db->groups;
        $res=$collection->remove(array('_id' => new MongoInt64($id)));
        $db=NULL;       
    }
    public function oneItemKeyGroup($collect,$id,$value){
        $db = $this->conex;
        $collection = $db->$collect; 
        $array = $collection->findOne(array('_id' => new MongoInt64($id)));
            return $array[$value];
        $db=null;
    } 


/*
   
    public function putProyect($data){
        $db = $this->conex; 
        $collection = $db->proyects;

        // save back to the database
        $collection->save( $data ); 
        // close the connection to MongoDB 
         $db=null;
    }

 
    public function putDateProyects($id){


        $db = $this->conex; 
        $collection = $db->proyects;
        $idm=
        $document = $collection->findOne(array('_id' => new MongoInt32($id)),array("w" => true));

        // specify new values for Jackets
        $document['date'] = 'Leather Jackets';


        // save back to the database
        $collection->save( $document );        

        $res=$collection->remove(array('_id' => new MongoInt32($id)),array("w" => true));
        // close the connection to MongoDB 
        $db=null;
    }

*/



    public function addItem($collect,$data){
        $db = $this->conex;
        $collection = $db->$collect; 
        //$idm=new MongoId();
        $data['_id'] = (string)(new MongoId());

        // se hace una simplicacions y se almacena las fecha como arrays
        $data['date_created']=date("Y-m-d H:i:s");
        $data['date_update']=date("Y-m-d H:i:s");
        // insert the array
        $collection->insert($data);

        return $data['_id'];

        //$this->close();       
    }

    public function listItems($collect){
        $db = $this->conex;
        $collection = $db->$collect; 
        $cursor = $collection->find()->sort(array('date_update' => -1));
        $num_docs = $cursor->count();
        if( $num_docs > 0 ){
            return iterator_to_array($cursor);
        }else{
            return [];
        }
        $db=null;
    }
    public function listItemsValue($collect,$row,$value){
        $db = $this->conex;
        $collection = $db->$collect; 
        $cursor = $collection->find(array($row=> $value));
        $num_docs = $cursor->count();
        if( $num_docs > 0 ){
            return iterator_to_array($cursor);
        }else{
            return [];
        }
        $db=null;
    } 
  
    public function oneItem($collect,$id){
        $db = $this->conex;
        $collection = $db->$collect; 
        $array = $collection->findOne(array('_id'=> $id));
        
            return $array;
        $db=null;
    }
    public function oneItemKeyValue($collect,$id,$value){
        $db = $this->conex;
        $collection = $db->$collect; 
        $array = $collection->findOne(array('_id'=> $id));
        //echo "datos en model";
        //var_dump($array);
        if(isset($array[$value]))
            return $array[$value];
        $db=null;
    }    
    public function updateItem($collect,$data){
        //$data['_id']= new MongoId($data['_id']['$id']);

        $db = $this->conex; 
        $collection = $db->$collect;

        // save back to the database
        $collection->save( $data ); 
        // close the connection to MongoDB 
         $db=null;
    }
    public function updateRowItem($collect,$data,$row,$value){
        //$idm = new array('_id' => new MongoInt32($data['_id']));
        $db = $this->conex; 
        $collection = $db->$collect;

        // save back to the database
        $result=$collection->update(array('_id'=> $data['_id']), array('$set' => array ($row => $value)), array('multi' => false));
        // close the connection to MongoDB 
        $db=null;
    }

    public function deleteItem($collect,$id){
        $db = $this->conex;
        $collection = $db->$collect;
        $res=$collection->remove(array('_id' => $id));
        $db=NULL;       
    }

    public function listItemsInterval($collect,$row,$start,$end){
        $db = $this->conex;
        $collection = $db->$collect; 
        $res=$cursor = $collection->find(array('datetime' => array('$gte' => $start, '$lte' => $end)))->sort(array('datetime' => 1));

        $num_docs = $cursor->count();
        if( $num_docs > 0 ){
 
            return iterator_to_array($cursor);
        }else{
            echo json_encode(array('error' => 'No action found'));
        }
        $db=null;
    }

    public function sumItensDays($value){
           $data=$this->listItemsValue("diarydays", "id_action",$value);
           $total=0;
            foreach ($data as $doc) {
                $total+=$doc['time'];
            }
          return  $total;
        $db=null;
    }

    public function sumItens($collect,$row,$value,$sum){
                $db = $this->conex;
        $collection = $db->diarydays; 

        //$value=$collection->aggregate(array ('$group' => array ( 'id_action' => $value , 'total' => array ('$sum'=> '$time'))));

        $value=$collection->aggregate(array ('$group' => array (
                                                    '_id' =>array ('id_action' => $value),
                                                    'total' => array ('$sum'=> '$time'))));        
        var_dump($value);

          return $value;
        $db=null;
    }
}

/* Fechaa
        $utcdatetime = new MongoDate();
        $datetime = $utcdatetime->toDateTime();
        var_dump($datetime);
    
    devuelve
        object(DateTime)[6]
          public 'date' => string '2016-05-12 20:57:37.370000' (length=26)
          public 'timezone_type' => int 1
          public 'timezone' => string '+00:00' (length=6)

        //encontrar fecha
        $start = new MongoDate(strtotime("2010-01-15 00:00:00"));
        $end = new MongoDate(strtotime("2010-01-30 00:00:00"));

        // encontrar fechas entre 1/15/2010 y 1/30/2010
        $collection->find(array("ts" => array('$gt' => $start, '$lte' => $end)));
        */

?>