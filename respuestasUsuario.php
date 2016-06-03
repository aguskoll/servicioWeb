<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of respuestasUsuario
 *
 * @author tino
 */
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
	}
	case 'getOfertasPorEstablecimiento': {
		getOfertasPorEstablecimiento();
		break;
	}
	default: {
		echo '<html> default </html> ';
		break;
	}
}

function getOfertas(){   
    // Manejar petición GET
    $db= new Database();
    $ofertas= $db->getOfertas(); 
    if ($ofertas) {

        $datos["estado"] = 1;
        $datos["ofertas"] = $ofertas;

        print json_encode($datos);
       
    }
    else 
        {
        print json_encode(array(
            "estado" => 2,
            "mensaje" => "Ha ocurrido un error"
        ));
    }
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
       echo '<html>el codigo es '.$codigo.'</html>'; 
    
       $db= new Database();
       $oferta= $db->getOfertaCodigo($codigo); 
       armarJson($oferta,"oferta");
    
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

        print json_encode($datos);
       
    }
    else 
        {
            print json_encode(array(
                "estado" => 2,
                "mensaje" => "Ha ocurrido un error"
            ));
        }
     
 }   
 function getOfertasUbicacion($ubicacion){
    
    
    
}

function getOfertasPorEstablecimiento() {
	if(isset($_GET['idEstablecimiento'])) {
		$id= $_GET['idEstablecimiento'];
		$dato= Establecimiento::getOfertasPorEstablecimiento($id);
		armarJson($dato, "ofertas");		
	}
}
