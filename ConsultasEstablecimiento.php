<?php

require 'Database2.php';
require 'login.php';

class Establecimiento {
	function __construct() {}
	
	/*
	* Obtiene las ofertas de un establecimiento
	*/
	public static function getOfertasPorEstablecimiento($idEstablecimiento) {
		$consulta= "SELECT codigo,idUserCreador,idUserComprador,deporte,precioHabitual,precioOferta,estado,fechaHora ". 
				   "FROM oferta ".
				   "WHERE idUserCreador = ".$idEstablecimiento;
		try {
			$db= new Database();
			$comando= $db->getDB()->prepare($consulta);
			$comando->execute();
			return $comando->fetchAll(PDO::FETCH_ASSOC);			
		} catch(PDOException $e) {
			return -1;
		}
	}
	
	/*
	* Obtiene un usuario en base al id 
	*/
	public static function getUserbyID($idUser) {
		$consulta= "SELECT *" .
				   "FROM establecimiento"  . 
				   "WHERE idUser = ?";
		try {
			$db= new Database();
			$comando= $db->getDB()->prepare($consulta);
			$comando->execute(array($idUser));
			return $comando->fetch(PDO::FETCH_ASSOC);
		} catch(PDOException $e) {
			return -1;
		}
	}
	
	/*
	* Obtiene un usuario en base al token
	*/
	public static function getUserbyToken($token) {
		$consulta= "SELECT * ".
				   "FROM establecimiento ". 
				   "WHERE token = ".$token;
		try {
			$db= new Database();
			$comando= $db->getDB()->prepare($consulta);
			$comando->execute();			
			return $comando->fetch(PDO::FETCH_ASSOC);
		} catch(PDOException $e) {
			return -1;
		}
	}
	
	/*
	* Obtiene un user por su nombre y mail
	*/
	public static function getUserByName($nombre, $mail) {
		$consulta= 'SELECT id FROM establecimiento WHERE nombre = ? AND email = ?';
		try {
			$db= new Database();
			$comando= $db->getDB()->prepare($consulta);
			$comando->execute(array($nombre,$mail));			
			return $comando->fetch(PDO::FETCH_COLUMN, 0);
		} catch(PDOException $e) {
			return -1;
		}
	}
	/*
	* Obtiene la información del pack de un usuario
	*/
	public static function getInfoPack($idUser) {
		$consulta= "SELECT cantidadOfertas ".
				   "FROM pack ". 
				   "WHERE id IN ( ".
						"SELECT idPack ".
						"FROM establecimiento ".
						"WHERE id =".$idUser.
				   ")";
		try {
			$db= new Database();
			$comando= $db->getDB()->prepare($consulta);
			$comando->execute();			
			return $comando->fetch(PDO::FETCH_COLUMN, 0);
		} catch(PDOException $e) {
			return -1;
		}
	}
	
	public static function getDeportes_Estab($userId) {
		$consulta= "SELECT nombre FROM deporte WHERE id IN ( ".
						"SELECT idDeporte FROM deporte_establecimiento WHERE idEstablecimiento = ".$userId.
				   " )";
		try {
			$db= new Database();
			$comando= $db->getDB()->prepare($consulta);
			$comando->execute();			
			return $comando->fetchAll(PDO::FETCH_COLUMN, 0);
		} catch (PDOException $e) {
			return -1;
		}
	}	
	
	public static function insertar_establecimiento_token(
		$tokenID,
		$nombre		
	) {
		$consulta= "INSERT INTO establecimiento (".
					"token,".
					"idPack,".
					"nombre) ".
					"VALUES(?,1,?)";
					
		$db= new Database();
		$comando= $db->getDB()->prepare($consulta);
		return $comando->execute(array($tokenID,$nombre));
	}
	
	public static function insertar_oferta(
		$idEstablecimiento,
		$deporte,
		$precioHab,
		$precioOferta,
		$fecha
	) {
		$consulta= 'INSERT INTO oferta ('.	
				   'idUserCreador,'.
				   ' deporte,'.
				   ' precioHabitual,'.
				   ' precioOferta,'.
				   ' estado,'.
				   ' fecha,'.
				   ' hora,'.
				   ' fechaHora)'.
				   ' VALUES(?,?,?,?,"Disponible",?,?,?)';
		$db= new Database();
		$comando= $db->getDB()->prepare($consulta);
		$dia= substr($fecha, 0, 10); //Solo la parte de la fecha 
		$hora= substr($fecha, 11); //La parte de la hora
		return $comando->execute(
			array(
				$idEstablecimiento,
				$deporte,
				$precioHab,
				$precioOferta,
				$dia,
				$hora,
				$fecha
				)
			);
	}
	
	public static function insertar_establecimiento($datos) 
	{
		$consulta= "INSERT INTO establecimiento (".	
				   "idPack,".
				   " nombre,".
				   " email) ".
				   " VALUES(1,?,?)";
		$db= new Database();
		$comando= $db->getDB()->prepare($consulta);
		$comando->execute(
			array(
				$datos['user'],
				$datos['email'],
				)
			);
		return $comando; 
	}
	
	//Prepara la base para la ejecucion de una consulta.
	public static function prepararPDO($consulta) {
		try {
			$db= new Database();
			$comando= $db->getDB()->prepare($consulta);
			$comando->execute();
			return $comando;
		} catch(PDOException $e) {
			return -1;
		}
	}
	
	//Devuelve la primer columna de la consulta
	public static function ejecutarConsulta($consulta) {
		try {	
			$comando= prepararPDO($consulta);		
			return $comando->fetch(PDO::FETCH_ASSOC);
		} catch(PDOException $e) {
			return -1;
		}
	}
	
	//Devuelve un arreglo con el resultado de la consulta
	public static function ejecutarConsultaArreglo($consulta) {
		try {
			$comando= prepararPDO($consulta);				
			return $comando->fetchAll(PDO::FETCH_COLUMN, 0);
		} catch(PDOException $e) {
			return -1;
		}
	}
	
	public static function getDeportes() {
		$consulta= "SELECT nombre FROM deporte";
		try {
			$db= new Database();
			$comando= $db->getDB()->prepare($consulta);
			$comando->execute();
			return $comando->fetchAll(PDO::FETCH_COLUMN, 0);
		} catch (PDOException $e) {
			return -1;
		}
	}	
	
	public static function getDeporteID($dep) {
		$consulta= 'SELECT id FROM deporte WHERE nombre=?';
		try {
			$db= new Database();
			$comando= $db->getDB()->prepare($consulta);
			$comando->execute(array($dep));
			return $comando->fetch(PDO::FETCH_COLUMN, 0);
		} catch (PDOException $e) {
			return -1;
		}
	}
	
	public static function insertarDeporte($id, $dep) {
		$depID= Establecimiento::getDeporteID($dep);
		if($dep) { //si no fallo
			$consulta= "INSERT INTO deporte_establecimiento VALUES (?,?)";
			try {
				$db= new Database();
				$comando= $db->getDB()->prepare($consulta);
				return $comando->execute(array($id,$depID));
			} catch (PDOException $e) {
			return -1;
			}
		}
		return -1;
	}
	
	public static function eliminarDeporte($id, $dep) {
		$depID= Establecimiento::getDeporteID($dep);
		if($dep) { //si no fallo
			$consulta= 'DELETE FROM deporte_establecimiento '.
						'WHERE idEstablecimiento=? AND idDeporte=?';
			try {
				$db= new Database();
				$comando= $db->getDB()->prepare($consulta);
				return $comando->execute(array($id,$depID));
			} catch (PDOException $e) {
			return -1;
			}
		}
		return -1;
	}
	
	public static function eliminar_oferta($cod) {
		$consulta= 'DELETE FROM oferta '.
					'WHERE codigo=?';
		try {
			$db= new Database();
			$comando= $db->getDB()->prepare($consulta);
			return $comando->execute(array($cod));
		} catch (PDOException $e) {
		return -1;
		}
	}
	
	public static function actualizar_establecimiento(
		$id, 
		$mail,
		$nombre,
		$password,
		$ubicacion,
		$direccion,
		$telefono) {
		$consulta= 'UPDATE establecimiento SET nombre=?, password=?, email=?, ubicacion=?, direccion=?, telefono=? WHERE id=?';
					
		try {
				$db= new Database();
				$comando= $db->getDB()->prepare($consulta);
				return $comando->execute(
									array(
										$nombre,
										$password,
										$mail,
										$ubicacion,
										$direccion,
										$telefono,
										$id)
									);
		} catch (PDOException $e) {
			return -1;
		}
	}
	
	public static function actualizar_oferta($cod,$precioHab,$precioFin,$fecha,$deporte) {
		$consulta= 'UPDATE oferta SET deporte=?, precioHabitual=?, precioOferta=?, fecha=?, hora=?, fechaHora=? WHERE codigo=?';
		try {
			$db= new Database();
			$comando= $db->getDB()->prepare($consulta);
			$dia= substr($fecha, 0, 10); //Solo la parte de la fecha 
			$hora= substr($fecha, 11); //La parte de la hora
			return $comando->execute(array($deporte,$precioHab,$precioFin,$dia,$hora,$fecha,$cod));
		} catch (PDOException $e) {
			return -1;
		}
	}
	
	public static function login($user,$pass) {
		$consulta= 'SELECT * FROM establecimiento WHERE nombre=? AND password=?';	
		try {
			$db= new Database();
			$comando= $db->getDB()->prepare($consulta);
			$comando->execute(array($user, $pass));
			return $comando->fetch(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			return -1;
		}
	}
}

function actualizarUser($id, $datos) {
	if(isset($datos['email'])) 
		$mail= $datos['email'];
	else $mail= '';
	if(isset($datos['telefono'])) 
		$tel= $datos['telefono'];
	else $tel= null;
	if(isset($datos['ubicacion']))
		$ubic= $datos['ubicacion'];
	else $ubic= null;
	if(isset($datos['direccion']))
		$direc= $datos['direccion'];
	else $direc= null;
	if(isset($datos['user'])) 
		$nombre= $datos['user'];
	else $nombre= '';
	if(isset($datos['pass'])) 
		$password= $datos['pass'];
	else $password= null;
	Establecimiento::actualizar_establecimiento($id,$mail,$nombre,$password,$ubic,$direc,$tel);
	if(isset($datos['deportesNuevos'])) {
		$deportes= $datos['deportesNuevos'];
		foreach($deportes as $dep) {
			Establecimiento::insertarDeporte($id, $dep);
		}
		unset($dep);
	}
	if(isset($datos['deportesEliminar'])) {
		$deportes= $datos['deportesEliminar'];
		foreach($deportes as $dep) {
			Establecimiento::eliminarDeporte($id, $dep);
		}
		unset($dep);
	}
	print json_encode(array("estado" => 1));	
}

function crearUser($datos) {
	//Creo el establecimiento
	Establecimiento::insertar_establecimiento($datos);
	//Lo obtengo
	$id= Establecimiento::getUserByName($datos['user'], $datos['email']);
	//Actualizo sus datos
	actualizarUser($id, $datos);	
}

function traerDeportes() {
	$datos = Establecimiento::getDeportes();
	armarJson($datos,"todosD");
}

function crearOferta($datos) {
	$idDep= Establecimiento::getDeporteID($datos['nombreDeporte']);
	Establecimiento::insertar_oferta(
		$datos['idEstablecimiento'],
		$idDep,
		$datos['precioHab'],
		$datos['precioAct'],
		$datos['fecha']
	);
	print json_encode(array("estado" => 1));	
}

function actualizarOferta($datos) {
	$depID= Establecimiento::getDeporteID($datos['nombreDeporte']);
	Establecimiento::actualizar_oferta(
		$datos['idOferta'],
		$datos['precioHab'],
		$datos['precioAct'],
		$datos['fecha'],
		$depID
	);
	print json_encode(array("estado" => 1));
}

function eliminarOferta($cod) {
	Establecimiento::eliminar_oferta($cod);
	print json_encode(array("estado" => 1));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$body= json_decode(file_get_contents("php://input"), true);
	if($body) {//Si no fallo
		$funcion= $body['funcion'];
		switch($funcion) {
			case 'login': {
				//1 represetna login con google, 0 el login nuestro.
				if(isset($body['token']))
					login(1, $body);
				else login(0, $body);
				break;
			}
			case 'actualizarUsuario': {
				ActualizarUser($body['idUser'], $body);
				break;
			}
			case 'crearUsuario': {
				crearUser($body);
				break;
			}
			case 'traerDeportes': {
				traerDeportes();
				break;
			}
			case 'crearOferta': {
				crearOferta($body);
				break;
			}
			case 'actualizarOferta': {
				actualizarOferta($body);
				break;
			}
			case 'eliminarOferta': {
				eliminarOferta($body['codigo']);
				break;
			}
		}
	} else {
		armarJson(0,0);
	}	
}