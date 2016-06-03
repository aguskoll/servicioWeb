<?php

require 'ConsultasEstablecimiento.php';

//Si esta bien formada la url y es un login por token
if($_SERVER['REQUEST_METHOD'] == 'POST') {
	//diferenciar token de la app
	$body= json_decode(file_get_contents("php://input"), true);
	$token= $body['token'];
	
	//1ro Google analiza la validez del token
	$url= "https://www.googleapis.com/oauth2/v3/tokeninfo?id_token=".$token;
	$tokenEndPoint= file_get_contents($url);
	
	//Decodifico la respuesta
	$body= json_decode($tokenEndPoint, true);
	
	if($body) {
		//Si no fallo, obtengo el identificar del user
		$sub= $body['sub'];
		$user= Establecimiento::getUserbyToken($sub);
		if($user) {
			//Si ya existe devuelvo su informacion
			$infoPack= Establecimiento::getInfoPack($user['id']);
			$deportesEst= Establecimiento::getDeportes_Estab($user['id']);
			$lala= Establecimiento::getDeportesEst();
			$datos["user"]= $user;
			$datos["cantPack"]= $infoPack;
			$datos["deportes"]= $deportesEst;
			$datos["todosD"]= $lala;
			//Armar el arreglo en $user
			armarJson($datos, "usuario");
		} else {			
			//Creo la nueva entrada en la base
			$user= Establecimiento::insertar_establecimiento_token(
										$sub,
										$body['given_name']
									);
			if($user) {
				//Si la creacion fue exitosa				
				$user= Establecimiento::getUserbyToken($sub);
				$infoPack= Establecimiento::getInfoPack($user['id']);
				$deportesEst= Establecimiento::getDeportes_Estab($user['id']);
				
				$datos["user"]= $user;
				$datos["cantPack"]= $infoPack;
				$datos["deportes"]= $deportesEst;
				
				//Armar el arreglo en $user
				armarJson($datos, "usuario");
			}
			else armarJson(0,0);
		}
	} else {
		//Mensaje de error
		armarJson(0,0);		
	}
} else {
	if (isset($_GET['user'])) {
		$user = $_GET['user'];
	}
	if (isset($_GET['pass'])) {
		$pass = $_GET['pass'];
	} 	
}

function armarJson($mensaje,$dato){
    if ($mensaje) {
        $datos["estado"] = 1;
        $datos[$dato] = $mensaje;
		//$out = array_values($datos);
        echo json_encode($datos, JSON_PRETTY_PRINT);       
    }else {
            print json_encode(array(
                "estado" => 2,
                "mensaje" => "Ha ocurrido un error"
            ));
        }     
 }   
