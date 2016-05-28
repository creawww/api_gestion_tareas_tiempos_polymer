<?php
$routes = array(
'/json/init' => array('GET'=>array('controller' =>'MongoController', 'action' =>'creatoAutoIncre')),


'/json/groups'=> array('GET'=>array('controller' =>'MongoController', 'action' =>'getGroups'),
						'POST'=>array('controller' =>'MongoController', 'action' =>'postGroup'),
						'PUT'=>array('controller' =>'MongoController', 'action' =>'put'),	
						'DELETE'=>array('controller' =>'MongoController', 'action' =>'deleteGroup'),
				),

'/json/actions' => array('GET'=>array('controller' =>'MongoController', 'action' =>'getActions'),
						'POST'=>array('controller' =>'MongoController', 'action' =>'post'),
						'PUT'=>array('controller' =>'MongoController', 'action' =>'put'),	
						'DELETE'=>array('controller' =>'MongoController', 'action' =>'delete'),
				),

'/json/diarydays' => array('GET'=>array('controller' =>'MongoController', 'action' =>'getDiaryDay'),
						'POST'=>array('controller' =>'MongoController', 'action' =>'post'),
						'PUT'=>array('controller' =>'MongoController', 'action' =>'put'),	
						'DELETE'=>array('controller' =>'MongoController', 'action' =>'delete'),
				),

'/json/resultdata' => array('GET'=>array('controller' =>'MongoController', 'action' =>'getResultData')),

'/json/prueba' => array('GET'=>array('controller' =>'MongoController', 'action' =>'prueba')),

'/json/Groupmysql' => array('GET'=>array('controller' =>'GroupController', 'action' =>'listGroups')),

'/json/Groupmysql' => array('GET'=>array('controller' =>'GroupController', 'action' =>'Group'),
						'POST'=>array('controller' =>'GroupController', 'action' =>'Group'),
						'PUT'=>array('controller' =>'GroupController', 'action' =>'put'),	
						'DELETE'=>array('controller' =>'GroupController', 'action' =>'delete'),
				),
);


