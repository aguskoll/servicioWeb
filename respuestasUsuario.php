<?php

//No es necesario el require 'Database2.php' porque ya esta en este require, que es necesario
//Para una consulta mas abajo
require 'ConsultasEstablecimiento.php';

//if ($_SERVER['REQUEST_METHOD'] == 'GET') 
if (isset($_GET['funcion'])) {
    $funcion = $_GET['funcion'];
} 
else {
	$funcion= '';
}

switch ($funcion) {
    case "getOfertas": {
        getOfertas();
        break;
	}
    case "getOfertaCodigo": {
        getOfertaCodigo();
        break;
    case "getOfertasUbicacion":
       getOfertaUbicacion();
        break;
    case "getOfertasDeporte":
       getOfertasDeporte();
        break;
    case "reservarOferta":
        reservarOferta();
        break;
	case 'getOfertasPorEstablecimiento': {
		getOfertasPorEstablecimiento();
		break;
	}
}

function getOfertas(){   
    // Manejar petición GET
    $db= new Database();
    $ofertas= $db->getOfertas(); 
  
      
            
    armarJson($ofertas, "ofertas");
}
    /**
    retorna una oferta parseada en json, cuyo codigo es $codigo
     *     
     *  */
    function  getOfertaCodigo(){   
        
        if (isset($_GET['codigo'])) {
             $codigo = $_GET['codigo'];
        }
        else{
            echo '<html> no hay codigo </html>';
        }
     
    
       $db= new Database();
       $oferta= $db->getOfertaCodigo($codigo); 
       armarJson($oferta,"ofertas");
    
    }
    
 function getOfertaUbicacion($ubicacion){
    if (isset($_GET['ubicacion'])) {
             $ubicacion = $_GET['ubicacion'];
        }
        else{
            echo '<html> no hay ubicacion </html>';
        }
       $db= new Database();
       $ofertas= $db->getOfertaUbicacion($ubicacion); 
       armarJson($ofertas,"ofertas");
    
    
}
 function getOfertasDeporte(){
      if (isset($_GET['deporte'])) {
             $deporte = $_GET['deporte'];
        }
        else{
            echo '<html> no hay deporte </html>';
        }
       $db= new Database();
       $ofertas= $db->getOfertasDeporte($deporte); 
       armarJson($ofertas,"ofertas");
 }
 /*
  * http://localhost:85/webServiceAndroid2/respuestasUsuario.php?funcion=reservarOferta&codigo=123&idUser=1
 */
  function reservarOferta(){
      
      
      if (isset($_GET['codigo'])) {
             $codigo = $_GET['codigo'];
        }
        else{
            echo '<html> no hay codgigo </html>';
        }
      if (isset($_GET['idUser'])) {
             $idUser = $_GET['idUser'];
        }
        else{
            echo '<html> no hay idUser </html>';
        }
       $db= new Database();
       
       if($db->reservarOferta($codigo, $idUser))
        {
                $datos["estado"] = 1;
                $datos['ok'] = 'true';
                print json_encode($datos, JSON_UNESCAPED_UNICODE);
        }
       else
           {
            $datos["estado"] = 0;
            $datos["ok"] = 'false';
            print json_encode($datos, JSON_UNESCAPED_UNICODE);
       }
       
       
        
  }
   /**
    retorna el json
    * recibe el mensaje a mandar y el tipo de dato que es 
    *     */
 function armarJson($mensaje,$dato){
    
     if ($mensaje) 
    {

        $datos["estado"] = 1;
        $datos[$dato] = $mensaje;

        print json_encode($datos, JSON_UNESCAPED_UNICODE);
       
    }
    else 
        {
            print json_encode(array(
                "estado" => 2,
                "mensaje" => "Ha ocurrido un error"
            ));
        }
     
 }
 
function getOfertasPorEstablecimiento() {
	if(isset($_GET['idEstablecimiento'])) {
		$id= $_GET['idEstablecimiento'];
		$dato= Establecimiento::getOfertasPorEstablecimiento($id);
		armarJson($dato, "ofertas");		
	}
}