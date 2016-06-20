<?php

//Si esta bien formada la url y es un login por token
function login($tipo, $body) {
	if($tipo) {
		//diferenciar token de la app
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
				$ofertas= Establecimiento::getOfertasPorEstablecimiento($user['id']);
				$datos["user"]= $user;
				$datos["cantPack"]= $infoPack;
				$datos["deportes"]= $deportesEst;					
				$datos["ofertas"]= $ofertas;
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
					$ofertas= Establecimiento::getOfertasPorEstablecimiento($user['id']);
					$datos["user"]= $user;
					$datos["cantPack"]= $infoPack;
					$datos["deportes"]= $deportesEst;					
					$datos["ofertas"]= $ofertas;
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
			$user= $body['user'];
			$pass= $body['pass'];
			if($user && $pass) {
				$user= Establecimiento::login($user,$pass);
				if($user) {
					$infoPack= Establecimiento::getInfoPack($user['id']);
					$deportesEst= Establecimiento::getDeportes_Estab($user['id']);
					$ofertas= Establecimiento::getOfertasPorEstablecimiento($user['id']);
					$datos["user"]= $user;
					$datos["cantPack"]= $infoPack;
					$datos["deportes"]= $deportesEst;
					$datos["ofertas"]= $ofertas;
					//Armar el arreglo en $user
					armarJson($datos, "usuario");
				} else 
					armarJson(0,0);				
			} else 
				armarJson(0,0);		
	} 
	return;
}

function armarJson($mensaje,$dato){
    if ($mensaje) {
        $datos["estado"] = 1;
        $datos[$dato] = $mensaje;
		echo json_encode($datos, JSON_PRETTY_PRINT);       
    }else if($mensaje == 0) {
            print json_encode(array(
                "estado" => 3,
                "mensaje" => "Consulta vacía"
            ));
        } else {
            print json_encode(array(
                "estado" => 2,
                "mensaje" => "Ha ocurrido un error"
            ));

		}
}  
