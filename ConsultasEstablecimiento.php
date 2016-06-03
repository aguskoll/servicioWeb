<?php

require 'Database2.php';

class Establecimiento {
	function __construct() {}
	
	/*
	* Obtiene las ofertas de un establecimiento
	*/
	public static function getOfertasPorEstablecimiento($idEstablecimiento) {
		$consulta= "SELECT * ". 
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
		$fecha,
		$hora
	) {
		$consulta= "INSERT INTO oferta (".	
				   "idUserCreador,".
				   " deporte,".
				   " precioHabitual,".
				   " precioOferta,".
				   " estado,".
				   " fecha,".
				   " hora)".
				   " VALUES(?,?,?,?,Disponible,?,?)";
		$comando= tabase2::getDB()->prepare($consulta);
		return $comando->excute(
			array(
				$idEstablecimiento,
				$deporte,
				$precioHab,
				$precioOferta,
				$fecha,
				$hora
				)
			);
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
			return $comando->fetchAll(PDO::FETCH_ASSOC);
		} catch(PDOException $e) {
			return -1;
		}
	}
	
	public static function getDeportesEst() {
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
}