<?php 


function lugarActual($tipoac,$idpunto,$ob_db){
                if($tipoac=='dejar'){
                    $lugares=$ob_db->findSites($idpunto);
                    $lugar_actual=$lugares[0];
                }else{
                    $lugar_actual='VIAJANDO';
                }
       return $lugar_actual;
}


function messageError($mensaje){
	echo "<div role='alert' class='alert alert alert-danger fade in'>
    <button aria-label='Close' data-dismiss='alert' class='close' type='button'><span aria-hidden='true'>×</span></button>";
    
    foreach ($mensaje as $item){
    	echo "<p>".$item."</p>";
    }

    echo "</div>";
}  


// VALIDAR DATOS DE FORMULARIOS ************************************************
$text_error=array('Required' => "El valor |key no puede ser vacio" 
		,'Email' => "La direccion de email |key es incorrecta"
		,'Max' => "El valor |key de No puede ser mayor de |value"	
		,'Min' => "El valor de |key. No puede ser menor de  |value"
		,'Num' => "El valor de |key. Solo admite valores numericos"										
	);



function getPost($var) {
	if (isset($_POST[$var]))
		$tmp=strip_tags(trim($_POST[$var]));
	else 
		$tmp='';
	return $tmp;
}
   
function validarDataForm($dat_array){
	$data=[];
	foreach ($dat_array as $key => $value) {
		$data[$key]=getPost($key);		
		if ($value!=""){
			$param=explode('|',$value);
			foreach ($param as $pvalue) {	
				if($pvalue=="Required"){
					$e=dataRequires($data[$key],$key);
					if($e<1){
						$data['error'][]=$e;
					}	
				}
				if($pvalue=="Email"){
					$e=dataEmail($data[$key],$key);
					if($e<1){
						$data['error'][]=$e;
					}	
				}
				if($pvalue=="Num"){
					$e=dataNum($data[$key],$key);
					if($e<1){
						$data['error'][]=$e;
					}	
				}								
				if(preg_match('#^Min*#s',$pvalue)){
					$para=explode(':',$pvalue);
					$e=dataMin($data[$key],$para[1],$key);
					if($e<1){
						$data['error'][]=$e;	
					}
				}	
				if(preg_match('#^Max*#s',$pvalue)){

					$para=explode(':',$pvalue);
					$e=dataMax($data[$key],$para[1],$key);					
					if($e<1){
						$data['error'][]=$e;	
					}
				}   		
			}
		}	
	}
	return $data;

}
function dataNum($data,$key){
	global $text_error;
	if(!preg_match("/^[0-9]+$/",$data)){		
		return str_replace('|key',$key,$text_error['Num']);
	}else{
		return 1;	
	}
}
function dataRequires($data,$key){
	global $text_error;
	if($data==""){		
		return str_replace('|key',$key,$text_error['Required']);
	}else{
		return 1;	
	}
}
function dataMax($data,$max,$key){
	global $text_error;
	if(strlen($data)>$max){	
		$c=	str_replace('|key',$key,$text_error['Max']);
		return str_replace('|value',$max,$c);
	}else{	
		return 1;	
	}
}
function dataMin($data,$min,$key){
	global $text_error;	
	if(strlen($data)<$min){		
		$c=	str_replace('|key',$key,$text_error['Min']);
		return str_replace('|value',$min,$c);
	}else{	
		return 1;	
	}
}

function dataEmail($data,$key){
	global $text_error;	
	if (!filter_var($data, FILTER_VALIDATE_EMAIL)) {
    	return str_replace('|key',$key,$text_error['Email']);
	}else{
		return 1;	
	}
}

// FIN VALIDAR DATOS DE FORMULARIOS **************************************************